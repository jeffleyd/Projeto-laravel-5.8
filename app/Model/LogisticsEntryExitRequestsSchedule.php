<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LogisticsEntryExitRequestsSchedule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logistics_entry_exit_requests_schedule';
    protected $appends = [
        'who_excute_action'
    ];

    public function logistics_entry_exit_requests() {
        return $this->hasOne(LogisticsEntryExitRequests::class, 'id', 'entry_exit_requests_id');
    }

    public function SecurityGuardLiberateDenied() {
        return $this->hasOne(LogisticsEntryExitSecurityGuard::class, 'id', 'entry_exit_security_guard_id');
    }

    public function getWhoExcuteActionAttribute() {
        if ($this->is_liberate or $this->is_denied) {
            $user = $this->SecurityGuardLiberateDeneid;
            if ($user)
                return $user->name;
            else
                return '';
        } else if ($this->logistics_entry_exit_requests->is_cancelled) {
            $user = $this->logistics_entry_exit_requests->Users;
            if ($user)
                return $user->full_name;
            else
                return '';
        } else {
            return '';
        }
    }
}
