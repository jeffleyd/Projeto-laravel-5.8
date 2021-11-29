<?php


namespace App\Model\Commercial;
use Illuminate\Database\Eloquent\Model;

class ProgramationVersion extends model
{

    protected $table = 'programation_version';
    protected $connection = 'commercial';
    protected $appends = [
        'created'
    ];

    public function getCreatedAttribute() {
        return date('d/m/Y', strtotime($this->created_at));
    }
}
