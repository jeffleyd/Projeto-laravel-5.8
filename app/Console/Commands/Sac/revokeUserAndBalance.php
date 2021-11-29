<?php

namespace App\Console\Commands\Sac;

use App\Model\SacProtocol;
use App\Model\Users;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class revokeUserAndBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gree:sac_revoke_user {r_code*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desativa o usuário e distribuir os protocolos em anexo dele para outros usuários.';

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
     * @return void
     */
    public function handle()
    {
        $r_code = $this->argument('r_code');
        $protocols = SacProtocol::whereIn('r_code', $r_code)
                ->where('is_cancelled', 0)
                ->where('is_completed', 0)
                ->where('is_refund', 0)
                ->get();

        if ($protocols->count()) {
            $this->info('Foi encontrado um total de '.$protocols->count().' protocolos');

			// Remover colaboradores em excessão.
			array_push($r_code, [2692, 2571]);
			
            $users = DB::table('sac_protocol')
                ->join('users', 'sac_protocol.r_code', '=', 'users.r_code')
                ->select(DB::raw('count(sac_protocol.id) as total, users.first_name, users.r_code'))
                ->where('sac_protocol.is_completed', 0)
                ->where('sac_protocol.is_cancelled', 0)
                ->where('sac_protocol.is_refund', 0)
                ->where('sac_protocol.r_code', '!=', null)
                ->whereNotIn('sac_protocol.r_code', $r_code)
                ->groupBy('sac_protocol.r_code')
                ->orderBy('sac_protocol.r_code', 'DESC')
                ->get()->pluck('r_code');

            if ($this->confirm('Que será distribuido para um total de '.$users->count().' colaboradores, você deseja continuar?')) {

                $index = 0;
                $index_users = $users->count()-1;
                // Distribue os atendimentos entre os operadores.
                foreach ($protocols as $protocol) {

                    // Reseta o index;
                    if ($index > $index_users) {
                        $index = 0;
                    }
                    $protocol->r_code = $users[$index];
                    $protocol->save();
                    $index++;
                }
                $this->info('Operação finalizada, Foi distribuido todos os protocolos.');

                $desactive = Users::whereIn('r_code', $r_code)->where('is_active', 1)->get();
                foreach ($desactive as $key) {
                    $key->is_active = 0;
                    $key->save();
                }
                $this->info('Total de '.$desactive->count().' usuários foram desativados do sistema.');
            } else {
                $this->info('Operação finalizada, não houve alterações no banco de dados.');
            }

        } else {
            $this->info('Não há protocolos abertos para esse colaborador.');
        }

    }
}
