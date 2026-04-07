<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions_count' => $role->permissions->count(),
                'permissions' => $role->permissions->pluck('name'),
                'created_at' => $role->created_at,
            ];
        });

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => \Illuminate\Support\Str::snake($request->name)
        ]);

        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return response()->json($role, 201);
    }

    public function update(Request $request, Role $role)
    {
        $request->merge([
            'name' => \Illuminate\Support\Str::snake($request->name)
        ]);

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        return response()->json($role);
    }

    public function destroy(Role $role)
    {
        // Prevent deleting core roles if necessary
        if (in_array($role->name, ['admin', 'teacher', 'student', 'parent'])) {
            return response()->json(['message' => 'Core roles cannot be deleted.'], 422);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully.']);
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json(['message' => 'Permissions updated successfully.']);
    }
}
