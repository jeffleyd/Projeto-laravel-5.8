<?php


namespace App\Model\Commercial;
use Illuminate\Database\Eloquent\Model;

class Programation extends model
{

    protected $table = 'programation';
    protected $connection = 'commercial';
    protected $appends = [
      'months'
    ];

    public function client() {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function programationMacro() {
        return $this->hasMany(ProgramationMacro::class, 'programation_id', 'id');
    }

    public function programationVersion($version = "") {
        if (!$version) {
            return $this->hasOne(ProgramationVersion::class, 'programation_id', 'id')
                ->orderBy('id', 'DESC');
        } else {
            return $this->hasOne(ProgramationVersion::class, 'programation_id', 'id')
                ->where('version', $version)
                ->orderBy('id', 'DESC');
        }
    }

    public function programationVersionLast() {
        return $this->hasOne(ProgramationVersion::class, 'programation_id', 'id')
            ->where('is_approv', 1)
            ->orWhere(function($q){
                $q->where('is_approv', 0)->where('is_reprov', 0);
            })
            ->orderBy('id', 'DESC');
    }

    public function programationVersionAll() {
        return $this->hasMany(ProgramationVersion::class, 'programation_id', 'id')
            ->orderBy('id', 'ASC');
    }

    public function salesman() {
        return $this->hasOne(Salesman::class, 'id', 'request_salesman_id');
    }

    public function programationMonth($version = "") {
        if (!$version) {
            return $this->hasMany(ProgramationMonth::class, 'programation_id', 'id');
        } else {
            return $this->hasMany(ProgramationMonth::class, 'programation_id', 'id')
                ->where('version', $version);
        }
    }

    public function scopeShowOnlyManager($query, $manager) {

    return $query->whereHas('client', function($sb1) use ($manager) {
            $sb1->ShowOnlyManager($manager);
        });
    }

    public function getMonthsAttribute() {
        $ver = $this->programationVersion;
        $version_arr = json_decode($ver->json_programation, true);
        $month_at = "";
        $months_arr = [];
        foreach ($version_arr as $idx => $vsion_val) {
            $months_arr[] = $idx;
        }
        foreach ($months_arr as $i => $m) {
            if ($i == 0) {
                $date = new \Carbon\Carbon($m);
                $month_at .= ucfirst($date->locale('pt_BR')->isoFormat('MMMM')) .' '. ucfirst($date->locale('pt_BR')->isoFormat('YYYY'));
                if (count($months_arr) > 1)
                    $month_at .= ' -> ';

            } else if ($i == count($months_arr)-1) {
                $date = new \Carbon\Carbon($m);
                $month_at .= ucfirst($date->locale('pt_BR')->isoFormat('MMMM')) .' '. ucfirst($date->locale('pt_BR')->isoFormat('YYYY'));
            }
        }

        return $month_at;
    }
}
