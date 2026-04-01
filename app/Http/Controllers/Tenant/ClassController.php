<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ClassModel;
use App\Models\Tenant\Subject;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    use HandlesPaginationAndSearch;

    public function index(Request $request)
    {
        $query = ClassModel::query()->with(['academicYear']);
        
        if ($request->academic_year_id) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        return $this->applyFiltersAndPaginate($query, $request, ['name']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_ids' => 'sometimes|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $class = ClassModel::create($request->only(['name', 'academic_year_id']));

        return response()->json(['message' => 'Class created', 'class' => $class], 201);
    }

    public function show(ClassModel $class)
    {
        return response()->json($class->load(['academicYear']));
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'subject_ids' => 'sometimes|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $class->update($request->only(['name']));

        return response()->json(['message' => 'Class updated', 'class' => $class]);
    }

    public function destroy(ClassModel $class)
    {
        // Check if students are enrolled
        if ($class->enrollments()->exists()) {
            return response()->json(['message' => 'Cannot delete class with enrolled students'], 422);
        }

        $class->delete();
        return response()->json(['message' => 'Class deleted']);
    }
}
