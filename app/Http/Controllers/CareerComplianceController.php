<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\DisciplinaryRecord;
use App\Models\Grievance;
use App\Models\PlacementApplication;
use App\Models\PlacementCompany;
use App\Models\PlacementDrive;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CareerComplianceController extends Controller
{
    // Module 21: Placement & Career
    public function placements(): View
    {
        $companies = PlacementCompany::all();
        $drives = PlacementDrive::with('company')->latest()->get();
        $applications = PlacementApplication::with(['drive.company', 'student.course'])->latest()->get();
        $students = Student::where('status', 'Active')->get();

        return view('placements.index', compact('companies', 'drives', 'applications', 'students'));
    }

    public function storeDrive(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'placement_company_id' => 'required|exists:placement_companies,id',
            'job_title' => 'required|string|max:255',
            'package_lpa' => 'required|numeric|min:1',
            'eligibility_cgpa' => 'required|numeric|min:0|max:10',
            'drive_date' => 'required|date',
            'drive_type' => 'required|in:Full-Time,Internship',
        ]);
        PlacementDrive::create($data);

        return back()->with('success', 'Placement drive scheduled.');
    }

    public function applyDrive(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'placement_drive_id' => 'required|exists:placement_drives,id',
            'student_id' => 'required|exists:students,id',
            'resume_url' => 'nullable|string|max:255',
        ]);
        PlacementApplication::create($data);

        return back()->with('success', 'Student nominated for placement drive.');
    }

    public function updatePlacementStatus(Request $request, PlacementApplication $application): RedirectResponse
    {
        $status = $request->validate(['status' => 'required|in:Applied,Shortlisted,Interviewed,Selected,Rejected'])['status'];
        $application->update(['status' => $status]);

        return back()->with('success', "Placement status updated to {$status}.");
    }

    // Module 22: Scholarships & Financial Aid
    public function scholarships(): View
    {
        $scholarships = Scholarship::with('academicSession')->get();
        $applications = ScholarshipApplication::with(['scholarship', 'student.course'])->latest()->get();
        $students = Student::where('status', 'Active')->get();

        return view('scholarships.index', compact('scholarships', 'applications', 'students'));
    }

    public function applyScholarship(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'student_id' => 'required|exists:students,id',
            'remarks' => 'nullable|string',
        ]);
        ScholarshipApplication::create($data);

        return back()->with('success', 'Scholarship application submitted.');
    }

    public function updateScholarshipStatus(Request $request, ScholarshipApplication $application): RedirectResponse
    {
        $status = $request->validate(['status' => 'required|in:Applied,Verified,Approved,Disbursed,Rejected'])['status'];
        $disbursed = $status === 'Disbursed' ? $application->scholarship->amount : $application->disbursed_amount;
        $application->update([
            'status' => $status,
            'disbursed_amount' => $disbursed,
            'disbursed_at' => $status === 'Disbursed' ? now()->toDateString() : $application->disbursed_at,
        ]);

        return back()->with('success', "Scholarship application {$status}.");
    }

    // Module 23: Certificates & QR Verification
    public function certificates(): View
    {
        $certificates = Certificate::with('student.course')->latest()->get();
        $students = Student::all();

        return view('certificates.index', compact('certificates', 'students'));
    }

    public function issueCertificate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'certificate_type' => 'required|in:Bonafide,Transfer,Migration,Character,NOC,Course Completion,Provisional,Degree,Duplicate',
            'remarks' => 'nullable|string',
        ]);

        $cert = Certificate::create([
            'certificate_no' => 'CERT-' . strtoupper(substr($data['certificate_type'], 0, 3)) . '-' . date('Y') . '-' . rand(100, 999),
            'student_id' => $data['student_id'],
            'certificate_type' => $data['certificate_type'],
            'issue_date' => now()->toDateString(),
            'status' => 'Issued',
            'verification_token' => Str::random(32),
            'remarks' => $data['remarks'] ?? null,
        ]);

        return back()->with('success', "Digital certificate {$cert->certificate_no} issued with QR verification.");
    }

    public function printCertificate(Certificate $certificate): View
    {
        $certificate->load('student.course.department.college.campus');

        return view('certificates.print', compact('certificate'));
    }

    public function verifyCertificate(string $token): View
    {
        $certificate = Certificate::with('student.course.department.college')->where('verification_token', $token)->first();

        return view('certificates.verify', compact('certificate', 'token'));
    }

    // Module 24: Grievance & Discipline
    public function grievances(): View
    {
        $grievances = Grievance::with(['student', 'assignee'])->latest()->get();
        $disciplinary = DisciplinaryRecord::with('student')->latest()->get();
        $students = Student::all();

        return view('grievances.index', compact('grievances', 'disciplinary', 'students'));
    }

    public function storeGrievance(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => 'nullable|exists:students,id',
            'category' => 'required|in:Academic,Anti-Ragging,Harassment,Hostel,Fee,General',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        $data['ticket_no'] = 'GRV-' . date('Y') . '-' . rand(100, 999);
        $data['is_confidential'] = $request->boolean('is_confidential');
        Grievance::create($data);

        return back()->with('success', "Grievance {$data['ticket_no']} logged.");
    }

    public function resolveGrievance(Request $request, Grievance $grievance): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:Under Review,Resolved,Closed',
            'resolution_notes' => 'nullable|string',
        ]);
        $grievance->update($data);

        return back()->with('success', 'Grievance ticket updated.');
    }

    public function storeDisciplinary(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'incident_date' => 'required|date',
            'description' => 'required|string',
            'action_taken' => 'required|string|max:255',
            'counselling_notes' => 'nullable|string',
        ]);
        DisciplinaryRecord::create($data);

        return back()->with('success', 'Disciplinary & counselling record logged.');
    }
}

