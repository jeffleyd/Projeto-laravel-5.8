<?php

namespace App\Model\Dynamic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectsUsersColumnsConfigs extends Model
{
    protected $table = "projects_users_columns_configs";
    protected $connection = "dynamic";
    protected $appends = [
        'column_name'
    ];

    public function projects_columns() {
        return $this->belongsTo(ProjectsColumns::class);
    }

    public function getColumnNameAttribute() {
        $ascii = Str::ascii($this->projects_columns->name);
        $ascii_slug = Str::slug($ascii, '_');

        return strtolower($ascii_slug);
    }
}
