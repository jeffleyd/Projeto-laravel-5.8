<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalProcessHistoric extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_process_historic';

    public function juridical_process_documents() {
        return $this->hasMany(JuridicalProcessDocuments::class, 'juridical_process_historic_id', 'id');
    }

    public function juridical_process() {
        return $this->belongsTo(JuridicalProcess::class, 'juridical_process_id', 'id');
    }
}
