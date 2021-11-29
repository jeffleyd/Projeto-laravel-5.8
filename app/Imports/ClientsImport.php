<?php

namespace App\Imports;

use App\Model\Commercial\Client;
use App\Model\Commercial\ClientOnProductSales;
use App\Model\Commercial\ClientPeoplesContact;
use App\Model\Commercial\ClientOwnerAndPartner;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use Illuminate\Http\Request;

use Carbon\Carbon;

class ClientsImport implements ToCollection, WithChunkReading 
{

    function __construct(Request $request) 
    {    
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        $this->validationData($rows);

        foreach($rows as $index => $col)
        {   
            
            if($index != 0) {

                if($this->validateCollumn($col)) {    

                    $identity_verify = Client::where('identity', $col[4])->first();

                    if(!$identity_verify) {

                        try {

                            $client = new Client;
                            $client->code = $col[0];
                            $client->type_people = $this->getTypePeople($col[1]); 
                            $client->company_name = $col[2];
                            $client->fantasy_name = $col[3];
                            $client->identity = $col[4];
                            $client->state_registration = $col[5];
                            $client->municipal_registration = $col[6];
                            $client->vpc = $this->getVPC($col[7]);
                            $client->is_matriz = $this->getMatriz($col[8]);
                            $client->address = $col[9];
                            $client->district = $col[10];
                            $client->state = trim($col[11]);
                            $client->city = $col[12];
                            $client->zipcode = $col[13];
                            $client->code_description_ativity = $col[14];
                            $client->suframa_registration = $col[15];
                            $client->tax_regime = $this->getTaxRegime($col[16]);
                            $client->especial_regime_icms_per_st = $col[17];
                            $client->social_capital = $col[18];
                            $client->nire_number = $col[19];
                            $client->type_client = $this->getTypeClient($col[20]); 
                            $client->billing_location_identity = $col[22];
                            $client->billing_location_state_registration = $col[23];
                            $client->billing_location_address = $col[24];
                            $client->billing_location_city_state = $col[25];
                            $client->delivery_location_identity = $col[26];
                            $client->delivery_location_state_registration = $col[27];
                            $client->delivery_location_address = $col[28];
                            $client->delivery_location_city_state = $col[29];
                            $client->quantity_filial_cds = $col[44];
                            $client->units_air_sold_last_years = $col[45];
                            $client->works_import = $this->getWorksImport($col[46]);
                            $client->save();

                            $product_id =  empty($col[21]) ? 1 : $this->getProductSales($col[21]); 
                            $this->saveProductSales($client->id, $product_id);
                            $this->saveContactPeople($client->id, $col[30], $col[31], $col[32], $col[33], 1);
                            $this->saveContactPeople($client->id, $col[34], $col[35], $col[36], $col[37], 2);
                            $this->saveContactPeople($client->id, $col[38], $col[39], $col[40], $col[41], 3);
                            $this->saveOwnerPartner($client->id, $col[42], $col[43]);

                        } catch (\Exception $e) {
                            throw new \Exception($e->getMessage());
                        }    
                    }
                }
            }
        }
        DB::commit();
    } 

    public function chunkSize(): int
    {
        return 1000;
    }

    protected function saveProductSales($client_id, $product_id) {

        try {
            $product = new ClientOnProductSales;
            $product->client_id = $client_id;
            $product->product_sales_id = $product_id;
            $product->save();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    protected function saveContactPeople($client_id, $name, $office, $email, $phone, $type) {

        try {
            $contact = new ClientPeoplesContact;
            $contact->client_id = $client_id;
            $contact->name = $name;
            $contact->office = $office;
            $contact->email = $email;
            $contact->phone = $phone;
            $contact->type_contact = $type;
            $contact->save();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        } 
    }

    protected function saveOwnerPartner($client_id, $name, $identity) {

        try {
            $owner = new ClientOwnerAndPartner;
            $owner->client_id = $client_id;
            $owner->name = $name;
            $owner->identity = $identity;
            $owner->save();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function validationData($rows) {   

        foreach($rows as $index => $col) {
            
            if($index != 0) {

                if($this->validateCollumn($col)) {

                    for ($i=0; $i < 47; $i++) { 

                        if(empty($col[$i])) {

                            throw new \Exception('COLUNA: '. $rows[0][$i] .' NA LINHA: '.$index.' ESTÁ VAZIA!');
                        }
                    }
                }    
            }    
        }    
    }

    private function validateCollumn($col) {

        if(empty($col[49]) && 
           empty($col[50]) && 
           empty($col[51]) && 
           empty($col[52]) && 
           empty($col[53]) && 
           empty($col[54])) 
        {
            return true;
        } else {
            return false;
        }
        
    }

    private function getTypePeople($type) {

        return [
            'Jurídico' => 1,
            'Funcionário' => 2,
            'Pessoa Física' => 3
        ][$type];
    }

    private function getVPC($val) {
        return [
            'Líquido' => 1,
            'Bruto' => 2,
        ][$val];
    }

    private function getMatriz($val) {
        return [
            'Matriz' => 1,
            'Filial' => 0,
        ][$val];
    }

    private function getTypeClient($type) {
        return [
            'Varejo Regional' => 1,
            'Varejo Regional (Abertura)' => 2,
            'Especializado Regional' => 3,
            'Especializado Nacional' => 4,
            'E-commerce' => 7,
            'Refrigerista Nacional' => 5,
            'Varejo Nacional' => 6,
            'VIP' => 8,
        ][$type];
    }

    private function getWorksImport($val) {
        return [
            'SIM' => 1,
            'NÃO' => 0,
        ][$val];
    }

    private function getTaxRegime($val) {
        return [
            'Lucro Real' => 1,
            'Presumido' => 2,
            'Simples' => 3
        ][$val];
    }

    private function getProductSales($val) {

        return [
            'Ar condicionado (doméstico)' => 1,
            'Eletrodoméstico' => 2,
            'Maquina Chiller' => 3,
            'Não é revenda' => 4,
            'VRF' => 5,
            'Outro' => 6
        ][$val];
    }    
}