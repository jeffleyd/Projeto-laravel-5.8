<?php

namespace App\Model\Commercial;

use App\Model\Users;
use Illuminate\Database\Eloquent\Model;

class BudgetCommercialReportClients extends Model
{
    protected $table = 'budget_commercial_report_clients';
    protected $connection = 'commercial';
}
