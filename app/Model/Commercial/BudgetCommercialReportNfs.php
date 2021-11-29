<?php

namespace App\Model\Commercial;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;

class BudgetCommercialReportNfs extends Model
{
    protected $table = 'budget_commercial_report_nfs';
    protected $connection = 'commercial';
}
