<?php


namespace App\Helpers\Financy;


use App\Model\FinancyLending;
use App\Model\FinancyRefundItem;
use App\Model\FinancyRPayment;
use App\Model\FinancyRPaymentAttach;
use App\Model\FinancyRPaymentRelationship;
use Illuminate\Support\Facades\DB;

/**
 * Class Payment
 * @package App\Helpers\Financeiro
 */
class Payment {

    /**
     * Função usada para criar um nova solicitação de pagamento interligado a outro módulo.
     *
     *
     */
    public function newPayment(array $params) {

        DB::beginTransaction();

        $r_payment = new FinancyRPayment;
        $r_payment->code = getCodeModule('payment', $params['request_r_code']);

        if (isset($params['agency']) and isset($params['account']) and isset($params['bank'])) {
            $r_payment->agency = $params['agency'];
            $r_payment->account = $params['account'];
            $r_payment->bank = $params['bank'];
        } else {
            throw new \Exception("Você precisa enviar a conta bancária para ser registrada.");
            DB::rollBack();
        }

        if (array_key_exists('identity', $params))
            $r_payment->identity = $params['identity'];
        if (array_key_exists('cnpj', $params))
            $r_payment->cnpj = $params['cnpj'];

        $r_payment->request_r_code = $params['request_r_code'];
        $r_payment->request_category = $params['request_category'];
        $r_payment->has_analyze = 0;
        $r_payment->is_approv = 1;
        $r_payment->nf_nmb = $params['nf_nmb'];
        $r_payment->amount_gross = $params['amount_gross'];
        $r_payment->amount_liquid = $params['amount_liquid'];
        $r_payment->optional = $params['optional'];
        $r_payment->recipient = $params['recipient'];
        $r_payment->recipient_r_code = $params['recipient_r_code'];
        $r_payment->due_date = $params['due_date'];
        $r_payment->description = $params['description'];
        // 1 = Boleto, 2 = Transferência / D.Automático, 3 = Caixa
        $r_payment->p_method = $params['p_method'];
        $r_payment->save();

        if (array_key_exists('module', $params)) {
            $r_payment->payment_relationship()->create([
                'module_id' => $params['module']['id'],
                'module_type' => $params['module']['type'],
                'financy_r_payment_id' => $r_payment->id,
            ]);
        }

        // Adiciona os arquivos na solicitação de pagamento
        if (array_key_exists('files', $params)) {
            if (count($params['files']) > 0) {
                foreach ($params['files'] as $file) {
                    $attach = new FinancyRPaymentAttach;
                    $attach->name = $file['name'];
                    $attach->size = $file['size'];
                    $attach->financy_r_payment_id = $r_payment->id;
                    $attach->url = $file['url'];
                    $attach->save();
                }
            }
        }

        DB::commit();
        return $r_payment;

    }

    public function exampleCreate($request = null) {

        $newPayment = [
            'agency' => '0864',
            'account' => '0006310-0',
            'bank' => 'Bradesco',
            'identity' => '13299800779',
            'cnpj' => '00000000/0001-00',
            'description' => '',
            'request_r_code' => '4447',
            'request_category' => 10,
            'nf_nmb' => 'CONTABILIZADO',
            'amount_gross' => 5300.20,
            'amount_liquid' => 0.00,
            'recipient' => 'MAGAZINE LUIZA',
            'recipient_r_code' => null,
            'due_date' => '2020-12-21',
            'p_method' => 2,
            'module' => [
                'type' => 'App\Model\Commercial\BudgetCommercial',
                'id' => 10
            ],
            'files' => [
                0 => [
                    'name' => 'Arquivo1',
                    'size' => '500',
                    'url' => 'https://teste1'
                ],
                1 => [
                    'name' => 'Arquivo2',
                    'size' => '300',
                    'url' => 'https://teste2'
                ],
            ],
            'optional' => 'Apenas um informação adicional',
        ];

        $this->newPayment($newPayment);
    }
}
