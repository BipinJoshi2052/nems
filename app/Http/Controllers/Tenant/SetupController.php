<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant\AcademicYear;
use App\Models\Tenant\Subject;
use App\Models\Tenant\ClassModel;
use App\Models\Tenant\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    /**
     * Get current setup progress.
     */
    public function getProgress()
    {
        $progress = DB::table('settings')->where('key', 'onboarding_progress')->value('value');
        
        return response()->json(json_decode($progress ?? '{}', true));
    }

    /**
     * Save wizard progress.
     */
    public function saveProgress(Request $request)
    {
        $data = $request->all();
        
        DB::table('settings')->updateOrInsert(
            ['key' => 'onboarding_progress'],
            ['value' => json_encode($data), 'updated_at' => now()]
        );

        return response()->json(['message' => 'Progress saved']);
    }

    /**
     * Step 1: School Info
     */
    public function saveSchoolInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'pan' => 'nullable|string',
            'vat_enabled' => 'nullable', // Allow any value, handle cast below
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['vat_enabled'] = filter_var($request->input('vat_enabled'), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('school', 'public');
            $validated['logo_url'] = '/storage/' . $path;
        }

        DB::table('settings')->updateOrInsert(
            ['key' => 'school_info'],
            ['value' => json_encode($validated), 'updated_at' => now()]
        );

        return response()->json(['message' => 'School info saved']);
    }

    /**
     * Step 3: Subjects
     */
    public function saveSubjects(Request $request)
    {
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'required|string|max:255',
        ]);

        foreach ($validated['subjects'] as $name) {
            Subject::updateOrCreate(['name' => $name], []);
        }

        return response()->json(['message' => 'Subjects saved']);
    }

    /**
     * Step 4: Classes
     */
    public function saveClasses(Request $request)
    {
        $validated = $request->validate([
            'classes' => 'required|array',
            'classes.*' => 'required|string|max:255',
        ]);

        // Get active academic year
        $academicYearId = AcademicYear::where('is_active', true)->value('id') 
            ?? AcademicYear::latest()->value('id');

        if (! $academicYearId) {
            return response()->json(['message' => 'Please setup academic year first'], 422);
        }

        foreach ($validated['classes'] as $name) {
            ClassModel::updateOrCreate(
                ['name' => $name, 'academic_year_id' => $academicYearId],
                []
            );
        }

        return response()->json(['message' => 'Classes saved']);
    }

    /**
     * Step 5: Invite Teachers
     */
    public function inviteTeachers(Request $request)
    {
        $validated = $request->validate([
            'emails' => 'array',
            'emails.*' => 'email',
        ]);

        // In a real app, send invitation emails here
        
        return response()->json(['message' => 'Invitations sent']);
    }

    /**
     * Step 6: Add First Student
     */
    public function saveFirstStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'admission_no' => 'required|string|max:50',
            'class' => 'required|string',
        ]);

        $academicYearId = AcademicYear::where('is_active', true)->value('id');
        $classId = ClassModel::where('name', $validated['class'])->value('id');

        if (! $classId || ! $academicYearId) {
            return response()->json(['message' => 'Class or Academic Year not found'], 422);
        }

        Student::updateOrCreate(
            ['admission_no' => $validated['admission_no']],
            [
                'name' => $validated['name'],
                'class_id' => $classId,
                'academic_year_id' => $academicYearId,
            ]
        );

        return response()->json(['message' => 'Student added']);
    }

    /**
     * Complete Setup
     */
    public function completeSetup(Request $request)
    {
        $user = $request->user();
        
        // Update user setup status
        User::where('id', $user->id)->update(['is_setup_complete' => true]);

        return response()->json(['message' => 'Setup completed']);
    }
}
