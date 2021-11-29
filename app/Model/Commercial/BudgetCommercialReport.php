<?php

namespace App\Model\Commercial;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;

class BudgetCommercialReport extends Model
{
    protected $table = 'budget_commercial_report';
    protected $connection = 'commercial';

    public function BudgetCommercial() {
        return $this->hasOne(BudgetCommercial::class, 'id', 'budget_commercial_id');
    }

    public function user() {
        return $this->setConnection('mysql')->belongsTo(Users::class, 'r_code', 'r_code');
    }
}
