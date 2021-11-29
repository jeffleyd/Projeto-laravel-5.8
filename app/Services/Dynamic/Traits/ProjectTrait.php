<?php

namespace App\Services\Dynamic\Traits;

use App\Model\Dynamic\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

trait ProjectTrait
{

    /**
     * Remove os caracteres, deixa minusculo e adicionar underscore nos espaços.
     * @param $words
     * @return string
     */
    public function formatWordsForDB($words): string {
        $ascii = Str::ascii($words);
        $ascii_slug = Str::slug($ascii, '_');

        return strtolower($ascii_slug);
    }

    /**
     * Valida se a pessoa que está mexendo tem acesso ao projeto.
     * @param Projects $project
     * @return bool
     */
    public function validateOwnerProject(Projects $project): bool
    {
        $get_r_codes = collect(explode(',', $project->r_codes_avaible));
        if ($get_r_codes->search(Session::get('r_code')) !== false
            or $project->r_code == Session::get('r_code')) {
            return true;
        }
        throw new \Exception("O projeto que está mexendo, não pertence a você.");
    }

    /**
     * Valida se o campo "Columns" existe e é uma array e outros possíveis campos.
     * @param Request $request
     * @return bool
     */
    public function validateArrayWithColumns(Request $request, ...$fields): bool
    {
        if (is_array($request->columns)) {
            foreach ($fields as $field) {
                foreach ($request->columns as $column) {
                    if (!isset($column[$field])) {
                        throw new \Exception("Estrutura de parametros para atualização do projeto é inválida.");
                    }
                }
            }
        } else {
            throw new \Exception("Estrutura de parametros para atualização do projeto é inválida.");
        }

        return true;
    }

}
