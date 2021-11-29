<?php

namespace App\Model\Commercial;

use Illuminate\Database\Eloquent\Model;

class BudgetCommercialDuplicates extends Model
{
    protected $table = 'budget_commercial_duplicates';
    protected $connection = 'commercial';
}
