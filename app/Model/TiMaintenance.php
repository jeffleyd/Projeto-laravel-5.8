<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TiMaintenance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ti_maintenance';
    protected $appends = [
        'printer_model_name',
        'toner_model_name',
        'unit_name',
        'sector_name',
        'status_name',
        'priority_name',
    ];

    public function category()
    {
        return $this->hasOne(TiMaintenanceCategories::class, 'id', 'category_id');
    }

    public function assigns()
    {
        return $this->hasMany(TiMaintenanceAssigned::class, 'maintenance_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(TiMaintenanceNotes::class, 'maintenance_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(TiMaintenanceReplies::class, 'maintenance_id', 'id');
    }

    public function users()
    {
        return $this->hasOne(Users::class, 'r_code', 'request_r_code');
    }

    public function getPrinterModelNameAttribute() {
        $arr = [
           1 => 'HP Laserjet 400 M401dw',
           2 => 'HP LASERJET PRO M402dne',
           3 => 'HP LaserJet Pro M12w',
           4 => 'HP Laserjet Color M251nw',
           5 => 'HP Laserjet M127fn',
           6 => 'HP Laserjet M225-M226',
           7 => 'HP Laserjet M201dw',
           8 => 'Samsung ProXpress M4075fr',
           9 => 'Brother 16NW',
           10 => 'Brother DCP-L5602DN',
           11 => 'EPSON 2190',
           12 => 'HP Laser 107W',
        ];
        return $arr[$this->printer_model] ?? '';
    }

    public function getTonerModelNameAttribute() {
        $arr = [
            1 => '26A',
            2 => '78A',
            3 => '79A',
            4 => '80A',
            5 => '83A',
            6 => 'D204',
            7 => '105A',
            8 => 'BROTER',
            9 => 'D204E',
        ];
        return $arr[$this->toner_model] ?? '';
    }

    public function getUnitNameAttribute() {
        $arr = [
            1 => 'ADMINISTRATIVO',
            2 => 'GALPÃO 1',
            3 => 'GALPÃO 2',
            4 => 'GALPÃO 3',
            5 => 'AZALEIA',
            6 => 'SUZUKI G1',
            7 => 'SUZUKI G2',
        ];
        return $arr[$this->unit] ?? '';
    }

    public function getStatusNameAttribute() {
        $arr = [
            1 => 'Novo',
            2 => 'Responder',
            3 => 'Respondido',
            4 => 'Em Progresso',
            5 => 'Em Empera',
            6 => 'Resolvido',
            7 => 'Encaminhada para o setor de compras',
            8 => 'Aguardando aprovação',
            9 => 'Aguardando toner para troca',
            10 => 'Agendada com o solicitante',
            11 => 'Reserva em andamento',
            12 => 'Aguardando setor de manutenção',
            13 => 'Enviado para assistência técnica',
        ];
        return $arr[$this->status] ?? '';
    }

    public function getPriorityNameAttribute() {
        $arr = [
            1 => 'Baixa',
            2 => 'Média',
            3 => 'Alta',
            4 => 'Crítica',
        ];
        return $arr[$this->priority] ?? '';
    }

    public function getSectorNameAttribute() {
        $arr = [
            1 => 'RH',
            2 => 'Almoxarifado A',
            3 => 'Almoxarifado B',
            4 => 'Almoxarifado C',
            5 => 'Almoxaridado NF',
            6 => 'Recebimento',
            7 => 'Produção',
            8 => 'C.Q',
            9 => 'SESMT',
            10 => 'Laboratório',
            12 => 'Enfermaria',
            13 => 'Assistência Técnica',
            14 => 'Expedição',
            15 => 'Comercial',
            16 => 'Importação',
            17 => 'Compras',
            18 => 'Marketing',
            19 => 'Manutenção',
            20 => 'Jurídico',
            21 => 'Financeiro',
            22 => 'Engenharia',
            23 => 'Administração',
            24 => 'S.A.C',
            25 => 'P&D',
			26 => 'TI',
        ];
        return $arr[$this->sector] ?? '';
    }
}
