<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // Reusing Spatie Role but within Tenant namespace if needed, 
    // or just use Spatie's directly. The user wants to link to this.
}
