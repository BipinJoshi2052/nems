<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Subject;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use HandlesPaginationAndSearch;

    public function index(Request $request)
    {
        $query = Subject::query();
        return $this->applyFiltersAndPaginate($query, $request, ['name', 'code']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'sometimes|string|unique:subjects,code',
            'credit_hours' => 'sometimes|numeric',
        ]);

        $subject = Subject::create($validated);

        return response()->json(['message' => 'Subject created', 'subject' => $subject], 201);
    }

    public function show(Subject $subject)
    {
        return response()->json($subject);
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => "sometimes|string|unique:subjects,code,{$subject->id}",
            'credit_hours' => 'sometimes|numeric',
        ]);

        $subject->update($validated);

        return response()->json(['message' => 'Subject updated', 'subject' => $subject]);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['message' => 'Subject deleted']);
    }
}
