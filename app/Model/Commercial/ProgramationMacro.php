<?php


namespace App\Model\Commercial;
use Illuminate\Database\Eloquent\Model;

class ProgramationMacro extends model
{

    protected $table = 'programation_macro';
    protected $connection = 'commercial';

    public function programation() {
        return $this->belongsTo(Programation::class, 'programation_id', 'id');
    }
}
