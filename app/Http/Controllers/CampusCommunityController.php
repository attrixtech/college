<?php

namespace App\Http\Controllers;

use App\Models\AccreditationRecord;
use App\Models\Alumnus;
use App\Models\Announcement;
use App\Models\CampusEvent;
use App\Models\Course;
use App\Models\Department;
use App\Models\NotificationLog;
use App\Models\ResearchRecord;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CampusCommunityController extends Controller
{
    // Module 25: Events & Module 26: Alumni
    public function eventsAlumni(): View
    {
        $events = CampusEvent::with('department')->latest()->get();
        $alumni = Alumnus::with('course')->latest()->get();
        $departments = Department::all();
        $courses = Course::all();

        return view('community.events_alumni', compact('events', 'alumni', 'departments', 'courses'));
    }

    public function storeEvent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Cultural,Sports,Seminar,Workshop,Club,Annual Fest',
            'event_date' => 'required|date',
            'venue' => 'required|string|max:120',
            'budget' => 'numeric|min:0',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);
        CampusEvent::create($data);

        return back()->with('success', 'Campus event created.');
    }

    public function storeAlumnus(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'graduation_year' => 'required|integer|min:1980|max:2035',
            'course_id' => 'nullable|exists:courses,id',
            'current_company' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'donation_total' => 'numeric|min:0',
        ]);
        $data['is_mentor'] = $request->boolean('is_mentor');
        Alumnus::create($data);

        return back()->with('success', 'Alumnus registered.');
    }

    // Module 27: Accreditation (NAAC / NIRF / AISHE / UGC / AICTE / NBA / IQAC) & Module 28: Research & Innovation
    public function accreditationResearch(): View
    {
        $accreditations = AccreditationRecord::with('department')->latest()->get();
        $research = ResearchRecord::with('department')->latest()->get();
        $departments = Department::all();

        return view('community.accreditation_research', compact('accreditations', 'research', 'departments'));
    }

    public function storeAccreditation(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'framework' => 'required|in:NAAC,NIRF,AISHE,UGC,AICTE,NBA,IQAC',
            'cycle_year' => 'required|string|max:20',
            'criterion' => 'required|string|max:80',
            'metric_code' => 'nullable|string|max:30',
            'title' => 'required|string|max:255',
            'score_or_value' => 'nullable|string|max:60',
            'department_id' => 'nullable|exists:departments,id',
            'evidence_url' => 'nullable|string|max:255',
        ]);
        AccreditationRecord::create($data);

        return back()->with('success', "{$data['framework']} compliance record saved.");
    }

    public function storeResearch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'record_type' => 'required|in:Faculty Publication,Student Project,Research Grant,Patent,Conference,Incubation',
            'title' => 'required|string|max:255',
            'lead_name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'funding_agency' => 'nullable|string|max:255',
            'grant_amount' => 'numeric|min:0',
            'publication_venue' => 'nullable|string|max:255',
            'patent_no' => 'nullable|string|max:50',
            'year' => 'required|integer|min:2000|max:2035',
            'status' => 'required|in:Ongoing,Published,Granted,Completed',
        ]);
        ResearchRecord::create($data);

        return back()->with('success', 'Research & Innovation record added.');
    }

    // Module 29: Communication & Notifications
    public function communication(): View
    {
        $announcements = Announcement::with(['department', 'creator'])->latest()->get();
        $logs = NotificationLog::latest()->take(50)->get();
        $departments = Department::all();

        return view('community.communication', compact('announcements', 'logs', 'departments'));
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'required|in:All,Students,Faculty,Parents,Department',
            'department_id' => 'nullable|exists:departments,id',
            'channel' => 'required|in:Portal,Email,SMS,WhatsApp,Push',
        ]);
        $data['is_emergency'] = $request->boolean('is_emergency');
        $data['published_at'] = now();
        $data['created_by'] = Auth::id();
        Announcement::create($data);

        if ($data['channel'] !== 'Portal') {
            $students = Student::where('status', 'Active')->take(10)->get();
            foreach ($students as $stu) {
                NotificationLog::create([
                    'recipient_name' => $stu->full_name,
                    'recipient_contact' => $data['channel'] === 'Email' ? $stu->email : $stu->phone,
                    'channel' => $data['channel'],
                    'subject' => $data['title'],
                    'message' => $data['message'],
                    'status' => 'Sent',
                    'sent_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Announcement published & notifications dispatched.');
    }
}

