<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsEntryExitSecurityGuard extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_entry_exit_security_guard';

    public function logistics_entry_exit_gate() {
        return $this->hasOne(LogisticsEntryExitGate::class, 'id', 'entry_exit_gate_id');
    }
}
