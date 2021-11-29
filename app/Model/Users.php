<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $appends = [
        'full_name',
        'short_name',
		'sector_name',
    ];

    protected $hidden = [
        'password',
        'token',
        'token_mobile',
        'otpauth',
    ];
	
	public function chatContactSend() {
        return $this->hasMany(ChatMessage::class, 's_r_code', \Session::get('r_code'))->orderBy('id', 'DESC');
    }

    public function chatContactReceiver() {
        return $this->hasMany(ChatMessage::class, 'r_r_code', \Session::get('r_code'))->orderBy('id', 'DESC');
    }

    public function chatContactAll() {
        return $this->chatContactSend->merge($this->chatContactReceiver);
    }
	
	public function immediates()
    {
        return $this->belongsToMany(Users::class, 'user_immediate', 'user_r_code', 'immediate_r_code', 'r_code', 'r_code');
    }
	
	public function subordinates()
    {
        return $this->belongsToMany(Users::class, 'user_immediate', 'immediate_r_code', 'user_r_code', 'r_code', 'r_code');
    }

    public function permissoes_usuario()
    {
        return $this->hasMany(UserOnPermissions::class, 'user_r_code', 'r_code');
    }

    public function financy()
    {
        return $this->belongsTo(UserFinancy::class, 'r_code', 'r_code');
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
	
	function getSectorNameAttribute() {
        $sector = config('gree.sector');
        return isset($sector[$this->sector_id]) ? $sector[$this->sector_id] : 'Não definido';
    }

    public function scopeIsActive($query, $bool=true)
    {
        return $query->where('is_active',$bool);
    }
}
