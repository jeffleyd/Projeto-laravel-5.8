<?php


namespace App\Services\Dynamic;


use App\Model\Dynamic\Projects;
use App\Model\Dynamic\ProjectsColumns;
use App\Services\Dynamic\Traits\ProjectTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class Project extends ProccessColumn
{
    use ProjectTrait;

    const db_name = 'brdb';
    const connection_name = 'dynamic';
    const path = 'App\Model\Dynamic\brdb';

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Cria a estrutura inicial
     * @return bool
     * @throws \Exception
     * @return Project
     */
    public function start(): Projects
    {
        if (!$this->request->name)
            throw new \Exception("O nome é obrigatório para poder criar o projeto.");

        $project = new Projects;
        $project->r_code = Session::get('r_code');
        $project->name = $this->request->name;
        $project->description = $this->request->description;
        if ($this->request->r_codes_avalible)
            $project->r_codes_avalible = implode(',', $this->request->r_codes_avalible);
        $project->save();

        $this->createSchemaDB($project);
        $this->createSchemaFile($project);

        return $project;
    }

    /**
     * Remove todos os registros do projeto, essa ação é irreversível.
     * @param Projects $project
     * @return bool
     */
    public function remove(Projects $project): bool
    {
        $db_name = self::db_name.$project->id;
        unlink("..\\App\\Model\\Dynamic\\$db_name.php");
        Schema::connection(self::connection_name)->drop($db_name);
        $project->projects_columns()->delete();
        $project->projects_users_columns_configs()->delete();
        $project->delete();

        return true;
    }

    /**
     * Cria a tabela no banco de dados.
     * Tipos aceitos: text, integer, double
     * columns = [
     *      0 => [
     *          'name' => 'Nome',
     *          'type' => 'text',
     *          'required' => 'true',
     *          'after' => 'id', // Não é obrigatório
     *      ]
     * ]
     * @param Projects $project
     * @return bool
     */
    private function createSchemaDB(Projects $project): bool
    {
        $request = $this->request;
        Schema::connection(self::connection_name)->create(self::db_name.$project->id, function($table) use ($request)
        {
            $table->bigIncrements('id');
            foreach ($request->columns as $column) {
                    $this->addColumnSchema($table, $column);
            }
            $table->timestamps();
            $table->softDeletes();
        });

        foreach ($request->columns as $col) {
            $column = new ProjectsColumns;
            $column->projects_id = $project->id;
            $column->name = $col['name'];
            if ($this->request->r_codes_avalible)
                $column->r_codes_avalible = implode(',', $this->request->r_codes_avalible);
            $column->save();
        }

        return true;
    }

    /**
     * Cria a classe da tabela no diretório do model.
     * @param Projects $project
     * @return bool
     */
    private function createSchemaFile(Projects $project): bool
    {

        $db_name = self::db_name.$project->id;

        $schema = '<?php

namespace App\Model\Dynamic;

use Illuminate\Database\Eloquent\Model;

class '.$db_name.' extends Model
{
    protected $table = "'.$db_name.'";
    protected $connection = "dynamic";

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}';

        $table = fopen("..\\App\\Model\\Dynamic\\$db_name.php", "w");
        fwrite($table, $schema);
        fclose($table);

        return true;
    }

    /**
     * Aumenta a versão do projeto.
     * @param Projects $project
     * @return bool
     */
    public function updateVersion(Projects $project): bool
    {
        $project->increment('version');
        return true;
    }

}
