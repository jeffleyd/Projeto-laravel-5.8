<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class AddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gree:add_permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adiciona Permissão com base no arquivo de Permissões';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $config_permissions = collect(config('gree.permissions'));

        $db_permissions = \App\Model\Permissions::pluck('id');

        $to_insert = $config_permissions->pluck('id')->diff(collect($db_permissions));

        $permissions_to_insert = $config_permissions->whereIn('id', $to_insert);

        if($permissions_to_insert->isNotEmpty()) {
            
            foreach ($permissions_to_insert as $key){
                DB::table('permissions')->insert($key);
                $this->info('Modulo ('.$key['name'].') Adicionado com Sucesso!');
            }
            
        }else{
            $this->info('Não existem modulos a serem inseridos!');
        }
    }
}
