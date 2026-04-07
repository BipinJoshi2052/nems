<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'module' => $permission->module ?: 'general',
                'action' => $permission->action ?: 'view',
                'guard_name' => $permission->guard_name,
            ];
        });

        // Group by module for the UI
        $grouped = $permissions->groupBy('module');

        return response()->json($grouped);
    }

    public function store(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'actions' => 'required|array',
            'actions.*' => 'string',
        ]);

        $created = [];
        $errors = [];

        foreach ($request->actions as $action) {
            $name = strtolower($request->module . '.' . $action);

            // Check if name already exists
            if (Permission::where('name', $name)->exists()) {
                $errors[] = "$name already exists.";
                continue;
            }

            $created[] = Permission::create([
                'name' => $name,
                'module' => strtolower($request->module),
                'action' => strtolower($action),
                'guard_name' => 'web'
            ]);
        }

        if (count($created) === 0 && count($errors) > 0) {
            return response()->json(['message' => implode(' ', $errors)], 422);
        }

        return response()->json([
            'message' => count($created) . ' permissions created successfully.',
            'created' => $created,
            'errors' => $errors
        ], 201);
    }
}
