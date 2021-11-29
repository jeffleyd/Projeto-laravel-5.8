<?php

namespace App\Services\Dynamic\Interfaces;

use Illuminate\Http\Request;

interface Widgets
{
    public function __construct(Request $request);

    /**
     * Responsável por criar
     * @return mixed
     */
    public function create();

    /**
     * Responsável por atualizar
     * @return mixed
     */
    public function update();

    /**
     * Responsável por deletar
     * @return mixed
     */
    public function delete();
}
