<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AuditLog;
use App\Models\Campus;
use App\Models\College;
use App\Models\FinancialYear;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Student;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    public function index(): View
    {
        $campuses = Campus::with('colleges')->get();
        $colleges = College::with('campus')->get();
        $academicYears = AcademicYear::all();
        $financialYears = FinancialYear::all();
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $settings = SystemSetting::all();
        $auditLogs = AuditLog::with('user')->latest()->take(30)->get();

        return view('settings.index', compact(
            'campuses',
            'colleges',
            'academicYears',
            'financialYears',
            'roles',
            'permissions',
            'settings',
            'auditLogs'
        ));
    }

    public function storeCampus(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:campuses,code',
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'address' => 'nullable|string',
        ]);
        Campus::create($data);

        return back()->with('success', 'Campus added.');
    }

    public function storeCollege(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'code' => 'required|string|max:20|unique:colleges,code',
            'name' => 'required|string|max:255',
            'university_affiliation' => 'nullable|string|max:255',
            'aishe_code' => 'nullable|string|max:30',
            'naac_grade' => 'nullable|string|max:10',
            'principal_name' => 'nullable|string|max:255',
        ]);
        College::create($data);

        return back()->with('success', 'College unit added under university group.');
    }

    public function updateCollege(Request $request, College $college): RedirectResponse
    {
        $data = $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'code' => 'required|string|max:20|unique:colleges,code,' . $college->id,
            'name' => 'required|string|max:255',
            'university_affiliation' => 'nullable|string|max:255',
            'aishe_code' => 'nullable|string|max:30',
            'naac_grade' => 'nullable|string|max:10',
            'principal_name' => 'nullable|string|max:255',
        ]);

        $college->update($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'COLLEGE_UPDATED',
            'module' => 'Settings',
            'description' => "Updated college: {$college->name} ({$college->code})",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "College '{$college->name}' updated successfully.");
    }

    public function updateCampus(Request $request, Campus $campus): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:20|unique:campuses,code,' . $campus->id,
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'address' => 'nullable|string',
        ]);

        $campus->update($data);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'CAMPUS_UPDATED',
            'module' => 'Settings',
            'description' => "Updated campus: {$campus->name} ({$campus->code})",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Campus '{$campus->name}' updated successfully.");
    }

    public function updateSetting(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'key' => 'required|string|max:100',
            'group_name' => 'required|string|max:50',
            'value' => 'nullable|string',
        ]);

        SystemSetting::updateOrCreate(
            ['key' => $data['key']],
            ['group_name' => $data['group_name'], 'value' => $data['value']]
        );

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'SETTING_UPDATED',
            'module' => 'Settings',
            'description' => "Updated setting {$data['key']}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'System setting saved.');
    }

    public function exportStudentsCsv(): StreamedResponse
    {
        $students = Student::with(['course', 'department'])->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_export_' . date('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () use ($students) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student ID', 'Admission No', 'Full Name', 'Email', 'Phone', 'Course', 'Department', 'Semester', 'Category', 'Status']);
            foreach ($students as $s) {
                fputcsv($handle, [
                    $s->unique_student_id,
                    $s->admission_number,
                    $s->full_name,
                    $s->email,
                    $s->phone,
                    $s->course?->code,
                    $s->department?->code,
                    $s->current_semester,
                    $s->category,
                    $s->status,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    public function backupDatabase(): StreamedResponse
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'DB_BACKUP',
            'module' => 'Settings',
            'description' => 'Downloaded database snapshot JSON backup.',
            'ip_address' => request()->ip(),
        ]);

        $payload = [
            'exported_at' => now()->toIso8601String(),
            'campuses' => Campus::all(),
            'colleges' => College::all(),
            'students' => Student::all(),
            'settings' => SystemSetting::all(),
        ];

        return response()->streamDownload(function () use ($payload) {
            echo json_encode($payload, JSON_PRETTY_PRINT);
        }, 'college_erp_backup_' . date('Ymd_His') . '.json');
    }
}

