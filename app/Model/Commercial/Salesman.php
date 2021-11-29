<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;
use \App\Model\Users;

class Salesman extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'salesman';
    protected $connection = 'commercial';

    protected $appends = [
        'full_name',
        'short_name',
    ];

    protected $hidden = [
        'password',
        'otpauth',
    ];

    protected $fillable = [
        'company_name', 'region', 'type_people', 'first_name', 'last_name',
        'phone_1','phone_2','email', 'address', 'state', 'city','zipcode','is_active'
    ];

    public function immediate_boss()
    {
        return $this->belongsToMany(Salesman::class, 'salesman_immediate', 'salesman_id', 'immediate_id');
    }

    public function subordinates()
    {
        return $this->belongsToMany(Salesman::class, 'salesman_immediate', 'immediate_id', 'salesman_id');
    }

    public function getShortNameAttribute(){
        $words = explode(" ", $this->last_name);
        $lastname = "";

        $i = 0;
        $len = count($words);
        foreach ($words as $w) {

            if (strlen($w) > 2) {
                if ($i == $len - 1) {
                    $lastname .= $w[0];
                } else {
                    $lastname .= $w[0] .".";
                }
            } else {
                $i++;
                continue;
            }
            $i++;
        }

        return $this->first_name ." ". strtoupper($lastname);
    }

    function getFullNameAttribute() {
        return $this->first_name ." ". $this->last_name;
    }

    public function scopeIsActive($query, $bool=true)
    {
        return $query->where('is_active',$bool);
    }

    public function user() {
        return $this->setConnection('mysql')->belongsTo(Users::class, 'r_code', 'r_code');
    }

    public function immediate_boss_many()
    {
        return $this->hasMany(SalesmanImmediate::class, 'salesman_id', 'id');
    }

    public function scopeValidProcessImdt($query, $request) {

        return $query->whereHas('immediate_boss_many', function($q) use ($request) {
            $q->ValidProcessImdt($request);
        });
    }

    public function salesman_on_state()
    {
        return $this->hasMany(SalesmanOnState::class, 'salesman_id', 'id');
    }

}
