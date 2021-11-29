<?php

namespace App\Model\Services\Analyze;

use App\Model\Services\Analyze\Model\RequestAnalyze;
use App\Model\Services\Analyze\Model\RequestAnalyzeApprovers;
use App\Model\Services\Analyze\Model\RequestAnalyzeObservers;
use App\Model\FinancyRPaymentRelationship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProcessAnalyze extends Model
{

    /**
     * Informar o prefixo dos atributos como rtd_
     */
    protected $appends = [
        'rtd_status',
		'rtd_position',
		'rtd_status_html'
    ];

    public function rtd_analyze() {
        return $this->morphMany(RequestAnalyze::class, 'analyze');
    }

    public function rtd_approvers() {
        $data = RequestAnalyzeApprovers::with('users')->where('analyze_type', get_class($this));
        return $data->get();
    }

    public function rtd_observers() {
        $data = RequestAnalyzeObservers::with('users')->where('analyze_type', get_class($this));
        return $data->get();
    }

    /**
     * @param $query
     * Sua solicitação precisará ter a coluna "version".
     * @param $r_code [Matricula do colaborador]
     * @return mixed
     */
    public function scopeValidAnalyzeProccess($query, $r_code) {

        $table = $this->table;
		
        $position = "(
                SELECT MIN(pos.position) FROM request_analyze as pos
                WHERE pos.r_code = '$r_code'
				AND pos.is_approv = 0
                AND pos.analyze_type = request_analyze.analyze_type
                AND pos.analyze_id = request_analyze.analyze_id
                AND pos.version = $table.version
            )";
		
		$position_max = "(
                SELECT MAX(pos.position) FROM request_analyze as pos
                WHERE pos.r_code = '$r_code'
				AND pos.is_approv = 1
                AND pos.analyze_type = request_analyze.analyze_type
                AND pos.analyze_id = request_analyze.analyze_id
                AND pos.version = $table.version
            )";
		
		$conditions = "IF($position IS NOT NULL, $position, $position_max)";

        return $query->whereHas('rtd_analyze', function ($q) use ($r_code, $table, $position) {
            $q->whereRaw("request_analyze.version = $table.version AND request_analyze.r_code=$r_code");
        })
			->whereRaw("EXISTS (
					SELECT pos.position FROM request_analyze as pos
					WHERE pos.r_code = '$r_code'
					AND pos.is_approv = 0
					AND pos.analyze_type = '".addslashes(get_class($this))."'
					AND pos.analyze_id = $table.id
					AND pos.version = $table.version
				)")
            ->whereHas('rtd_analyze', function ($q) use ($r_code, $table, $position, $conditions) {
                $q->whereRaw("request_analyze.version = $table.version
        AND (
            EXISTS (SELECT 1 FROM request_analyze as r_1
                    WHERE r_1.analyze_type = request_analyze.analyze_type
                    AND r_1.analyze_id = request_analyze.analyze_id
                    AND r_1.version = $table.version
                    AND r_1.is_approv = 1
                    AND r_1.position = ($position-1)
                    )
            OR NOT EXISTS (SELECT 1 FROM request_analyze as r_1
                    WHERE r_1.analyze_type = request_analyze.analyze_type
                    AND r_1.analyze_id = request_analyze.analyze_id
                    AND r_1.version = $table.version
                    AND ((r_1.is_reprov = 1 OR r_1.is_suspended = 1) OR (r_1.is_approv = 0 AND r_1.is_reprov = 0))
                    AND r_1.position = ($position-1)
                    )
            )
    AND NOT EXISTS (SELECT 1 FROM request_analyze as r_2
                WHERE r_2.analyze_type = request_analyze.analyze_type
                AND r_2.analyze_id = request_analyze.analyze_id
                AND r_2.version = $table.version
               	AND (r_2.is_approv = 1 OR r_2.is_reprov = 1)
                AND r_2.position = $conditions)");
            });
    }

    /**
     * Retorna o coleção completa, as coleçoes por versão
     * Valor da última versão
     * Status da última versão
     * 1 = Reprovado
     * 2 = Aprovado
     * 3 = Em análise
     * 4 = Suspenso
     * @return array
     */
    public function getRtdStatusAttribute(): array
    {
        if ($this->rtd_analyze->count() and !$this->is_cancelled) {
            $version = $this->rtd_analyze->max('version');
            $status = $this->findStatus($this->rtd_analyze, $version);

            $collect_versions = [];
            foreach ($this->rtd_analyze->groupBy('version') as $version => $collect) {
				$max_position = $collect->where('is_approv', 1)->where('mark', 1)->max('position');
                $marks = collect([]);
                foreach ($collect->where('is_approv', 1) as $item) {
                    if ($item->position >= $max_position)
                        $marks->push($item);
                }
                $collect_versions[$version] = [
                    'collect' => $collect->groupBy('position'),
					'mark' => $marks,
                    'status' => $this->findStatus($collect, $version),
                ];
            }
            $scheme = [
                'collect' => $this->rtd_analyze,
                'versions' => $collect_versions,
                'last_version' => $version,
                'status' => $status,
                'code' => $this->code,
            ];
        } else {
            $scheme = [
                'collect' => [],
                'versions' => [],
                'last_version' => 0,
                'status' => [
                    'code' => 0,
                    'validation' => collect([]),
                    'situation' => $this->is_cancelled ? 'A solitação foi cancelada!' : 'Ainda não foi enviado para análise.'
                ],
                'code' => $this->code,
            ];
        }

        return $scheme;
    }
	
	public function getRtdPositionAttribute()
    {
        return $this->rtd_status['status']['validation']->count() ? $this->rtd_status['status']['validation']->first()->position : 1;
    }

    /**
     * Busca a situação da análise por versão.
     * @param Collection $analyze
     * @param int $version
     * @return string[]
     */
    protected function findStatus(Collection $analyze, $version = 1): array
    {
        $proccess_analyze = $analyze->where('version', $version)->groupBy('position');

        $validator = collect([]);
        foreach ($proccess_analyze as $value) {
            if ($value->where('is_reprov', 1)->count()) {
                $status = [ 'code' => 1, 'validation' => collect([]), 'situation' => 'Reprovado'];
                break;
            } else if ($value->where('is_approv', 1)->count()) {
                $status = ['code' => 2, 'validation' => collect([]), 'situation' => 'Aprovado'];
            } else if ($value->where('is_suspended', 1)->count()) {
                if(!$validator->count())
                    $validator = $value;
                $status = ['code' => 4, 'validation' => $validator, 'situation' => 'Suspenso no momento'];
				break;
            } else {
                if(!$validator->count())
                    $validator = $value;
                $status = ['code' => 3, 'validation' => $validator, 'situation' => 'Em análise'];
            }
        }
        return $status;
    }
	
	public function getRtdStatusHtmlAttribute() {
		if ($this->is_reprov == 1)
			return '<span class="badge badge-light-danger">REPROVADO</span>';
		elseif ($this->is_paid == 1)
			return '<span class="badge badge-light-success">PAGO</span>';
		elseif ($this->is_approv == 1)
			return '<span class="badge badge-light-success">APROVADO</span>';
		elseif ($this->has_analyze == 1)
			return '<span class="badge badge-light-info">EM ANÁLISE</span>';
		else
			return '<span class="badge badge-light-secondary">NÃO ENVIADO</span>';
	}
	
	// ESSE METÓDO NÃO É PARA ESTAR AQUI
	public function relation_payment() {
        $data = FinancyRPaymentRelationship::where('module_type', get_class($this))->where('module_id', $this->id);
        return $data->first();
    }
	
}
