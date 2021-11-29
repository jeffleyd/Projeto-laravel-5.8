<?php

namespace App\Exports;

use App\Model\Task;
use App\Model\TaskHistory;
use Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class ProjectExport implements FromQuery, WithHeadings, WithMapping
{

    use Exportable;

    public function __construct($r_code, $status, Request $request)
    {
        $this->r_code = $r_code;
        $this->status = $status;
        $this->request = $request;
    }

    public function query()
    {
        if (hasPermApprov(3)) {
            $task = Task::leftJoin('sector', 'task.sector_id', '=', 'sector.id')
                    ->leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')
                    ->leftJoin('users', 'task_responsible.r_code', '=', 'users.r_code')
                    ->select('task.*', 'sector.name as sector_name', 'users.r_code as users_r_code', 'users.first_name', 'users.last_name')
                    ->where('task.r_code', $this->request->session()->get('r_code'))
                    ->groupBy('task.id');

            if (!empty($this->r_code)) {
                $task->where('task_responsible.r_code', $this->r_code);
            }

        } else {
            $task = Task::leftJoin('sector', 'task.sector_id', '=', 'sector.id')
                    ->leftJoin('task_responsible', 'task.id', '=', 'task_responsible.task_id')
                    ->leftJoin('users', 'task.r_code', '=', 'users.r_code')
                    ->select('task.*', 'sector.name as sector_name', 'users.r_code as users_r_code', 'users.first_name', 'users.last_name')
                    ->where('task_responsible.r_code', $this->request->session()->get('r_code'))
                    ->groupBy('task.id');
        }
        
        if (!empty($this->status)) {
            if ($this->status == 1) {
                $task->where('task.is_completed', 1);
            } else if ($this->status == 2) {
                $task->where('task.start_date', '<=', date('Y-m-d'))
                    ->where('task.is_accept', 1);
            } else if ($this->status == 3) {
                $task->where('task.end_date', '<=', date('Y-m-d'))
                    ->where('task.is_accept', 1);
            } else if ($this->status == 4) {
                $task->where('task.is_accept', 1);
            } else if ($this->status == 5) {
                $task->where('task.is_recuse', 1);
            } else if ($this->status == 6) {
                $task->where('task.is_cancelled', 1);
            } else if ($this->status == 7) {
                $task->where('task.has_analyze', 1);
            } else if ($this->status == 8) {
                $task->where('task.has_analyze', 1)
                    ->where('task.is_accept', 1)
                    ->where('task.is_completed', 0);
            } 
        }

        return $task;
    }

    /**
    * @var Task $task
    */
    public function map($task): array
    {
        $status = 0;
        if ($task->is_completed == 1) {
            $status = __('project_i.ee_11');
        } else if ($task->has_analyze == 1 and $task->is_accept == 1 and $task->is_completed == 0) {
            $status = __('project_i.ee_12');
        } else if ($task->is_accept == 1 and date('Y-m-d') >= date('Y-m-d', strtotime($task->end_date))) {
            $status = __('project_i.ee_13');
        } else if ($task->is_accept == 1 and date('Y-m-d') >= date('Y-m-d', strtotime($task->start_date))) {
            $status = __('project_i.ee_14');
        } else if ($task->is_accept == 1) {
            $status = __('project_i.ee_15');
        } else if ($task->is_recuse == 1) {
            $status = __('project_i.ee_16');
        } else if ($task->has_analyze == 1) {
            $status = __('project_i.ee_17');
        }

        return [
            [
                $task->id,
                $task->users_r_code,
                $task->first_name .' '. $task->last_name,
                date('Y-m-d', strtotime($task->start_date)),
                date('Y-m-d', strtotime($task->end_date)),
                sectorName($task->sector_id),
                $task->title,
                $task->description,
                $task->attach,
                $status,
            ],
        ];
    }

    public function headings(): array
    {
        return [
            '#ID',
            __('project_i.ee_1'),
            __('project_i.ee_2'),
            __('project_i.ee_3'),
            __('project_i.ee_4'),
            __('project_i.ee_5'),
            __('project_i.ee_6'),
            __('project_i.ee_7'),
            __('project_i.ee_8'),
            __('project_i.ee_10'),
        ];
    }
}
