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
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Mail\UserInvitationMail;
use Illuminate\Support\Facades\Mail;

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
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'students' => 'nullable|array',
            'students.*.id' => 'exists:students,id',
            'students.*.relationship' => 'required|string',
        ]);

        return DB::transaction(function() use ($request) {
            $role = Role::where('name', 'parent')->first();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(32)),
                'role_id' => $role?->id,
            ]);

            $parent = ParentUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make(Str::random(32)),
                'user_id' => $user->id,
            ]);

            if ($request->hasFile('photo')) {
                $this->handlePhotoUpload($parent, $request->file('photo'), AttachmentFolderEnum::Photos);
                $user->update([
                    'thumbnail_id' => $parent->thumbnail_id,
                    'original_id' => $parent->original_id,
                ]);
            }

            if ($request->students) {
                $syncData = [];
                foreach ($request->students as $student) {
                    $syncData[$student['id']] = [
                        'relationship' => $student['relationship'],
                        'is_primary_contact' => $student['is_primary_contact'] ?? false
                    ];
                }
                $parent->students()->sync($syncData);
            }

            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            $inviteUrl = route('users.setup-password', ['token' => $token, 'email' => $user->email]);

            Mail::to($user->email)->queue(new UserInvitationMail($user->name, $inviteUrl, tenant('name')));

            $this->audit->record('create_parent', 'ParentUser', $parent->id, null, $parent->toArray());

            return response()->json([
                'message' => 'Parent created', 
                'invite_url' => $inviteUrl,
                'parent' => $parent->load(['thumbnail', 'original', 'students'])
            ], 201);
        });
    }

    public function show(ParentUser $parent)
    {
        return response()->json($parent->load([
            'students.user',
            'students.enrollments.class', 
            'students.enrollments.section', 
            'students.thumbnail',
            'thumbnail',
            'original',
        ]));
    }

    public function update(Request $request, ParentUser $parent)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:parent_users,email,' . $parent->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'facebook_url' => 'nullable|string',
            'x_url' => 'nullable|string',
            'linkedin_url' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'students' => 'nullable|array',
            'students.*.id' => 'exists:students,id',
            'students.*.relationship' => 'required|string',
        ]);

        $oldData = $parent->toArray();
        $parent->update($request->only([
            'name', 'email', 'phone', 'address', 'facebook_url', 'x_url', 'linkedin_url', 'instagram_url'
        ]));

        if ($request->hasFile('photo')) {
            $this->handlePhotoUpload($parent, $request->file('photo'), AttachmentFolderEnum::Photos);
        }
        
        if ($request->has('students')) {
            $syncData = [];
            foreach ($request->students as $student) {
                $syncData[$student['id']] = [
                    'relationship' => $student['relationship'],
                    'is_primary_contact' => $student['is_primary_contact'] ?? false
                ];
            }
            $parent->students()->sync($syncData);
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
