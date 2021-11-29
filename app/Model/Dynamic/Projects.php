<?php

namespace App\Model\Dynamic;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $table = "projects";
    protected $connection = "dynamic";

    public function projects_columns() {
        return $this->hasMany(ProjectsColumns::class);
    }

    public function projects_users_columns_configs() {
        return $this->hasMany(ProjectsUsersColumnsConfigs::class);
    }
}
