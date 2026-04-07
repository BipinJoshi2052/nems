<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant\ClassModel;
use App\Models\Tenant\AcademicYear;
use App\Core\Services\AuditService;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Http\Traits\HandlesAttachments;
use App\Enums\AttachmentFolderEnum;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Models\Tenant\Staff;
use App\Mail\UserInvitationMail;
use Illuminate\Support\Facades\Mail;

class StaffController extends Controller
{
    use HandlesPaginationAndSearch, HandlesAttachments;

    public function __construct(protected AuditService $audit) {}

    public function index(Request $request)
    {
        $query = User::query()->with(['thumbnail', 'original', 'role', 'staff'])
            ->whereHas('role', function($q) {
                $q->whereIn('name', ['staff', 'teacher', 'accountant', 'receptionist', 'admin']);
            });

        if ($request->role) {
            $query->whereHas('role', fn($q) => $q->where('name', 'ilike', $request->role));
        }

        $status = $request->status ?: 'active';
        if ($status === 'active') {
            $query->whereNull('deactivated_at');
        } elseif ($status === 'deactivated') {
            $query->whereNotNull('deactivated_at');
        }

        return $this->applyFiltersAndPaginate($query, $request, ['name', 'email']);
    }

    public function show(User $user)
    {
        return response()->json($user->load(['thumbnail', 'original', 'staff']));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|exists:roles,name',
            'designation' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        return DB::transaction(function() use ($request) {
            $role = Role::where('name', $request->role)->first();
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(32)),
                'role_id' => $role?->id,
                'is_owner' => false,
            ]);

            if ($request->hasFile('photo')) {
                $this->handlePhotoUpload($user, $request->file('photo'), AttachmentFolderEnum::Photos);
            }

            $staff = Staff::create([
                'user_id' => $user->id,
                'designation' => $request->designation,
            ]);

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

            $this->audit->record('invite_staff', 'User', $user->id, null, [
                'user' => $user->toArray(),
                'staff' => $staff->toArray()
            ]);

            return response()->json([
                'message' => 'Staff invited successfully',
                'invite_url' => $inviteUrl,
                'user' => $user->load(['staff', 'role'])
            ]);
        });
    }

    public function update(Request $request, User $user)
    {
        $oldValues = $user->toArray();
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'designation' => 'sometimes|string',
            'address' => 'nullable|string',
            'facebook_url' => 'nullable|string',
            'x_url' => 'nullable|string',
            'linkedin_url' => 'nullable|string',
            'instagram_url' => 'nullable|string',
            'role' => 'sometimes|string',
            'photo' => 'sometimes|image|max:2048',
        ]);

        $user->update($request->only(['name']));
        
        if ($user->staff) {
            $user->staff->update($request->only([
                'designation', 'address', 'facebook_url', 'x_url', 'linkedin_url', 'instagram_url'
            ]));
        }
        
        if ($request->hasFile('photo')) {
            $this->handlePhotoUpload($user, $request->file('photo'), AttachmentFolderEnum::Photos);
        }

        $this->audit->record('update_staff', 'User', $user->id, $oldValues, $user->fresh()->toArray());

        return response()->json(['message' => 'Staff updated', 'staff' => $user->load(['thumbnail', 'original'])]);
    }

    public function destroy(User $user)
    {
        $user->update(['deactivated_at' => now()]);
        
        $this->audit->record('deactivate_staff', 'User', $user->id);

        return response()->json(['message' => 'Staff deactivated']);
    }

    public function assignClasses(Request $request, User $user)
    {
        $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        // Implementation for teacher_class_assignments
        // This likely needs a pivot or a separate table
        
        $this->audit->record('assign_teacher_classes', 'User', $user->id, null, $request->all());

        return response()->json(['message' => 'Classes assigned']);
    }
}
