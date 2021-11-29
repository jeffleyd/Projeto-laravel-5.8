<?php

namespace App\Imports;

use App\Model\JuridicalProcess;
use App\Model\JuridicalTypeAction;
use App\Model\JuridicalProcessHistoric;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Http\Request;

use Carbon\Carbon;

class ProcessImport implements ToCollection, WithChunkReading
{
    function __construct(Request $request) 
    {    
        $this->request = $request;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach($rows as $index => $col)
        {   
            $total_cols = $rows[0]->count();

            if($index != 0) {

                if(!empty($col[0]) and !empty($col[1])) {

                    $type_action = JuridicalTypeAction::whereRaw('LOWER(description) like ?', '%'.strtolower($col[2]).'%')->first();

                    if(!$type_action) {

                        $type_action = new JuridicalTypeAction;
                        $type_action->description = $col[2];
                        $type_action->status = 1;
                        $type_action->save();
                    }

                    if ($col[0] != '') {
                        
                        $process_number = explode("|", $col[0]);
                        $process_verify = JuridicalProcess::where('process_number', trim($process_number[0]))->first();

                        if(!$process_verify) {

                            $process = new JuridicalProcess;

                            $process->process_number = trim($process_number[0]);
                            $process->process_number_2 = array_key_exists(1, $process_number) ? trim($process_number[1]) : null;
                            $process->lawyer_r_code = $this->request->session()->get('r_code');
                            $process->law_firm_id = $this->request->law_firm;
                            $process->type_applicant = 2;
                            $process->name_applicant = $col[1];
                            $process->type_required = 2;
                            $process->identity_required = "03.519.135/0001-56";
                            $process->name_required = "GREE ELECTRIC APPLIANCES DO BRASIL LTDA";
                            $process->type_process = $this->request->type_process;
                            $process->type_action_id = $type_action->id;
                            $process->value_cause = $col[3];

                            if($col[4] != '') {
                                $UNIX_DATE = ($col[4] - 25569) * 86400;
                                $date_judgment = gmdate("Y-m-d", $UNIX_DATE);
                                $process->date_judgment = $date_judgment;
                            }

                            $process->judicial_forum = '';
                            $process->judicial_court = '';
                            $process->district_court = $col[5];
                            $process->state_court = '';
                            $process->measures_plea = $col[8] ? $col[8] : '';

                            if($process->save()) {

                                if ($col[6] != '') {
                                    $this->saveProcessHistoric($process->id, $col[6]);
                                }
                            } else {
                                DB::rollBack();
                                throw new \Exception('Erro ao salvar o processo!');
                            }
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

    protected function saveProcessHistoric($process_id, $description) {

        $historic = new JuridicalProcessHistoric;
        $historic->juridical_process_id = $process_id;
        $historic->lawyer_r_code = $this->request->session()->get('r_code');
        $historic->title = '';
        $historic->description = $description;

        if ($historic->save()) {
            return true;
        }else {
            throw new \Exception('Erro ao salvar andamento do processo!');
        }
    }    
}