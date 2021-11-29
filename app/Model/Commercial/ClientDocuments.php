<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class ClientDocuments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client_documents';
    protected $connection = 'commercial';
    protected $hidden = ['updated_at'];

    protected $fillable = [
        'contract_social',
        'contract_social_is_exception',
        'balance_equity_dre_flow',
        'balance_equity_dre_flow_is_exception',
        'declaration_regime',
        'declaration_regime_is_exception',
        'card_cnpj',
        'card_cnpj_is_exception',
        'card_ie',
        'card_ie_is_exception',
        'apresentation_commercial',
        'apresentation_commercial_is_exception',
        'proxy_representation_legal',
        'proxy_representation_legal_is_exception',
		'certificate_debt_negative_federal',
        'certificate_debt_negative_federal_is_exception',
        'certificate_debt_negative_sefaz',
        'certificate_debt_negative_sefaz_is_exception',
        'certificate_debt_negative_labor',
        'certificate_debt_negative_labor_is_exception',
		'balance_equity_dre_flow_2_year',
        'balance_equity_dre_flow_2_year_is_exception',
        'balance_equity_dre_flow_3_year',
        'balance_equity_dre_flow_3_year_is_exception'
    ];

    public function contractSocial()
    {
        return $this->hasMany(ClientOnContractSocial::class, 'client_id', 'client_id');
    }

    public function balanceEquity()
    {
        return $this->hasMany(ClientOnBalanceEquityDreFlow::class, 'client_id', 'client_id');
    }
	
	public function balanceEquity2Year()
    {
        return $this->hasMany(ClientOnBalanceEquityDreFlow2Year::class, 'client_id', 'client_id');
    }

    public function balanceEquity3Year()
    {
        return $this->hasMany(ClientOnBalanceEquityDreFlow3Year::class, 'client_id', 'client_id');
    }
}
