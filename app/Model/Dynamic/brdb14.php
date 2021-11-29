<?php

namespace App\Model\Dynamic;

use Illuminate\Database\Eloquent\Model;

class brdb14 extends Model
{
    protected $table = "brdb14";
    protected $connection = "dynamic";

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
