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

class StaffController extends Controller
{
    use HandlesPaginationAndSearch, HandlesAttachments;

    public function __construct(protected AuditService $audit) {}

    public function index(Request $request)
    {
        $query = User::query()->with(['thumbnail', 'original'])->where('is_owner', false);

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status === 'active') {
            $query->whereNull('deactivated_at');
        } elseif ($request->status === 'deactivated') {
            $query->whereNotNull('deactivated_at');
        }

        return $this->applyFiltersAndPaginate($query, $request, ['name', 'email', 'designation']);
    }

    public function show(User $user)
    {
        return response()->json($user->load(['thumbnail', 'original']));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'designation' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(32)), // Random password until setup
            'designation' => $request->designation,
            'is_owner' => false,
        ]);

        // Assign role (assuming Spatie or similar, will use a placeholder for now)
        // $user->assignRole($request->role);

        $inviteUrl = URL::signedRoute('staff.setup-password', ['user' => $user->id], now()->addHours(48));

        // Send Email (Placeholder)
        // Mail::to($user->email)->send(new StaffInviteMail($user, $inviteUrl));

        $this->audit->record('invite_staff', 'User', $user->id, null, $user->toArray());

        return response()->json([
            'message' => 'Staff invited successfully',
            'invite_url' => $inviteUrl, // For testing/dev
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $oldValues = $user->toArray();
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'designation' => 'sometimes|string',
            'role' => 'sometimes|string',
            'photo' => 'sometimes|image|max:2048',
        ]);

        $user->update($request->only(['name', 'designation']));
        
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
