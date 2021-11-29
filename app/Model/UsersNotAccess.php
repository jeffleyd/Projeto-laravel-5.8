<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersNotAccess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_not_access';

    protected $appends = [
        'full_name',
        'short_name',
        'sector_name',
    ];

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
}
