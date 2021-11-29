<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalProcess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_process';

    protected $fillable = [
        'process_number', 'process_number_execution', 'is_execution', 'lawyer_r_code', 'law_firm_id', 'costumer_id', 'type_applicant', 
        'identity_applicant',  'name_applicant', 'type_required', 'identity_required', 'name_required', 'worker_r_code',
        'type_process', 'type_action_id', 'value_cause', 'judicial_forum', 'judicial_court', 'district_court', 'state_court', 
        'measures_plea', 'date_received', 'date_judgment', 'status', 'sector_related', 'type_sector_related', 'code_sector_related'
    ];    

    public function sac_client() {
        return $this->belongsTo(SacClient::class, 'costumer_id', 'id');
    }

    public function juridical_type_action() {
        return $this->hasOne(JuridicalTypeAction::class, 'id', 'type_action_id');
    }

    public function juridical_law_firm() {
        return $this->belongsTo(JuridicalLawFirm::class, 'law_firm_id', 'id');
    }

    public function juridical_process_historic() {
        return $this->hasMany(JuridicalProcessHistoric::class, 'juridical_process_id', 'id');
    }

    public function juridical_process_cost() {
        return $this->hasMany(JuridicalProcessCost::class, 'juridical_process_id', 'id');
    }

    public function users() {
        return $this->belongsTo(Users::class, 'lawyer_r_code', 'r_code');
    }
}
