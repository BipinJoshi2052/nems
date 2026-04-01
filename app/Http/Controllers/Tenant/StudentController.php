<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Student;
use App\Models\Tenant\Enrollment;
use App\Core\Services\AuditService;
use App\Http\Traits\HandlesPaginationAndSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\HandlesAttachments;
use App\Enums\AttachmentFolderEnum;
use App\Models\Tenant\AttachmentFile;

class StudentController extends Controller
{
    use HandlesPaginationAndSearch, HandlesAttachments;

    public function __construct(protected AuditService $audit) {}

    public function index(Request $request)
    {
        $query = Student::query()->with(['enrollments.class', 'enrollments.academicYear', 'enrollments.section', 'section', 'thumbnail', 'original']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->class_id) {
            $query->whereHas('enrollments', fn($e) => $e->where('class_id', $request->class_id));
        }

        return $this->applyFiltersAndPaginate($query, $request, ['name', 'admission_no']);
    }

    public function show(Student $student)
    {
        return response()->json($student->load([
            'enrollments.class', 
            'enrollments.academicYear', 
            'enrollments.section',
            'section', 
            'thumbnail', 
            'original'
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'admission_no' => 'required|string|unique:students,admission_no',
            'class_id' => 'required|exists:classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'section_id' => 'required|exists:sections,id',
            'date_of_birth_ad' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'photo' => 'nullable|image|max:2048',
            'roll_no' => 'nullable|string',
        ]);

        return DB::transaction(function() use ($request) {
            $student = Student::create($request->only([
                'name', 'admission_no', 'date_of_birth_ad', 'date_of_birth_bs', 
                'gender', 'medical_notes', 'class_id', 'academic_year_id', 'section_id', 'roll_no'
            ]));

            if ($request->hasFile('photo')) {
                $this->handlePhotoUpload($student, $request->file('photo'), AttachmentFolderEnum::Photos);
            }

            Enrollment::create([
                'student_id' => $student->id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'academic_year_id' => $request->academic_year_id,
                'promotion_status' => 'pending',
            ]);

            $this->audit->record('create_student', 'Student', $student->id, null, $student->toArray());

            return response()->json(['message' => 'Student enrolled', 'student' => $student->load(['thumbnail', 'original'])], 201);
        });
    }

    public function update(Request $request, Student $student)
    {
        $oldValues = $student->toArray();
        $student->update($request->except(['photo']));

        if ($request->hasFile('photo')) {
            $this->handlePhotoUpload($student, $request->file('photo'), AttachmentFolderEnum::Photos);
        }
        
        if ($request->has('class_id')) {
            $student->enrollments()->updateOrCreate(
                ['academic_year_id' => $student->academic_year_id ?? $request->academic_year_id],
                ['class_id' => $request->class_id]
            );
        }

        $this->audit->record('update_student', 'Student', $student->id, $oldValues, $student->fresh()->toArray());

        return response()->json(['message' => 'Student updated', 'student' => $student->load(['thumbnail', 'original', 'enrollments.class'])]);
    }

    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => 'required|in:active,withdrawn,graduated',
            'reason' => 'required_if:status,withdrawn,graduated|string',
        ]);

        $oldStatus = $student->status;
        $student->update([
            'status' => $request->status,
            'withdrawal_reason' => $request->reason,
            'withdrawn_at' => in_array($request->status, ['withdrawn', 'graduated']) ? now() : null,
        ]);

        $this->audit->record('status_change', 'Student', $student->id, ['status' => $oldStatus], ['status' => $request->status, 'reason' => $request->reason]);

        return response()->json(['message' => 'Status updated']);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        $this->audit->record('delete_student', 'Student', $student->id);
        return response()->json(['message' => 'Student deleted']);
    }
}
