<?php

namespace App\Http\Controllers;

use App\Models\AttendanceCorrection;
use App\Models\ClassSection;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\NotificationLog;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date', now()->toDateString());
        $sections = ClassSection::with('course')->get();
        $subjects = Subject::all();
        $students = Student::with(['course', 'attendances'])->where('status', 'Active')->get();

        $studentSummary = $students->map(function ($stu) {
            $total = $stu->attendances->count();
            $present = $stu->attendances->whereIn('status', ['Present', 'Late'])->count();
            $pct = $total > 0 ? round(($present / $total) * 100, 1) : 100;

            return [
                'student' => $stu,
                'total' => $total,
                'present' => $present,
                'percentage' => $pct,
                'shortage' => $pct < 75.0,
            ];
        });

        $shortageList = $studentSummary->where('shortage', true);
        $employeeAttendances = EmployeeAttendance::with('employee')->whereDate('attendance_date', $date)->get();
        $employees = Employee::where('status', 'Active')->get();
        $corrections = AttendanceCorrection::with('user')->latest()->get();

        return view('attendance.index', compact(
            'date',
            'sections',
            'subjects',
            'students',
            'studentSummary',
            'shortageList',
            'employeeAttendances',
            'employees',
            'corrections'
        ));
    }

    public function markStudentAttendance(Request $request): RedirectResponse
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_section_id' => 'nullable|exists:class_sections,id',
            'method' => 'required|in:Manual,QR,Biometric,RFID',
            'statuses' => 'required|array',
        ]);

        foreach ($request->statuses as $studentId => $status) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $request->subject_id,
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'class_section_id' => $request->class_section_id,
                    'status' => $status,
                    'method' => $request->method,
                    'marked_by' => Auth::id(),
                ]
            );
        }

        return back()->with('success', 'Student attendance recorded via ' . $request->method . '.');
    }

    public function markEmployeeAttendance(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:Present,Absent,Late,Half Day,On Leave',
            'method' => 'required|in:Manual,Biometric,RFID',
        ]);

        EmployeeAttendance::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'attendance_date' => $data['attendance_date']],
            $data
        );

        return back()->with('success', 'Faculty/Staff attendance logged.');
    }

    public function sendShortageAlerts(): RedirectResponse
    {
        $students = Student::with(['attendances', 'guardians'])->where('status', 'Active')->get();
        $count = 0;

        foreach ($students as $stu) {
            $total = $stu->attendances->count();
            $present = $stu->attendances->whereIn('status', ['Present', 'Late'])->count();
            $pct = $total > 0 ? round(($present / $total) * 100, 1) : 100;

            if ($pct < 75.0) {
                $guardian = $stu->guardians->first();
                NotificationLog::create([
                    'recipient_name' => $guardian?->name ?? $stu->full_name,
                    'recipient_contact' => $guardian?->phone ?? $stu->phone,
                    'channel' => 'SMS',
                    'subject' => 'Attendance Shortage Alert (<75%)',
                    'message' => "Alert: {$stu->full_name} ({$stu->unique_student_id}) has {$pct}% attendance. Minimum 75% required for End-Sem Exams.",
                    'status' => 'Sent',
                    'sent_at' => now(),
                ]);
                $count++;
            }
        }

        return back()->with('success', "Attendance shortage alerts sent to {$count} students/parents.");
    }

    public function updateCorrection(Request $request, AttendanceCorrection $correction): RedirectResponse
    {
        $status = $request->validate(['status' => 'required|in:Approved,Rejected'])['status'];
        $correction->update(['status' => $status]);

        return back()->with('success', "Correction request {$status}.");
    }
}

