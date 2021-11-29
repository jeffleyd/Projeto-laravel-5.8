<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Casts\NumberFormat;

class FinancyUsersDebtors extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financy_users_debtors';

    protected $appends = [];

    protected $fillable = ['r_code'];

    /**
     * lendings Relationships
     *
     * @param integer $tipo
     * @return void
     */
    public function lendings()
    {
        return $this->hasMany(FinancyLending::class, 'r_code', 'r_code')->where('is_paid',1)->where('is_accountability_paid',0);
    }
    
    public function all_lendings()
    {
        return $this->hasMany(FinancyLending::class, 'r_code', 'r_code')->where('is_paid',1);
    }
    
    public function receiver_history()
    {
        return $this->hasMany(FinancyAccountabilityReceiverHistory::class, 'financy_users_debt_id', 'id');
    }

    public function obs_history()
    {
        return $this->hasMany(FinancyAccountabilityObservationHistory::class, 'financy_users_debt_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'r_code', 'r_code');
    }

    /**
     * function getTotalEmprestimo
     * Retorna o Total da Divida do Usuario
     * @param [int] $tipo
     * 1='Total'
     * 2='Total Emprestimo'
     * 3='Total Emprestimo Manual'
     * 
     * @return saldo
     */
    public function getTotalEmprestimo($tipo=1){
        $total=0;
        $lendings = $this->lendings;
        
        if($lendings->isNotEmpty()){

            foreach($lendings as $lending){
                $total+=$lending->getTotalEmprestimo($tipo);
            }

        }
        return $total;
    }

    /**
     * getTotalPrestacaoConta function
     *
     * @param [int] $tipo
     * 1='Total'
     * 2='Total Prestação de Contas'
     * 3='Total Pagamentos'
     * @return void
     */
    public function getTotalPrestacaoConta($tipo=1){
        $total=0;
        $lendings = $this->lendings;
        
        if($lendings->isNotEmpty()){

            foreach($lendings as $lending){
                $total+=$lending->getTotalPrestacaoConta($tipo);
            }

        }
        return $total;
    }

    public function getTotalPago($tipo=1){
        $total=0;
        $lendings = $this->lendings;
        
        if($lendings->isNotEmpty()){

            foreach($lendings as $lending){
                $total+=$lending->getTotalPago($tipo);
            }

        }
        return $total;
    }
    
    public function getTotalPendentePago(){
        $total=0;
        $lendings = $this->lendings;
        
        if($lendings->isNotEmpty()){

            foreach($lendings as $lending){
                if($lending->is_accountability_paid==0){
                    $total+=$lending->getTotalPrestacaoConta();
                }
            }

        }
        return $total;
    }

    /**
     * function getTotalPendente
     * Retorna o Total da Divida do Usuario
     * @param [int] $tipo
     * 1='Total'
     * 2='Total Emprestimo'
     * 3='Total Emprestimo Manual'
     * 
     * @return saldo
     */
    public function getTotalPendente($tipo=1){
        $total = $this->getTotalPrestacaoConta($tipo) - $this->getTotalEmprestimo($tipo);
        return $total;
    }


}
