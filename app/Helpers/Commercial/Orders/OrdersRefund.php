<?php

namespace App\Helpers\Commercial\Orders;

use App\Http\Controllers\Services\CommercialInvoiceTrait;
use App\Model\Commercial\OrderInvoiceRefund;
use App\Model\Commercial\OrderInvoiceRefundProducts;
use App\Model\Commercial\OrderProducts;
use App\Model\Commercial\OrderSales;

use NFePHP\NFe\Common\Standardize;
class OrdersRefund
{

    use CommercialInvoiceTrait;

    private $xml = null;
    private $pdf = null;
    private $orderInvoice = null;
    private $nfe_number = null;
    private $nfe_key = null;
    private $verifyRepeatOrder = [];

    public function __construct($xml, $pdf) {
        $this->xml = $xml;
        $this->pdf = $pdf;
    }    

    public function saveRefund($order_invoice, $arr_rel_refund) {  

        $arr_xml = $this->xml->toArray();
        $nfe = $arr_xml['NFe']['infNFe'];
        $infNfe = $arr_xml['protNFe']['infProt'];

        $this->nfe_number = $nfe['ide']['nNF'];
        $this->nfe_key = $infNfe['chNFe'];
        $this->orderInvoice = $order_invoice;

        $invoice_refund = OrderInvoiceRefund::where('nf_number', $nfe['ide']['nNF'])->first();
        if(!$invoice_refund) {

            $refund = new OrderInvoiceRefund;
            $refund->order_invoice_id = $this->orderInvoice->id;
            $refund->order_sales_id = $this->orderInvoice->order_sales_id;
            $refund->origin_nf_serie = $this->orderInvoice->nf_serie;
            $refund->origin_nf_number = $this->orderInvoice->nf_number;
            $refund->nf_serie = $nfe['ide']['serie'];
            $refund->nf_number = $nfe['ide']['nNF'];
            $refund->nf_key = $infNfe['chNFe'];
            $refund->nf_total = $nfe['total']['ICMSTot']['vNF'];
            $refund->nf_icms_total = $nfe['total']['ICMSTot']['vICMS'];
            $refund->nf_pis_total = $nfe['total']['ICMSTot']['vPIS'];
            $refund->nf_cofins_total = $nfe['total']['ICMSTot']['vCOFINS'];
            $refund->date_emission = date('Y-m-d', strtotime($nfe['ide']['dhEmi']));
            $archive = $this->uploadArchive($nfe['ide']['nNF'], $nfe['ide']['serie'], $nfe['ide']['dhEmi']);
            $refund->nf_xml_url = $archive['nf_xml_url'];
            $refund->nf_pdf_url = $archive['nf_pdf_url'];

            if($refund->save()) {
                $this->invoiceProducts($nfe['det'], $refund, $arr_rel_refund, $refund->order_sales_id);
            }
        } else {
            throw new \Exception('Devolução de número '.$nfe['ide']['nNF'].' já importada!');
        }  
    }

    private function invoiceProducts($nfe_det, $refund, $arr_rel_refund, $order_sales_id) {

        foreach($nfe_det as $key) {
            
            $index = array_search($key['prod']['cProd'], $arr_rel_refund['refund_cprod']);
            $data = json_decode($arr_rel_refund['refund_data'][$index], true);

            $product = new OrderInvoiceRefundProducts;
            $product->order_invoice_refund_id = $refund->id;
            $product->code_product = $key['prod']['cProd'];
            $product->description_product = $key['prod']['xProd'];
            $product->quantity = $key['prod']['qCom'];
            $product->price_unit = $key['prod']['vUnCom'];
            $product->price_total = $key['prod']['vProd']; 
            $values_icms = $this->getValuesICMS($key['imposto']['ICMS']);
            $product->price_base_icms = $values_icms['vBC'];
            $product->price_icms = $values_icms['vICMS'];
            $product->product_id = $data['product_id'];
            $product->set_product_id = $data['set_product_id'];
            $product->btus = $data['set_product_btus'];
            $product->set_product_group_id = $data['set_product_group_id'];
            
            if($product->save()) {
                $this->orderInvoice->is_refund = 1;
                $this->orderInvoice->save();
                if(!in_array($data['set_product_id'], $this->verifyRepeatOrder)) {
                    array_push($this->verifyRepeatOrder, $data['set_product_id']);
                    $this->updateQuantityRefund($data['set_product_id'], $order_sales_id, $key['prod']['qCom']);
                }
            }
        }
    }

    private function updateQuantityRefund($set_product_id, $order_sales_id, $quantity) {
        
        $order_products = OrderProducts::where('set_product_id', $set_product_id)
                                       ->where('order_sales_id', $order_sales_id)->first();
        if(!$order_products) {
            throw new \Exception('Quantidade do faturamento não atualizada, OrderProducts não encontrado');
        } else {

            if($order_products->quantity_invoice < $quantity || $order_products->quantity_invoice == 0)
                throw new \Exception('Quantidade de devolução não atualizada, quantidade de devolução é maior que a faturada');    
			
			$order_products->quantity_invoice_refund += $quantity;
            if($order_products->save())
                $this->updateOrderSaleInvoice($order_sales_id);
        }
    }
	
	private function updateOrderSaleInvoice($id) {

        $order_products = OrderProducts::where('order_sales_id', $id);
        $qtd_order = $order_products->sum('quantity');
        $qtd_invoice = $order_products->sum('quantity_invoice');

        if($qtd_order > $qtd_invoice) {
            $order_sale = OrderSales::find($id);
            $order_sale->is_invoice = 0;
            $order_sale->save();
        }
    }

    private function uploadArchive($nNF, $serie, $dhEmi) {

        $desc = 'NFE-devolucao-'.$nNF.'-'.$serie.'-'.date('YmdHis', strtotime($dhEmi));

        $upload_xml = $this->uploadNfe($this->xml->simpleXml(), $desc, 'xml');
        if(!$upload_xml)
            throw new \Exception('Erro ao realizar o upload do XML!');
            
        $upload_pdf = $this->uploadNfe($this->pdf, $desc, 'pdf');
        if(!$upload_pdf) 
            throw new \Exception('Erro ao realizar o upload do PDF!');

        return [
            'nf_xml_url' => $upload_xml,
            'nf_pdf_url' => $upload_pdf
        ];
    }

    private function getValuesICMS($icms) {

        $type = array_key_first($icms);
        $array = $icms[$type];

        return [
            'vBC' => $array['vBC'],
            'vICMS' => $array['vICMS']
        ];
    }
}    

