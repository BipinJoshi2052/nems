<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformRole extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'platform_roles';
    protected $guarded = ['id'];
}
