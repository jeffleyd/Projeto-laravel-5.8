<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \App\Model\Users;

class Client extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'client';
    protected $connection = 'commercial';
    protected $hidden = ['pivot'];

    protected $appends = [
        'group',
        'type_client_name',
        'tax_regime_name'
    ];

    protected $fillable = [
        'code', 'request_salesman_id', 'type_people', 'company_name', 'fantasy_name', 'client_group_id', 'identity', 'state_registration', 'municipal_registration',
        'is_matriz', 'address', 'district', 'city', 'state', 'zipcode', 'code_description_ativity', 'suframa_registration', 'especial_regime_icms_per_st',
        'tax_regime', 'social_capital', 'nire_number', 'type_client', 'billing_location_type_people', 'billing_location_identity',  'billing_location_state_registration',
        'billing_location_address', 'billing_location_city_state', 'delivery_location_type_people', 'delivery_location_identity', 'delivery_location_state_registration',
        'delivery_location_address', 'delivery_location_city_state', 'title_name_reason_social', 'title_name_reason_social_identity',
        'quantity_filial_cds', 'billing_last_years', 'units_air_sold_last_years', 'billing_air_last_years', 'purchase_volume', 'works_import', 'vpc', 'financy_status', 'financy_credit', 'commission', 'buy_intention'
    ];

    public function manager_region() {
        return $this->belongsToMany(Salesman::class, 'salesman_on_state', 'state', 'salesman_id', 'state');
    }

    public function salesman() {
        return $this->belongsTo(Salesman::class, 'request_salesman_id', 'id');
    }
	
	public function client_managers()
    {
        return $this->hasMany(ClientManagers::class, 'client_id', 'id');
    }

    public function client_peoples_contact()
    {
        return $this->hasMany(ClientPeoplesContact::class, 'client_id', 'id');
    }

    public function client_account_bank()
    {
        return $this->hasMany(ClientAccountBank::class, 'client_id', 'id');
    }

    public function client_main_suppliers()
    {
        return $this->hasMany(ClientMainSuppliers::class, 'client_id', 'id');
    }

    public function client_main_clients()
    {
        return $this->hasMany(ClientMainClients::class, 'client_id', 'id');
    }

    public function client_group()
    {
        return $this->belongsToMany(ClientGroup::class, 'client_on_group', 'client_id', 'client_group_id');
    }

    public function client_on_group()
    {
        return $this->hasOne(ClientOnGroup::class,'client_id', 'id');
    }

    public function getTypeClientNameAttribute()
    {
        $types = [
            1 => 'Varejo Regional',
            2 => 'Varejo Regional (Abertura)',
            3 => 'Especializado Regional',
            4 => 'Especializado Nacional',
            5 => 'Refrigerista Nacional',
            6 => 'Varejo Nacional',
            7 => 'E-commerce',
            8 => 'VIP',
			9 => 'Colaborador / Parceiro',
        ];
        return $types[$this->type_client];
    }

    public function getTaxRegimeNameAttribute()
    {
        $types = [
            1 => 'Lucro real',
            2 => 'Lucro Presumido',
            3 => 'Simples',
			4 => 'Pessoa física',
        ];
        return $types[$this->tax_regime];
    }

    public function getGroupAttribute()
    {
        return $this->client_group->first() ? $this->client_group->first() : (object)['id' => 0, 'name' => '', 'code' => ''];
    }

    public function client_on_product_sales()
    {
        return $this->hasMany(ClientOnProductSales::class, 'client_id', 'id');
    }

    public function client_owner_and_partner()
    {
        return $this->hasMany(ClientOwnerAndPartner::class, 'client_id', 'id');
    }

    public function client_version()
    {
        return $this->hasMany(ClientVersion::class, 'client_id', 'id');
    }

    public function client_documents()
    {
        return $this->hasOne(ClientDocuments::class, 'client_id', 'id');
    }

    public function client_imdt_analyze()
    {
        return $this->hasMany(ClientImdtAnalyze::class, 'client_id', 'id');
    }

	public function client_revision_analyze()
    {
        return $this->hasOne(ClientRevisionAnalyze::class, 'client_id', 'id');
    }
	
	public function client_judicial_analyze()
    {
        return $this->hasOne(ClientJudicialAnalyze::class, 'client_id', 'id');
    }
	
    public function client_commercial_analyze()
    {
        return $this->hasOne(ClientCommercialAnalyze::class, 'client_id', 'id');
    }

    public function client_financy_analyze()
    {
        return $this->hasOne(ClientFinancyAnalyze::class, 'client_id', 'id');
    }

    public function scopeClientVersionStatus($query, $type) {

        return $query->whereHas('client_version', function($q) use ($type) {
            if ($type == 1)
                $q->where('version', '>', 1);
            else
                $q->where('version', '=', 1);
        });
    }

    public function scopeGroupFilter($query, $value) {

        return $query->whereHas('client_on_group', function($q) use ($value) {
            $q->where('client_group_id', $value);
        });
    }

    public function clientHeadquarters()
    {
        return $this->belongsToMany(Client::class, 'client_on_client', 'filial_id', 'matriz_id');
    }

    public function clientSubsidiary()
    {
        return $this->belongsToMany(Client::class, 'client_on_client', 'matriz_id', 'filial_id');
    }

    public function scopeShowOnlyManager($query, $id) {
        return $query->whereHas('client_managers', function ($sb2) use ($id) {
            $sb2->where('salesman_id', $id);
        })->orWhere('request_salesman_id', $id);
    }

    // VALID PROCESS IMDT
    public function client_imdt_analyze_one()
    {
        return $this->hasOne(ClientImdtAnalyze::class, 'client_id', 'id')->orderBy('id', 'DESC');
    }

    public function scopeValidProcessImdt($query, $request) {

        return $query->whereHas('client_imdt_analyze_one', function($q) use ($request) {
            $q->ValidProcessImdt($request)->where('version', function($nq){
                $nq->select(DB::raw('MAX(client_version.version) FROM client_version WHERE client_version.client_id = client.id'));
            });

        })->orWhere(function($q) use ($request) {
            $q->whereHas('salesman', function($q) use ($request) {
                $q->ValidProcessImdt($request);

            })->whereDoesntHave('client_imdt_analyze_one', function($query) {
                $query->where('version', function($nq){
                    $nq->select(DB::raw('MAX(client_version.version) FROM client_version WHERE client_version.client_id = client.id'));
                });
            });
        });
    }

}
