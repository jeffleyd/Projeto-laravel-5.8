<?php


namespace App\Services\Departaments;


use App\Model\UserHoliday;
use App\Services\Departaments\Rules\RTDGlobal;
use App\Services\Departaments\Interfaces\Analyze;
use App\Widgets\BlockApprov;
use Illuminate\Support\Collection;

class ProcessAnalyze extends RTDGlobal
{
    /**
     * O resultado do metódo principal depois de executado.
     * @var mixed
     */
    protected $resultEvent;

    /**
     * A solicitação que será executada.
     * @var Analyze
     */
    protected $solicitation;

    /**
     * Linha da tabela "request_analyze" que será atualizada depois da ação.
     * @var Collection
     */
    protected $analyze;

    /**
     * Resultados do before antes de executar o metódo principal.
     * @var array
     */
    public $bresult;

    /**
     * @var array [Regras que serão executadas antes do metodo principal]
     */
    public $before;
    /**
     * @var array [Regras que serão executadas depois do metodo principal]
     */
    public $after;

    public function __construct(Analyze $solicitation)
    {
        $this->solicitation = $solicitation;
        $this->analyze = $this->solicitation->model->rtd_status['status']['validation']
                      ->where('r_code', $this->solicitation->request->session()->get('r_code'))->first();
    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventStart(array $exceptions = [], array $extraFunc = []) {

        $this->before = ['rulesStatusStartAnalyze'];
        $result = $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc);
        $holiday_result = $this->verifyUserIsHolidayAndChange($result);
		
		$blockApprov = new BlockApprov($holiday_result, $this->solicitation->model);
        $blockApprov->updateScheme(true);

        if($this->solicitation->model->rtd_analyze()->createMany($holiday_result)) {
            $this->solicitation->model->has_analyze = 1;
            $this->solicitation->model->is_approv = 0;
            $this->solicitation->model->is_reprov = 0;
            $this->solicitation->model->version = $this->solicitation->model->rtd_status['last_version'] + 1;
            $this->solicitation->model->save();
        }
    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventApprov(array $exceptions = [], array $extraFunc = []) {

        // Methods from event
        $this->before = ['validPasswordUsers', 'rulesVerifyHasAnalyze', 'rulesVerifyValidationAnalyze'];
        $this->after = [];
        $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc, false);

        $this->analyze->is_approv = 1;
		$this->analyze->is_suspended = 0;
        $this->analyze->description = $this->solicitation->request->description;
        $this->analyze->save();

        $result = $this->solicitation->approvAnalyze();

        is_array($this->after) ? $this->afterFunc(
            $this->execMethods(array_diff($this->after, $exceptions)),
            isset($extraFunc['after']) ? $extraFunc['after'] : []
        ) : null;
		
		$blockApprov = new BlockApprov([], $this->solicitation->model);
        $blockApprov->updateScheme(false, $this->solicitation->request->session()->get('r_code'));

        return $result;

    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventReprov(array $exceptions = [], array $extraFunc = []) {

        // Methods from event
        $this->before = ['validPasswordUsers', 'rulesVerifyHasAnalyze', 'rulesVerifyValidationAnalyze'];
        $this->after = [];
        $result = $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc);

        $this->analyze->is_reprov = 1;
		$this->analyze->is_suspended = 0;
        $this->analyze->description = $this->solicitation->request->description;
        $this->analyze->save();
		
		$blockApprov = new BlockApprov([], $this->solicitation->model);
        $blockApprov->updateScheme(false, $this->solicitation->request->session()->get('r_code'));

        return $result;
    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventSuspended(array $exceptions = [], array $extraFunc = []) {

        // Methods from event
        $this->before = ['validPasswordUsers', 'rulesVerifyHasAnalyze', 'rulesVerifyValidationAnalyze'];
        $this->after = [];
        $result = $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc);

        $this->analyze->is_suspended = 1;
        $this->analyze->description = $this->solicitation->request->description;
        $this->analyze->save();

        return $result;
    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventCancel(array $exceptions = [], array $extraFunc = []) {

        // Methods from event
        $this->before = [];
        $this->after = [];
        $result = $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc);

        return $result;
    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventRevert(array $exceptions = [], array $extraFunc = []) {

        // Methods from event
        $this->before = ['validPasswordUsers', 'rulesVerifyHasAnalyze', 'rulesVerifyValidationAnalyze'];
        $this->after = [];
        $result = $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc);

        foreach ($this->solicitation->model->rtd_analyze as $key) {
            if($key->position >= $this->solicitation->request->position) {
                $key->is_approv = 0;
                $key->is_reprov = 0;
                $key->is_suspended = 0;
                $key->is_holiday = 0;
                $key->description = null;
                $key->save();
            }
        }
		
		$blockApprov = new BlockApprov([], $this->solicitation->model);
        $blockApprov->updateScheme(false, $this->solicitation->request->session()->get('r_code'));
		
        return $result;
    }

    /**
     * @param array $exceptions
     * @return bool
     * @throws \Exception
     */
    public function eventApprovNow(array $exceptions = [], array $extraFunc = []) {

        // Methods from event
        $this->before = ['validPasswordUsers', 'rulesVerifyHasAnalyze'];
        $this->after = [];
        $result = $this->setCallMethods($exceptions, __FUNCTION__, $extraFunc);
		
		// Bloco Aprovação
		$blockApprov = new BlockApprov([], $this->solicitation->model);
		$users_process = $this->solicitation->model->rtd_status['status']['validation'];
        foreach ($users_process as $user) {
			$blockApprov->updateScheme(false, $user->r_code, false);
        }

        $validation = $this->solicitation->model->rtd_status['status']['validation']->first();
        $position = $validation->position;
        $version = $validation->version;

        foreach ($this->solicitation->model->rtd_analyze as $key) {
            if($key->position >= $position) {
                $key->delete();
            }
        }

        $this->solicitation->model->rtd_analyze()->createMany([[
            'version' => $this->solicitation->model->rtd_status['last_version'] + 1, 
            'r_code' => $this->solicitation->request->session()->get('r_code'), 
            'position' => $position,
            'version' => $version,
            'is_approv' => 1,
            'description' => ''.$this->solicitation->request->description.'<br><span style="color:red;display: contents;">Aprovação realizada de forma imediata</span>'
        ]]);

        $request = $this->solicitation->model;
        $request->is_approv = 1;
        $request->has_analyze = 0;
        $request->save();
		
		$blockApprov = new BlockApprov([], $this->solicitation->model);
        $blockApprov->updateScheme(false, $this->solicitation->request->session()->get('r_code'));

        return $result;
    }    

    /**
     * Verifica se o usuário está de férias e muda para outro usuário decidido por ele.
     * @param array $users
     * @return array
     */
    private function verifyUserIsHolidayAndChange(array $users): array {

        $result = $users;
        $users_collect = collect($users);
        $r_code_in_holiday = UserHoliday::whereIn('user_r_code', $users_collect->pluck('r_code'))->get();
        if ($r_code_in_holiday->count()) {
            $result = [];
            foreach ($users as $val) {
                $is_holiday = $r_code_in_holiday->where('user_r_code', $val['r_code'])->first();
                array_push($result,
                    [
                        'version' => $val['version'],
                        'r_code' =>  $is_holiday ? $is_holiday->receiver_r_code : $val['r_code'],
                        'position' => $val['position'],
                        'is_holiday' => $is_holiday ? 1 : 0,
                        'mark' => $val['mark'] ?? 1
                    ]
                );
            }
        }

        return $result;
    }

    /**
     * @param array $exceptions [Usado para remover as regras que não deseja]
     * @param string $name [Nome do metódo executado]
     * @param array $extraFunc [Funcões anônimas que poderão usar o resultados das regras para
     * manipular antes ou depois do metodo principal]
     * @return mixed
     * @throws \Exception
     */
    private function setCallMethods(array $exceptions, string $name, array $extraFunc = [], $needExec = true) {
        $method = str_replace('event', '', $name).'Analyze';

        $this->bresult = $this->execMethods(array_diff($this->before, $exceptions));
        is_array($this->before) ? $this->beforeFunc(
            $this->bresult,
            isset($extraFunc['before']) ? $extraFunc['before'] : []
        ) : null;

        if ($needExec) {
            // Execute main method
            $this->resultEvent = $this->solicitation->$method();

            is_array($this->after) ? $this->afterFunc(
                $this->execMethods(array_diff($this->after, $exceptions)),
                isset($extraFunc['after']) ? $extraFunc['after'] : []
            ) : null;
        }


        return $this->resultEvent;
    }

    /**
     * Será executado antes do metodo principal
     * @param array $results [Array com todos os resultados das regras executadas e seus respectivos nomes]
     * @param array $functions [Array com todas as funcões anônimas que será executadas com o resultados das regras]
     * @return void
     */
    private function beforeFunc(array $results, array $functions) {
        foreach ($results as $result) {
            if (isset($functions[$result['name']]))
                $functions[$result['name']]($result['result']);
        }
    }

    /**
     * Será executado depois do metodo principal
     * @param array $results [Array com todos os resultados das regras executadas e seus respectivos nomes]
     * @param array $functions [Array com todas as funcões anônimas que será executadas com o resultados das regras]
     * @return void
     */
    private function afterFunc(array $results, array $functions) {
        foreach ($results as $result) {
            if (isset($functions[$result['name']]))
                $functions[$result['name']]($result['result'], $this->resultEvent);
        }
    }

    /**
     * @param $methods
     * @throws \Exception
     * @return array
     */
    protected function execMethods($methods): array
    {
        $arr_methods = [];
        foreach ($methods as $method) {
            if ($method) {
                $result = $this->$method();
                $arr_methods[] = ['name' => $method, 'result' => $result];
            }
        }
        return $arr_methods;
    }

    private function clearSuspended() {

    }
}