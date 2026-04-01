<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\AcademicYear;
use App\Models\Tenant\ClassModel;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    use HandlesPaginationAndSearch;

    public function index(Request $request)
    {
        $query = AcademicYear::query();
        return $this->applyFiltersAndPaginate($query, $request, ['name']);
    }

    public function show(AcademicYear $academicYear)
    {
        return response()->json($academicYear->load('classes'));
    }

    /**
     * Validate academic year data.
     */
    public function validateData(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'terms' => 'required|integer|min:1',
        ]);

        return response()->json(['message' => 'Valid data']);
    }

    /**
     * Create a new academic year.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'terms' => 'required|integer|min:1',
            'classes' => 'sometimes|array',
        ]);

        return DB::transaction(function () use ($validated) {
            // Deactivate other years if this is set as active
            // For simplicity, we just create it as inactive if others exist, or user choice
            // AcademicYear::where('is_active', true)->update(['is_active' => false]);

            $academicYear = AcademicYear::create([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'terms' => $validated['terms'],
                'is_active' => $validated['is_active'] ?? false,
            ]);

            if (isset($validated['classes'])) {
                foreach ($validated['classes'] as $cls) {
                    ClassModel::create([
                        'name' => $cls['name'],
                        'academic_year_id' => $academicYear->id,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Academic year created successfully',
                'id' => $academicYear->id,
                'academic_year' => $academicYear
            ]);
        });
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['is_active']) && $validated['is_active']) {
            AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
        }

        $academicYear->update($validated);

        return response()->json(['message' => 'Academic year updated', 'academic_year' => $academicYear]);
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->is_active) {
            return response()->json(['message' => 'Cannot delete active academic year'], 422);
        }

        $academicYear->delete();
        return response()->json(['message' => 'Academic year deleted']);
    }

    /**
     * Get classes from previous year.
     */
    public function previousClasses()
    {
        $lastYear = AcademicYear::latest()->first();
        if (! $lastYear) return response()->json([]);

        $classes = ClassModel::where('academic_year_id', $lastYear->id)->get();
        return response()->json($classes);
    }

    /**
     * Get previous assignments placeholder.
     */
    public function previousAssignments()
    {
        return response()->json([]);
    }
}
