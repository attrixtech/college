<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\AdmissionApplication;
use App\Models\AdmissionEnquiry;
use App\Models\Course;
use App\Models\EnquiryFollowup;
use App\Models\FeeStructure;
use App\Models\NotificationLog;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentFeeAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    // Public Online Admission Portal
    public function publicApply(): View
    {
        $courses = Course::with('department')->where('is_active', true)->get();
        $sessions = AcademicSession::where('is_active', true)->get();

        return view('admissions.public_apply', compact('courses', 'sessions'));
    }

    public function submitPublicApplication(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'academic_session_id' => 'nullable|exists:academic_sessions,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'required|in:Male,Female,Other',
            'category' => 'required|in:General,OBC,SC,ST,EWS',
            'aadhaar_number' => 'nullable|digits:12',
            'aadhaar_consent' => 'nullable|boolean',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'class_10_board' => 'nullable|string|max:80',
            'class_10_percentage' => 'nullable|numeric|min:0|max:100',
            'class_12_board' => 'nullable|string|max:80',
            'class_12_percentage' => 'nullable|numeric|min:0|max:100',
            'previous_qualification' => 'nullable|string|max:255',
            'entrance_exam_name' => 'nullable|string|max:80',
            'entrance_score' => 'nullable|numeric|min:0|max:100',
            'photo' => 'nullable|file|max:2048',
            'signature' => 'nullable|file|max:2048',
        ]);

        $appNo = 'APP-' . date('Y') . '-' . rand(1000, 9999);
        $meritScore = round((($data['class_12_percentage'] ?? 60) * 0.5) + (($data['entrance_score'] ?? ($data['class_12_percentage'] ?? 60)) * 0.5), 2);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('admissions/photos', 'public') : null;
        $sigPath = $request->hasFile('signature') ? $request->file('signature')->store('admissions/signatures', 'public') : null;

        $app = AdmissionApplication::create([
            'application_no' => $appNo,
            'academic_session_id' => $data['academic_session_id'] ?? AcademicSession::where('is_active', true)->value('id'),
            'course_id' => $data['course_id'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'],
            'category' => $data['category'],
            'aadhaar_number' => $data['aadhaar_number'] ?? null,
            'aadhaar_consent' => (bool) ($data['aadhaar_consent'] ?? false),
            'father_name' => $data['father_name'] ?? null,
            'mother_name' => $data['mother_name'] ?? null,
            'address' => $data['address'] ?? null,
            'class_10_board' => $data['class_10_board'] ?? null,
            'class_10_percentage' => $data['class_10_percentage'] ?? null,
            'class_12_board' => $data['class_12_board'] ?? null,
            'class_12_percentage' => $data['class_12_percentage'] ?? null,
            'previous_qualification' => $data['previous_qualification'] ?? null,
            'entrance_exam_name' => $data['entrance_exam_name'] ?? null,
            'entrance_score' => $data['entrance_score'] ?? null,
            'merit_score' => $meritScore,
            'photo_path' => $photoPath,
            'signature_path' => $sigPath,
            'application_fee_status' => 'Paid',
            'payment_txn_id' => 'UPI-' . strtoupper(substr(md5($appNo), 0, 10)),
            'status' => 'Submitted',
        ]);

        NotificationLog::create([
            'recipient_name' => $app->full_name,
            'recipient_contact' => $app->phone,
            'channel' => 'WhatsApp',
            'subject' => 'Application Received: ' . $app->application_no,
            'message' => "Dear {$app->full_name}, your application {$app->application_no} is submitted successfully.",
            'status' => 'Sent',
            'sent_at' => now(),
        ]);

        return redirect()->route('admission.track', ['application_no' => $app->application_no])
            ->with('success', "Application submitted! Your Application Number is {$app->application_no}.");
    }

    public function trackApplication(Request $request): View
    {
        $application = null;
        if ($request->filled('application_no')) {
            $application = AdmissionApplication::with('course.department')
                ->where('application_no', trim($request->application_no))
                ->first();
        }

        return view('admissions.track', compact('application'));
    }

    // Admin Admission Management
    public function index(Request $request): View
    {
        $query = AdmissionApplication::with(['course', 'academicSession'])->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        $applications = $query->get();
        $courses = Course::all();

        return view('admissions.index', compact('applications', 'courses'));
    }

    public function generateMeritList(Request $request): RedirectResponse
    {
        $courseId = $request->input('course_id');
        $apps = AdmissionApplication::whereIn('status', ['Submitted', 'Verified', 'Waiting List'])
            ->when($courseId, fn ($q) => $q->where('course_id', $courseId))
            ->orderByDesc('merit_score')
            ->get();

        $rank = 1;
        foreach ($apps as $app) {
            $cutoff = $app->course->cutoff_percentage ?? 50;
            $status = ($app->merit_score >= $cutoff) ? 'Merit Listed' : 'Waiting List';
            $app->update([
                'merit_rank' => $rank++,
                'status' => $status,
            ]);
        }

        return back()->with('success', 'Merit list & waiting list generated based on course cut-off percentages.');
    }

    public function updateStatus(Request $request, AdmissionApplication $application): RedirectResponse
    {
        $status = $request->validate(['status' => 'required|in:Verified,Merit Listed,Waiting List,Approved,Rejected,Confirmed,Cancelled'])['status'];
        $regNo = $application->registration_number;

        if ($status === 'Confirmed' && ! $regNo) {
            $regNo = 'REG-' . date('Y') . '-' . str_pad((string) $application->id, 4, '0', STR_PAD_LEFT);
            $course = $application->course;
            $studentRole = Role::where('slug', 'student')->first();

            $user = User::firstOrCreate(
                ['email' => $application->email],
                [
                    'name' => $application->full_name,
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole?->id,
                    'phone' => $application->phone,
                    'college_id' => $course->department->college_id,
                ]
            );

            $student = Student::firstOrCreate(
                ['email' => $application->email],
                [
                    'user_id' => $user->id,
                    'college_id' => $course->department->college_id,
                    'department_id' => $course->department_id,
                    'course_id' => $course->id,
                    'current_semester' => 1,
                    'unique_student_id' => 'VV' . date('y') . strtoupper(substr($course->code, 0, 2)) . str_pad((string) $application->id, 3, '0', STR_PAD_LEFT),
                    'admission_number' => 'ADM-' . date('Y') . '-' . str_pad((string) $application->id, 4, '0', STR_PAD_LEFT),
                    'registration_number' => $regNo,
                    'full_name' => $application->full_name,
                    'phone' => $application->phone,
                    'dob' => $application->dob,
                    'gender' => $application->gender,
                    'category' => $application->category,
                    'aadhaar_last4' => $application->aadhaar_number ? substr($application->aadhaar_number, -4) : null,
                    'permanent_address' => $application->address,
                    'admission_date' => now()->toDateString(),
                    'status' => 'Active',
                ]
            );

            $feeStructure = FeeStructure::where('course_id', $course->id)->first();
            $totalFee = $feeStructure?->total_fee ?? 55000;
            StudentFeeAccount::firstOrCreate(
                ['student_id' => $student->id, 'academic_session_id' => $application->academic_session_id],
                [
                    'fee_structure_id' => $feeStructure?->id,
                    'total_amount' => $totalFee,
                    'balance_amount' => $totalFee,
                    'due_date' => now()->addDays(30)->toDateString(),
                    'status' => 'Pending',
                ]
            );
        }

        $refundAmount = $status === 'Cancelled' ? $application->application_fee_amount : $application->refund_amount;

        $application->update([
            'status' => $status,
            'registration_number' => $regNo,
            'refund_amount' => $refundAmount,
            'remarks' => $request->input('remarks', $application->remarks),
        ]);

        NotificationLog::create([
            'recipient_name' => $application->full_name,
            'recipient_contact' => $application->email,
            'channel' => 'Email',
            'subject' => "Admission Status Updated: {$status}",
            'message' => "Your application {$application->application_no} status is now {$status}.",
            'status' => 'Sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', "Application {$application->application_no} updated to {$status}.");
    }

    public function receipt(AdmissionApplication $application): View
    {
        $application->load('course.department.college');

        return view('admissions.receipt', compact('application'));
    }

    // Module 3: Enquiry & Admission CRM
    public function enquiries(): View
    {
        $enquiries = AdmissionEnquiry::with(['course', 'counsellor', 'followups'])->latest()->get();
        $courses = Course::all();
        $counsellors = User::whereHas('role', fn ($q) => $q->whereIn('slug', ['super_admin', 'faculty']))->get();

        return view('admissions.enquiries', compact('enquiries', 'courses', 'counsellors'));
    }

    public function storeEnquiry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'source' => 'required|in:Walk-in,Telephone,Online,Campaign,Referral',
            'campaign_name' => 'nullable|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'counsellor_id' => 'nullable|exists:users,id',
            'next_followup_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data['enquiry_no'] = 'ENQ-' . date('Y') . '-' . rand(100, 999);
        $data['status'] = 'New';
        AdmissionEnquiry::create($data);

        return back()->with('success', 'Admission enquiry registered.');
    }

    public function addFollowup(Request $request, AdmissionEnquiry $enquiry): RedirectResponse
    {
        $data = $request->validate([
            'notes' => 'required|string',
            'outcome' => 'nullable|string|max:60',
            'status' => 'required|in:New,Follow-up,Converted,Lost',
            'lost_reason' => 'nullable|string|max:255',
            'next_followup_date' => 'nullable|date',
        ]);

        EnquiryFollowup::create([
            'admission_enquiry_id' => $enquiry->id,
            'user_id' => Auth::id(),
            'followup_at' => now(),
            'notes' => $data['notes'],
            'outcome' => $data['outcome'] ?? $data['status'],
        ]);

        $enquiry->update([
            'status' => $data['status'],
            'lost_reason' => $data['lost_reason'] ?? $enquiry->lost_reason,
            'next_followup_date' => $data['next_followup_date'] ?? $enquiry->next_followup_date,
        ]);

        return back()->with('success', 'Follow-up logged and enquiry status updated.');
    }

    public function bulkNotifyEnquiries(Request $request): RedirectResponse
    {
        $request->validate(['channel' => 'required|in:Email,SMS,WhatsApp', 'message' => 'required|string']);
        $enquiries = AdmissionEnquiry::whereIn('status', ['New', 'Follow-up'])->get();
        foreach ($enquiries as $enq) {
            NotificationLog::create([
                'recipient_name' => $enq->student_name,
                'recipient_contact' => $request->channel === 'Email' ? ($enq->email ?? $enq->phone) : $enq->phone,
                'channel' => $request->channel,
                'subject' => 'Admission Counselling Update',
                'message' => $request->message,
                'status' => 'Sent',
                'sent_at' => now(),
            ]);
        }

        return back()->with('success', "Bulk {$request->channel} sent to {$enquiries->count()} active enquiries.");
    }
}

