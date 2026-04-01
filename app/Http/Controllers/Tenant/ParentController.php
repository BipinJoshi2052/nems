<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ParentUser;
use App\Models\Tenant\Student;
use App\Core\Services\AuditService;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Traits\HandlesAttachments;
use App\Enums\AttachmentFolderEnum;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    use HandlesPaginationAndSearch, HandlesAttachments;

    public function __construct(protected AuditService $audit) {}

    public function index(Request $request)
    {
        return $this->applyFiltersAndPaginate(ParentUser::query(), $request, ['name', 'email', 'phone']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:parent_users,email',
            'phone' => 'nullable|string',
        ]);

        $parent = ParentUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make(Str::random(32)),
        ]);

        if ($request->hasFile('photo')) {
            $this->handlePhotoUpload($parent, $request->file('photo'), AttachmentFolderEnum::Photos);
        }

        $this->audit->record('create_parent', 'ParentUser', $parent->id, null, $parent->toArray());

        return response()->json(['message' => 'Parent created', 'parent' => $parent->load(['thumbnail', 'original'])], 201);
    }

    public function show(ParentUser $parent)
    {
        return response()->json($parent->load('students'));
    }

    public function update(Request $request, ParentUser $parent)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:parent_users,email,' . $parent->id,
            'phone' => 'nullable|string',
        ]);

        $oldData = $parent->toArray();
        $parent->update($request->only(['name', 'email', 'phone']));

        if ($request->hasFile('photo')) {
            $this->handlePhotoUpload($parent, $request->file('photo'), AttachmentFolderEnum::Photos);
        }
        
        $this->audit->record('update_parent', 'ParentUser', $parent->id, $oldData, $parent->toArray());

        return response()->json(['message' => 'Parent updated', 'parent' => $parent->load(['thumbnail', 'original'])]);
    }

    public function destroy(ParentUser $parent)
    {
        $parent->delete();
        $this->audit->record('delete_parent', 'ParentUser', $parent->id);
        return response()->json(['message' => 'Parent deleted']);
    }

    public function linkToStudent(Request $request, Student $student)
    {
        $request->validate([
            'parent_user_id' => 'required|exists:parent_users,id',
            'relationship' => 'required|string',
            'is_primary_contact' => 'required|boolean',
        ]);

        if ($request->is_primary_contact) {
            $student->parents()->updateExistingPivot(
                $student->parents()->pluck('parent_user_id'), 
                ['is_primary_contact' => false]
            );
        }

        $student->parents()->syncWithoutDetaching([
            $request->parent_user_id => [
                'relationship' => $request->relationship,
                'is_primary_contact' => $request->is_primary_contact,
            ]
        ]);

        $this->audit->record('link_parent', 'Student', $student->id, null, $request->all());

        return response()->json(['message' => 'Parent linked to student']);
    }
}
