<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'survey';

    protected $casts = [
        'frequency_week' => 'array',
        'frequency_month' => 'array',
    ];
}
