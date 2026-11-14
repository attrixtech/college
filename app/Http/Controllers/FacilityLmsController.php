<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Hostel;
use App\Models\HostelActivity;
use App\Models\HostelAllocation;
use App\Models\HostelRoom;
use App\Models\LmsAssignment;
use App\Models\LmsDiscussion;
use App\Models\LmsMaterial;
use App\Models\LmsSubmission;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TransportAllocation;
use App\Models\TransportRoute;
use App\Models\TransportVehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FacilityLmsController extends Controller
{
    // Module 14: Hostel
    public function hostel(): View
    {
        $hostels = Hostel::with(['warden', 'rooms'])->get();
        $rooms = HostelRoom::with('hostel')->get();
        $allocations = HostelAllocation::with(['hostel', 'room', 'student'])->latest()->get();
        $activities = HostelActivity::with(['hostel', 'student'])->latest()->get();
        $students = Student::where('status', 'Active')->get();

        return view('hostel.index', compact('hostels', 'rooms', 'allocations', 'activities', 'students'));
    }

    public function allocateHostel(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hostel_room_id' => 'required|exists:hostel_rooms,id',
            'student_id' => 'required|exists:students,id',
            'bed_no' => 'required|string|max:10',
        ]);
        $room = HostelRoom::findOrFail($data['hostel_room_id']);
        if ($room->occupied_beds >= $room->capacity) {
            return back()->withErrors(['room' => 'Selected room is already full.']);
        }

        HostelAllocation::create([
            'hostel_id' => $room->hostel_id,
            'hostel_room_id' => $room->id,
            'student_id' => $data['student_id'],
            'bed_no' => $data['bed_no'],
            'check_in_date' => now()->toDateString(),
        ]);
        $room->increment('occupied_beds');

        return back()->with('success', 'Hostel bed allocated.');
    }

    public function storeHostelActivity(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'student_id' => 'nullable|exists:students,id',
            'activity_type' => 'required|in:Visitor,Complaint,Maintenance,Outing Request,Attendance',
            'title' => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);
        HostelActivity::create($data);

        return back()->with('success', 'Hostel log/request recorded.');
    }

    // Module 15: Transport
    public function transport(): View
    {
        $vehicles = TransportVehicle::all();
        $routes = TransportRoute::with('vehicle')->get();
        $allocations = TransportAllocation::with(['route.vehicle', 'student'])->latest()->get();
        $students = Student::where('status', 'Active')->get();

        return view('transport.index', compact('vehicles', 'routes', 'allocations', 'students'));
    }

    public function allocateTransport(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'transport_route_id' => 'required|exists:transport_routes,id',
            'student_id' => 'required|exists:students,id',
            'pickup_stop' => 'required|string|max:100',
        ]);
        $data['start_date'] = now()->toDateString();
        TransportAllocation::create($data);

        return back()->with('success', 'Student transport route allocated.');
    }

    // Module 20: LMS
    public function lms(): View
    {
        $materials = LmsMaterial::with(['subject', 'faculty'])->latest()->get();
        $assignments = LmsAssignment::with(['subject', 'faculty', 'submissions.student'])->latest()->get();
        $discussions = LmsDiscussion::with(['subject', 'user'])->latest()->get();
        $subjects = Subject::all();
        $faculty = Employee::where('employee_type', 'Faculty')->get();

        return view('lms.index', compact('materials', 'assignments', 'discussions', 'subjects', 'faculty'));
    }

    public function storeMaterial(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'nullable|exists:employees,id',
            'title' => 'required|string|max:255',
            'material_type' => 'required|in:PDF,Document,Video Lecture,Recorded Class,Quiz',
            'file_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        LmsMaterial::create($data);

        return back()->with('success', 'Study material published to LMS.');
    }

    public function storeAssignment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'nullable|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_marks' => 'required|integer|min:1|max:100',
        ]);
        LmsAssignment::create($data);

        return back()->with('success', 'Assignment created.');
    }

    public function storeDiscussion(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        $data['user_id'] = Auth::id();
        LmsDiscussion::create($data);

        return back()->with('success', 'Discussion post added.');
    }

    public function gradeSubmission(Request $request, LmsSubmission $submission): RedirectResponse
    {
        $data = $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);
        $submission->update($data);

        return back()->with('success', 'Assignment submission graded.');
    }
}

