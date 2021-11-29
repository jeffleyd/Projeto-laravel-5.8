<?php


namespace App\widgets;


use App\Model\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlockApprov
{
    private $approvers;
    private $model;

    /**
     * blockApprov constructor.
     * @param $approvers = Array com todos aprovadores, só será usado no incio da análise.
     * @param $model = model que está sendo usado no processo.
     */
    public function __construct($approvers, $model)
    {
        $this->approvers = $approvers;
        $this->model = $model;
    }

    /**
     * Faz a validação do esquema do bloco, caso não tenha, ele gera um padrão.
     * @param $r_code
     * @return array
     */
    private function getSchemaBlock($r_code): array
    {
        $user = $this->findUser($r_code);
        if (Storage::disk('wdget_approv')->exists($user->id.'.json')) {
            $scheme = json_decode(Storage::disk('wdget_approv')->get($user->id.'.json'), true);
        } else {
            Storage::disk('wdget_approv')->put($user->id.'.json', '{}');
            $scheme = [];
        }

        return $scheme;
    }

    /**
     * Verifica se o usuário consta na base de dados.
     * @param $r_code
     * @return mixed
     * @throws \Exception
     */
    private function findUser($r_code) {
        $user = Users::where('r_code', $r_code)->first();
        if (!$user)
            throw new \Exception('Ocorreu um erro ao registrar o valor no widget de aprovação.');

        return $user;
    }

    /**
     * @param false $isStepFirst Identifica se atualização é da etapa de inicialização do processo.
     * @param null $r_code Matricula do usuário
     * @param bool $processNext Caso seja falso, ele não adicionará valor no bloco para os próximos aprovadores.
     */
    public function updateScheme($isStepFirst = false, $r_code = null, $processNext = true) {
        if ($isStepFirst) {
            foreach($this->approvers as $user) {
                if ($user['position'] == 1) {
                    $this->setScheme($user['r_code']);
                }
            }
        } else {
            $this->unsetScheme($r_code, $processNext);
        }
    }

    /**
     * Acrescenta valor no bloco de aprovação, caso não exista, ele inclui o módulo no esquema.
     * @param $r_code
     */
    private function setScheme($r_code) {
        $scheme = $this->getSchemaBlock($r_code);
        $index = $this->searchScheme($scheme);
        if (is_numeric($index))
            $scheme[$index]['qtd'] += 1;
        else
            $scheme = $this->insertScheme($scheme);

        $this->saveScheme($scheme, $r_code);
    }

    /**
     * Decrescenta valor no bloco de aprovação.
     * @param $r_code
     * @param bool $processNext Caso seja verdadeiro, ele adicionará valor no bloco dos próximos aprovadores.
     */
    private function unsetScheme($r_code, $processNext = true) {

        $analyze = $this->model->rtd_analyze()->get();
        $user_analyzing = $analyze->where('r_code', $r_code)->first();
        $users_process = $analyze->where('position', $user_analyzing->position);

        foreach ($users_process as $user) {
            $scheme = $this->getSchemaBlock($user->r_code);
            $index = $this->searchScheme($scheme);
            if (is_numeric($index)) {
                if ($scheme[$index]['qtd'] == 1)
                    unset($scheme[$index]);
                else
                    $scheme[$index]['qtd'] -= 1;
            }

            if ($processNext)
                $this->nextApprovers();

            $this->saveScheme($scheme, $user->r_code);
        }
    }

    /**
     * Seta o valor nos blocos dos próximos aprovadores.
     */
    private function nextApprovers() {
        $users_process = $this->model->rtd_status['status']['validation'];
        foreach ($users_process as $user) {
            $this->setScheme($user->r_code);
        }
    }

    /**
     * Atualiza no diretório o esquema do bloco do aprovador.
     * @param $scheme
     * @param $r_code
     * @throws \Exception
     */
    private function saveScheme($scheme, $r_code) {
        $user = $this->findUser($r_code);
        Storage::disk('wdget_approv')->delete($user->id.'.json');
        Storage::disk('wdget_approv')->put($user->id.'.json', json_encode($scheme));
    }

    /**
     * Cria um novo módulo no bloco do aprovador.
     * @param $scheme
     * @return mixed
     */
    private function insertScheme($scheme) {
        $scheme[] = [
            'name' => $this->model->configClass('name'),
            'qtd' => 1,
            'namespace' => get_class($this->model),
            'url' => $this->model->configClass('url'),
        ];
        return $scheme;
    }

    /**
     * Pesquisa o módulo no esquema do aprovador.
     * @param array $scheme
     * @return int|string|null
     */
    private function searchScheme(array $scheme) {
        foreach($scheme as $index => $module) {
            if ($module['namespace'] == get_class($this->model)) {
                return $index;
            }
        }
        return null;
    }
}
