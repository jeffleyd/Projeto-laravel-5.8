<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class JuridicalProcessDocuments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'juridical_process_documents';

    protected $appends = [
        'file_ext'
    ];

    public function juridical_type_document() {
        return $this->hasOne(JuridicalTypeDocument::class, 'id', 'juridical_type_document_id');
    }

    function getFileExtAttribute() {
        return  pathinfo($this->url, PATHINFO_EXTENSION);
    }    
}
