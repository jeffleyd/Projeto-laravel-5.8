<?php

namespace App\Helpers\Commercial\Orders;

use App\Http\Controllers\Services\CommercialInvoiceTrait;
use App\Model\Commercial\OrderInvoice;
use App\Model\Commercial\OrderInvoiceErrors;
use App\Model\Commercial\OrderInvoiceProducts;
use App\Model\Commercial\OrderSales;
use App\Model\Commercial\SetProduct;
use App\Model\Commercial\SetProductOnGroup;
use App\Model\Commercial\OrderProducts;
use App\Model\ProductAir;

class OrdersInvoice
{
    use CommercialInvoiceTrait;

    private $xml = null;
    private $simple_xml = null;
    private $order_sales = null;
    private $countErrors = 0;
    private $order_sale_id = null;
    private $verifyOrder = false;
    private $verifyRepeatOrder = [];
    private $order_invoice_id = null;
    private $nfe_number = null;
    private $nfe_key = null;

    public function __construct($xml) {
        $this->xml = $xml->toArray();
        $this->simple_xml = $xml->simpleXml();
    }

    public function saveInvoice() {

        try {

            $nfe = $this->xml['NFe']['infNFe'];
            $infNfe = $this->xml['protNFe']['infProt'];
            $this->nfe_number = $nfe['ide']['nNF'];
            $this->nfe_key = $infNfe['chNFe'];

            $order_invoice = OrderInvoice::where('nf_number', $nfe['ide']['nNF'])->first();
            if(!$order_invoice) {

                $orderSaleId = $this->getOrderSaleId($nfe['infAdic']['infCpl']);
                if($orderSaleId) {

                    $invoice = new OrderInvoice;
                    $invoice->order_sales_id = $orderSaleId;
                    $invoice->nf_serie = $nfe['ide']['serie'];
                    $invoice->nf_number = $nfe['ide']['nNF'];
                    $invoice->nf_key = $infNfe['chNFe'];
                    $invoice->nf_total = $nfe['total']['ICMSTot']['vNF'];
                    $invoice->contract_vpc = $this->order_sales->contract_vpc;
                    $invoice->nf_icms_total = $nfe['total']['ICMSTot']['vICMS'];
                    $invoice->nf_pis_total = $nfe['total']['ICMSTot']['vPIS'];
                    $invoice->nf_cofins_total = $nfe['total']['ICMSTot']['vCOFINS'];
                    $invoice->type_payment_vpc = $this->order_sales->client_vpc;
                    $invoice->date_emission = date('Y-m-d', strtotime($nfe['ide']['dhEmi']));
                    $archive = $this->uploadArchive($nfe['ide']['nNF'], $nfe['ide']['serie'], $nfe['ide']['dhEmi']);
                    $invoice->nf_xml_url = $archive['nf_xml_url'];
                    $invoice->nf_pdf_url = $archive['nf_pdf_url'];
                    $invoice->vpc_total_paid = calculateVPC(
                        $invoice->type_payment_vpc,
                        $invoice->contract_vpc,
                        $invoice->nf_total,
                        $invoice->nf_icms_total,
                        $invoice->nf_pis_total,
                        $invoice->nf_cofins_total
                    )['total_vpc'];

                    if($invoice->save()){
                        $this->order_invoice_id = $invoice->id;
                        $this->invoiceProducts($nfe['det'], $invoice);
                    }
                }
            } 
        } catch (\Exception $e) {

            $invoice = OrderInvoice::find($this->order_invoice_id);
            if($invoice) {
                $invoice->delete();
                OrderInvoiceProducts::where('order_invoice_id', $this->order_invoice_id)->delete();
            }
            if($e->getMessage())
                $this->setErrorInvoice($this->setMsg(1).$e->getMessage(), $this->nfe_number, $this->nfe_key, 1);
        }
    }

    private function invoiceProducts($nfe_det, $new_invoice) {

        foreach($nfe_det as $product) {

            $product_id = $this->getProductAirId($product['prod']['cProd']);
            $new_product = new OrderInvoiceProducts;
            $new_product->order_invoice_id = $new_invoice->id;
            $new_product->description_product = $product['prod']['xProd'];
            $new_product->product_id = $product_id;
            $new_product->quantity = $product['prod']['qCom'];
            $new_product->price_unit = $product['prod']['vUnCom'];
            $new_product->price_total = $product['prod']['vProd'];
            $values_icms = $this->getValuesICMS($product['imposto']['ICMS']);
            $new_product->price_base_icms = $values_icms['vBC'];
            $new_product->price_icms = $values_icms['vICMS'];
            $new_product->price_pis = $product['imposto']['PIS']['PISAliq']['vPIS'];
            $new_product->price_confins = $product['imposto']['COFINS']['COFINSAliq']['vCOFINS'];
            $new_product->total_vpc = calculateVPC(
                $new_invoice->type_payment_vpc,
                $new_invoice->contract_vpc,
                $product['prod']['vProd'],
                $values_icms['vICMS'],
                $product['imposto']['PIS']['PISAliq']['vPIS'],
                $product['imposto']['COFINS']['COFINSAliq']['vCOFINS']
            )['total_vpc'];
            
            if($new_product->save()) {

                $set_product = $this->getSetProduct($product_id);
                if($set_product['set_product_id']) {

                    $product_group_id = $this->getProductGroup($set_product['set_product_id']);
                    $new_product->set_product_id = $set_product['set_product_id'];
                    $new_product->set_product_group_id = $product_group_id;
                    $new_product->btus = $set_product['btus'];
                    $new_product->save();

                    if(!in_array($set_product['set_product_id'], $this->verifyRepeatOrder)) {
                        array_push($this->verifyRepeatOrder, $set_product['set_product_id']);
                        $this->updateQuantityInvoice($set_product['set_product_id'], $new_invoice->order_sales_id, $new_product->quantity, $new_invoice->id);
                    }
                }
            }
        }
    }

    private function getOrderSaleId($info_comp) {

        OrderSales::select('id', 'code', 'contract_vpc', 'client_vpc')->where('is_invoice', 0)
            ->chunk(100, function ($order_all) use ($info_comp) {

            foreach($order_all->pluck('code')->toArray() as $code) {
                if(stristr($info_comp, $code)) {
                    $order = $order_all->where('code', $code)->first();
                    $this->order_sale_id = $order->id;
                    $this->order_sales = $order->load('orderProducts');
                    $this->verifyOrder = true;
                } else {
                    $this->countErrors++;
                }
            }
        });

        if($this->verifyOrder == false && $this->countErrors > 0) {
            $this->setErrorInvoice($this->setMsg(2).$info_comp.'', $this->nfe_number, $this->nfe_key, 1);
            throw new \Exception(false);
        }
        $this->countErrors = 0;
        return $this->order_sale_id;
    }

    private function getProductAirId($code) {

		$product = ProductAir::whereRaw('FIND_IN_SET("'.$code.'", sales_code)')->get();
        if (!$product->first()) {  
            $this->setErrorInvoice($this->setMsg(3).$code, $this->nfe_number, $this->nfe_key, 1);
            throw new \Exception(false);
        } 
        elseif ($product->count() > 1) {
            $this->setErrorInvoice($this->setMsg(8).$code, $this->nfe_number, $this->nfe_key, 1);
            throw new \Exception(false);
        } 
        else {
            return $product->first()->id;
        }
    }

    private function getSetProduct($product_id) {

        $set_product = SetProduct::where('evap_product_id', $product_id)
                                 ->orWhere('cond_product_id', $product_id)
                                 ->withTrashed()
                                 ->first();

        if(!$set_product) {
            $this->setErrorInvoice(
                $this->setMsg(4).$product_id, 
                $this->nfe_number, 
                $this->nfe_key, 1
            );
            throw new \Exception(false);
        } else {
            return [
                'set_product_id' => $set_product->id,
                'btus' => $set_product->btus
            ];
        }
    }

    private function getProductGroup($set_product_id) {
        $group = SetProductOnGroup::where('set_product_id', $set_product_id)->first();
        if(!$group) {
            $this->setErrorInvoice(
                $this->setMsg(5).$set_product_id, 
                $this->nfe_number, 
                $this->nfe_key, 1
            );
            throw new \Exception(false);   
        } else {
            return $group->set_product_group_id;
        }
    }

    private function updateQuantityInvoice($set_product_id, $order_sales_id, $quantity, $invoice_id) {

        $order_products = OrderProducts::where('set_product_id', $set_product_id)
                                       ->where('order_sales_id', $order_sales_id)->first();
        if(!$order_products) {
            $this->setErrorInvoice(
                $this->setMsg(6). $set_product_id.' pedido id:'.$order_sales_id, 
                $this->nfe_number, 
                $this->nfe_key, 1
            );
            throw new \Exception(false);
        } else {
            $order_products->quantity_invoice = $this->verifyQuantityInvoice($order_products->quantity,$order_products->quantity_invoice,$quantity,$invoice_id,$set_product_id,$order_sales_id);
            if($order_products->save()) {
                $this->updateOrderSaleInvoice($order_sales_id);
            }
        }
    }

    private function updateOrderSaleInvoice($id) {

        $order_products = OrderProducts::where('order_sales_id', $id);
        $qtd_order = $order_products->sum('quantity');
        $qtd_invoice = $order_products->sum('quantity_invoice');

        if($qtd_order == $qtd_invoice) {
            $order_sale = OrderSales::find($id);
            $order_sale->is_invoice = 1;
            $order_sale->save();
        }
    }

    private function verifyQuantityInvoice($quantity, $quantity_invoice, $new_quantity, $invoice_id,$set_product_id, $order_sales_id) {
 
        $val = $quantity_invoice + $new_quantity;
        if($quantity < $new_quantity || $quantity < $val) {
            $this->setErrorInvoice(
                $this->setMsg(7).$invoice_id.' Conjunto produto id:'. $set_product_id.' pedido id:'. $order_sales_id.'',
                $this->nfe_number,
                $this->nfe_key, 1
            );
            return $quantity_invoice;
        } else {
            return $val;
        }
    }

    private function uploadArchive($nNF, $serie, $dhEmi) {

        $desc = 'NFE-devolucao-'.$nNF.'-'.$serie.'-'.date('YmdHis', strtotime($dhEmi));

        $upload_xml = $this->uploadNfe($this->simple_xml, $desc, 'xml');
        if(!$upload_xml) {
            $this->setErrorInvoice('Erro a fazer upload do XML: '.$desc.'', $this->nfe_number, $this->nfe_key, 1);
            throw new \Exception(false);
        }    
        
        $converted_pdf = $this->getConvertPDF($this->simple_xml);
        if(!$converted_pdf) {
            $this->setErrorInvoice('Erro ao converter Nota para PDF: '.$desc.'', $this->nfe_number, $this->nfe_key, 1);
            throw new \Exception(false);
        }

        $upload_pdf = $this->uploadNfe($converted_pdf, $desc, 'pdf');
        if(!$upload_pdf) {
            $this->setErrorInvoice('Erro a fazer upload do PDF: '.$desc.'', $this->nfe_number, $this->nfe_key, 1);
            throw new \Exception(false);
        }    

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

    private function setMsg($type) {
        return [
            1 => 'Erro ao salvar pedido: ',
            2 => 'Pedido não encontrado no sistema, verifique o código do pedido: ',
            3 => 'Código de venda não encontrado: ',
            4 => 'Conjunto não encontrado por id de produto: ',
            5 => 'Grupo não encontrado por id de conjunto: ',
            6 => 'Quantidade do faturamento não atualizada, OrderProducts não encontrado: conjunto produto id:',
            7 => 'Quantidade do faturamento não atualizada, qtd. de atualização é maior que a existente : Faturamento id: '
        ][$type];
    }
}    