<?php

namespace App\Exports;

use App\Model\SacExpeditionRequest;
use Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

use App\Model\Commercial\Client;

class ClientsExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct($request, $type = null)
    {
        $this->request = $request;
        $this->year = $request->year;
        $this->subordinates = $request->subordinates;
        $this->client_group = $request->client_group;
        $this->status = $request->status;
        $this->type = $type;
		$this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
    }
    
    public function query()
    {
        $clients = Client::with('client_group', 
                                'client_peoples_contact', 
                                'client_on_product_sales',
                                'client_on_group',
                                'client_owner_and_partner')->orderBy('id', 'DESC');
		
        if(empty($this->client_group)) {
			if(!empty($this->start_date)){
				$clients->whereDate('created_at', '>=', $this->start_date);
			}
			if(!empty($this->end_date)){
				$clients->whereDate('created_at', '<=', $this->end_date);
			}
		}	
        if($this->type == 1) {
            $clients->ShowOnlyManager($this->request->session()->get('salesman_data')->id);
        }
        if (!empty($this->year)) {
            $clients->whereYear('created_at', $this->year);
        }
        if(!empty($this->subordinates)) {
            $clients->where('request_salesman_id', $this->subordinates);
        }
        if(!empty($this->client_group)) {
            $value = $this->client_group;
            $clients->whereHas('client_on_group', function ($q) use ($value) {
                $q->where('client_group_id', $value);
            });
        }
        if(!empty($this->status)) {

            $value_filter = $this->status;

            if ($value_filter == 1) {
                $clients->where('is_active', 1);
            } elseif ($value_filter == 2) {
                $clients->where('is_active', 0);
            } elseif ($value_filter == 3) {
                $clients->where('salesman_imdt_reprov', 1)
                       ->orWhere('revision_is_reprov', 1)
                       ->orWhere('judicial_is_reprov', 1)
                       ->orWhere('commercial_is_reprov', 1)
                       ->orWhere('financy_reprov', 1);
            } elseif ($value_filter == 4 || $value_filter == 5 || $value_filter == 6) {
                $clients->where('salesman_imdt_approv', 1)
                       ->where('revision_is_approv', 1)
                       ->where('judicial_is_approv', 1)
                       ->where('commercial_is_approv', 1)
                       ->where('financy_approv', 1);

                if($value_filter == 4)
                    $clients->where('financy_status', 1);
                elseif($value_filter == 5)    
                    $clients->where('financy_status', 2);
                elseif($value_filter == 6)        
                    $clients->where('financy_status', 3);
            }    
            elseif($value_filter == 7 || $value_filter == 8 || $value_filter == 9) {

                $clients->where('has_analyze', 0);

                if($value_filter == 7)
                    $clients->where('financy_status', 1);
                elseif($value_filter == 8)    
                    $clients->where('financy_status', 2);
                elseif($value_filter == 9)        
                    $clients->where('financy_status', 3);
            }
            elseif($value_filter == 10) {
                $clients->where('has_analyze', 1);
            }
        }
        return $clients;
    }   
    
    public function map($clients): array
    {       

        $status = '';
        
        if ($clients->is_active == 0) {
            $status .= 'Desativado';
        } elseif ($clients->salesman_imdt_reprov == 1 or $clients->revision_is_reprov == 1 or $clients->judicial_is_reprov == 1 or $clients->commercial_is_reprov == 1 or $clients->financy_reprov == 1) {
            $status .= 'Reprovado';
        } elseif ($clients->salesman_imdt_approv == 1 and $clients->revision_is_approv == 1 and $clients->judicial_is_approv == 1 and $clients->commercial_is_approv == 1 and $clients->financy_approv == 1) {
            $status .= 'Aprovado / ';
            if ($clients->financy_status == 1) {
                $status .= 'Reprovado pelo financeiro';
            } elseif ($clients->financy_status == 2) {
                $status .= 'Liberado antecipado';
            } elseif ($clients->financy_status == 3) {
                $status = 'Liberado antecipado & parcelado';
            }
        } elseif($clients->has_analyze == 0) {
            $status .= 'Cadastrado / ';
            if ($clients->financy_status == 1) {
                $status .= 'Reprovado pelo financeiro';
            } elseif ($clients->financy_status == 2) {
                $status = 'Liberado antecipado';
            } elseif ($clients->financy_status == 3) {
                $status .= 'Liberado antecipado & parcelado';
            }
        } else {
            $status .= 'Em análise';
        }
        
        return [
            [
                $clients->code,
                $this->getTypePeople($clients->type_people),
                $clients->company_name,
                $clients->fantasy_name,
                $clients->identity,
                $clients->state_registration,
                $clients->municipal_registration,
                $this->getVPC($clients->vpc),
                $this->getMatriz($clients->is_matriz),
                $clients->address,
                $clients->district,
                $clients->state,
                $clients->city,
                $clients->zipcode,
                $clients->code_description_ativity,
                $clients->suframa_registration,
                $clients->tax_regime_name,
                $clients->especial_regime_icms_per_st,
                number_format($clients->social_capital, 2, ',', '.'),
                $clients->nire_number,
                $clients->type_client_name,
                $this->getProductSales($clients->client_on_product_sales->pluck('product_sales_id')->toArray()),
                $clients->billing_location_identity,
                $clients->billing_location_state_registration,
                $clients->billing_location_address,
                $clients->billing_location_city_state,
                $clients->delivery_location_identity,
                $clients->delivery_location_state_registration,
                $clients->delivery_location_address,
                $clients->delivery_location_city_state,
                $clients->client_peoples_contact->where('type_contact', 1)->first()->name,
                $clients->client_peoples_contact->where('type_contact', 1)->first()->office,
                $clients->client_peoples_contact->where('type_contact', 1)->first()->email,
                $clients->client_peoples_contact->where('type_contact', 1)->first()->phone,
                $clients->client_peoples_contact->where('type_contact', 2)->first()->name,
                $clients->client_peoples_contact->where('type_contact', 2)->first()->office,
                $clients->client_peoples_contact->where('type_contact', 2)->first()->email,
                $clients->client_peoples_contact->where('type_contact', 2)->first()->phone,
                $clients->client_peoples_contact->where('type_contact', 3)->first()->name,
                $clients->client_peoples_contact->where('type_contact', 3)->first()->office,
                $clients->client_peoples_contact->where('type_contact', 3)->first()->email,
                $clients->client_peoples_contact->where('type_contact', 3)->first()->phone,
                $clients->client_owner_and_partner->first()->name,
                $clients->client_owner_and_partner->first()->identity,
                $clients->quantity_filial_cds,
                $clients->units_air_sold_last_years,
                $this->getWorksImport($clients->works_import),
                $status,
				date('d/m/Y', strtotime($clients->created_at))
            ]   
        ];     
    }   
    
    public function headings(): array
    {
        return [
            'Código do cliente',
            'Tipo de pessoa',
            'Razão Social',
            'Nome Fantasia',
            'CNPJ / RG',
            'Inscrição Estadual',
            'Inscrição Municipal',
            'Pagamento VPC',
            'Estabelecimento',
            'Endereço',
            'Bairro',
            'Estado(UF)',
            'Cidade',
            'CEP',
            'Atividade econômica principal (CNAE)',
            'Inscrição SUFRAMA',
            'Regime de Tributação',
            'Regime especial ou ICMS por ST',
            'Social Capital',
            'Junta Com. (NIRE)',
            'Tipo de Cliente',
            'Produtos Vendidos',
            'Cobrança  - CNPJ /RG',
            'Cobrança - Inscrição Estadual',
            'Cobrança - Endereço',
            'Cobrança - Cidade / UF',
            'Entrega - CNPJ / RG',
            'Entrega - Inscrição Estadual',
            'Entrega - Endereço',
            'Entrega - Cidade / UF',
            'Contato Compras - Nome',
            'Contato Compras - Cargo',
            'Contato Compras - Email',
            'Contato Compras - Telefone',
            'Contato Financeiro - Nome',
            'Contato Financeiro - Cargo',
            'Contato Financeiro - Email',
            'Contato Financeiro - Telefone',
            'Contato Logística - Nome',
            'Contato Logística - Cargo',
            'Contato Logística - Email',
            'Contato Logística - Telefone',
            'Proprietário / Sócio',
            'Proprietário / Sócio - CPF / CNPJ',
            'Quantas filiais (loja e CD e sede) no Brasil?',
            'Quantas unidades de ar.cond foram vendidas nos últimos anos? *',
            'A empresa trabalha com IMPORTAÇÃO direta? *',
            'Status',
			'Data de Criação'
        ];
    }   
    
    private function getTypePeople($type) {

        return [
            1 => 'Jurídico',
            2 => 'Funcionário',
            3 => 'Pessoa Física'
        ][$type];
    }

    private function getVPC($val) {
        if($val != 0)  {
            return [
                1 => 'Líquido',
                2 => 'Bruto'
            ][$val];
        } else {
            return ' - ';
        }
    }

    private function getMatriz($val) {
        return [
            1 => 'Matriz',
            0 => 'Filial'
        ][$val];
    }

    private function getProductSales($arr) {

        $arr_products = [
            1 =>'Ar condicionado (doméstico)',
            2 =>'Eletrodoméstico',
            3 =>'Maquina Chiller',
            4 =>'Não é revenda',
            5 =>'VRF',
            6 =>'Outro'
        ];

        $products = '';
        foreach ($arr as $key) {
            $products .= $arr_products[$key].', ';
        }

        return $products;
    }

    private function getWorksImport($val) {
        return [
            1 => 'SIM',
            0 => 'NÃO'
        ][$val];
    }
}
