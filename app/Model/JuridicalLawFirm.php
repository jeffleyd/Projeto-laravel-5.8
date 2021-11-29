<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalLawFirm extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_law_firm';

    public function juridical_law_firm_contacts()
    {
        return $this->hasMany(JuridicalLawFirmContacts::class, 'juridical_law_firm_id', 'id');
    }

    public function juridical_process()
    {
        return $this->hasMany(JuridicalProcess::class, 'law_firm_id', 'id');
    }

    public function juridical_law_firm_cost() 
    {
        return $this->hasMany(JuridicalLawFirmCost::class, 'juridical_law_firm_id', 'id');
    }

    public function juridical_law_firm_account()
    {
        return $this->hasMany(JuridicalLawFirmAccount::class, 'juridical_law_firm_id', 'id');
    }
}
