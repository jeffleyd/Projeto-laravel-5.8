<?php

namespace App\Services\Departaments\Interfaces;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

Interface Analyze
{

    public function __construct($model, Request $request);

    /**
     * Initialize analyze of departament
     * 
     * @return void
     */
    public function startAnalyze(): Array;

    /**
     * Approv analyze of departament
     * 
     * @return void
     */
    public function approvAnalyze();

    /**
     * Reprov analyze of departament
     * 
     * @return void
     */
    public function reprovAnalyze();

    /**
     * Suspend analyze of departament
     * 
     * @return void
     */
    public function suspendedAnalyze();

    /**
     * Revert analyze of departament
     * 
     * @return void
     */
    public function RevertAnalyze();
	
	/**
     * Approv analyze immediate of departament
     * 
     * @return void
     */
    public function ApprovNowAnalyze();
}    