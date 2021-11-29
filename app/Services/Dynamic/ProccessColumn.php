<?php


namespace App\Services\Dynamic;


use App\Model\Dynamic\Projects;
use App\Model\Dynamic\ProjectsColumns;
use App\Model\Dynamic\ProjectsUsersColumnsConfigs;
use App\Services\Dynamic\Traits\ProjectTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProccessColumn extends ProccessCells
{
    use ProjectTrait;

    public function addColumnSchema($table, $column) {

        $name = $this->formatWordsForDB($column['name']);
        $type = $column['type'];
        $required = $column['required'];
        $after = isset($column['after']) ? $column['after'] : '';
        if ($type == 'double') {
            if ($required == 'true') {
                if ($after)
                    $table->double($name, '15', '2')->after($after);
                else
                    $table->double($name, '15', '2');
            } else {
                if ($after)
                    $table->double($name, '15', '2')->after($after)->nullable();
                else
                    $table->double($name, '15', '2')->nullable();
            }
        } else {
            if ($required == 'true') {
                if ($after)
                    $table->$type($name)->after($after);
                else
                    $table->$type($name);
            } else {
                if ($after)
                    $table->$type($name)->after($after)->nullable();
                else
                    $table->$type($name)->nullable();
            }
        }

        return $table;
    }

    /**
     * Cria uma nova coluna para o projeto
     * @param Projects $project
     * @throws \Exception
     * @return bool
     */
    public function createColumn(Projects $project): bool
    {
        $this->validateOwnerProject($project);
        $this->validateArrayWithColumns($this->request, 'name', 'type', 'required');

        DB::beginTransaction();
        $request = $this->request;
        Schema::connection(Project::connection_name)->table(Project::db_name.$project->id, function($table) use ($request) {
            foreach ($request->columns as $column) {
                $this->addColumnSchema($table, $column);
            }
        });

        foreach ($request->columns as $col) {
            $column = new ProjectsColumns;
            $column->projects_id = $project->id;
            $column->name = $col['name'];
            if ($this->request->r_codes_avalible)
                $column->r_codes_avalible = implode(',', $this->request->r_codes_avalible);
            $column->save();
        }

        $this->updateVersion($project);
        DB::commit();

        return true;
    }

    public function deleteColumn(Projects $project): bool
    {
        $this->validateOwnerProject($project);
        $this->validateArrayWithColumns($this->request, 'name');

        DB::beginTransaction();
        $request = $this->request;

        Schema::connection(Project::connection_name)->table(Project::db_name.$project->id, function($table) use ($request, $project)
        {
            foreach ($request->columns as $column) {
                if (Schema::connection(Project::connection_name)->hasColumn(Project::db_name.$project->id, $this->formatWordsForDB($column['name']))) {
                    $table->dropColumn($this->formatWordsForDB($column['name']));
                } else {
                    DB::rollBack();
                    throw new \Exception("Ocorreu um erro inesperado, essa coluna não existe.");
                }
            }
        });

        foreach ($request->columns as $col) {
            ProjectsColumns::where('projects_id', $project->id)->where('name', $col['name'])->delete();
        }

        $this->updateVersion($project);
        DB::commit();

        return true;
    }

    /**
     * Mostra um collection com as colunas disponíveis para visualização.
     * @param Projects $project
     * @return mixed
     */
    public function showColumns(Projects $project) {

        $hide_columns = $project->projects_users_columns_configs;
        $columns = $project->projects_columns;
        if (!$columns->count())
            throw new \Exception('Você ainda não adicionou colunas ao projeto.');

        if ($hide_columns->count()) {
            $h_columns = $hide_columns->pluck('projects_columns_id');
            $columns = $columns->whereNotIn('id', $h_columns);

            // Valida se as columns estão visiveis para esse colaborador.
            if ($project->r_code != $this->request->session()->get('r_code')) {
                $rest_columns = collect([]);
                foreach ($columns as $column) {
                    $get_r_codes = collect(explode(',', $column->r_codes_avaible));
                    foreach ($get_r_codes as $r_code) {
                        if ($r_code == $this->request->session()->get('r_code'))
                            $rest_columns->push($column->name);
                    }
                }
                $columns = $rest_columns;
            } else {
                $columns = $columns->pluck('name');
            }
        }

        return $columns;
    }

    /**
     * Atualizar a coluna para ser visivel ou não
     * @param Projects $project
     * @throws \Exception
     * @return bool
     */
    public function visibleColumn(Projects $project): bool
    {
        $this->validateOwnerProject($project);
        $this->validateArrayWithColumns($this->request, 'name', 'is_visible');

        DB::beginTransaction();
        foreach ($this->request->columns as $col) {

            $column = ProjectsColumns::where('projects_id', $project->id)
                ->where('name', $col['name'])
                ->first();

            if (!$column)
                throw new \Exception("Ocorreu um erro inesperado, essa coluna não existe.");

            $column_conf = ProjectsUsersColumnsConfigs::where('projects_columns_id', $column->id)
                ->where('projects_id', $project->id)
                ->where('r_code', $this->request->session()->get('r_code'))
                ->first();

            if (!$column_conf)
                $column_conf = new ProjectsUsersColumnsConfigs;

            $column_conf->projects_id = $project->id;
            $column_conf->r_code = $this->request->session()->get('r_code');
            $column_conf->projects_columns_id = $column->id;
            $column_conf->is_visible = $col['is_visible'];
            $column_conf->save();
        }
        DB::commit();

        return true;
    }
}
