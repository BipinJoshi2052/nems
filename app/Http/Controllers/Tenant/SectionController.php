<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Section;
use App\Models\Tenant\ClassModel;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    use HandlesPaginationAndSearch;

    public function index(Request $request)
    {
        $query = Section::query();
        
        return $this->applyFiltersAndPaginate($query, $request, ['name']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $section = Section::create($validated);

        return response()->json(['message' => 'Section created', 'section' => $section], 201);
    }

    public function show(Section $section)
    {
        return response()->json($section);
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $section->update($validated);

        return response()->json(['message' => 'Section updated', 'section' => $section]);
    }

    public function destroy(Section $section)
    {
        if ($section->students()->exists()) {
            return response()->json(['message' => 'Cannot delete section with students'], 422);
        }

        $section->delete();
        return response()->json(['message' => 'Section deleted']);
    }
}
