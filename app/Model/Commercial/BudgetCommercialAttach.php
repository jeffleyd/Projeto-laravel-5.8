<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class BudgetCommercialAttach extends Model
{
    protected $table = 'budget_commercial_attach';
    protected $connection = 'commercial';
}
