<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryExitEmployees extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'entry_exit_employees';

    protected $appends = [
        'request_user',
        'sector_name',
        'reason_name',
        'who_excute_action',
        'status',
    ];

	public function entry_exit_employees_items() {
        return $this->hasMany(EntryExitEmployeesItems::class);
    }
	
	public function logistics_warehouse() {
        return $this->hasOne(LogisticsWarehouse::class, 'id', 'warehouse_id');
    }
	
    public function logistics_entry_exit_gate() {
        return $this->hasOne(LogisticsEntryExitGate::class, 'id', 'logistics_entry_exit_gate_id');
    }

    public function SecurityGuardLiberateDeneid() {
        return $this->hasOne(LogisticsEntryExitSecurityGuard::class, 'id', 'logistics_entry_exit_security_guard_id');
    }

    public function SecurityGuardLiberateDeletedid() {
        return $this->hasOne(LogisticsEntryExitSecurityGuard::class, 'id', 'del_logistics_entry_exit_security_guard_id');
    }

    function who_analyze() {
        return $this->belongsTo(Users::class, 'who_analyze_r_code', 'r_code');
    }

    function create_user() {
        return $this->belongsTo(Users::class, 'create_r_code', 'r_code');
    }

    function users() {
        return $this->belongsTo(Users::class, 'request_r_code', 'r_code');
    }

    function userCancel() {
        return $this->belongsTo(Users::class, 'cancelled_r_code', 'r_code');
    }

    function users_not_access() {
        return $this->belongsTo(UsersNotAccess::class, 'request_r_code', 'r_code');
    }

    public function getWhoExcuteActionAttribute() {
        if ($this->deleted_at) {
            $user = $this->SecurityGuardLiberateDeletedid;
            if ($user)
                return $user->name;
            else
                return '';
        } else if ($this->is_liberate or $this->is_denied) {
            $user = $this->SecurityGuardLiberateDeneid;
            if ($user)
                return $user->name;
            else
                return '';
        } else if ($this->is_cancelled) {
            $user = $this->userCancel;
            if ($user)
                return $user->full_name;
            else
                return '';
        } else {
            return '';
        }
    }

    function getRequestUserAttribute() {
        if ($this->request_user_type == 1)
            return $this->users;
        else
            return $this->users_not_access;
    }

    function getSectorNameAttribute() {
        $sector = config('gree.sector');
        return isset($sector[$this->request_sector]) ? $sector[$this->request_sector] : 'Não definido';
    }

    function getReasonNameAttribute() {

        $reason = [
             1 => 'Serviço',
             2 => 'Particular',
             3 => 'Almoço',
			 4 => 'Esqueceu o crachá',
			 5 => 'Saúde',
        ];

        return isset($reason[$this->reason]) ? $reason[$this->reason] : 'Não definido';
    }

    function getStatusAttribute() {
        if($this->deleted_at)
            $status = 'Deletado';
        elseif ($this->is_reprov)
            $status = 'Reprovado';
        elseif ($this->is_cancelled)
            $status = 'Cancelado';
        elseif ($this->is_denied)
            $status = 'Negado';
        elseif ($this->is_liberate)
            $status = 'Liberado';
        elseif ($this->has_analyze)
            $status = 'Em análise';
        else
            $status = 'Aguard. Liberação';
        return $status;
    }
}
