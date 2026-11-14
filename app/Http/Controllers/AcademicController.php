<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\AcademicSession;
use App\Models\ClassSection;
use App\Models\College;
use App\Models\Course;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Subject;
use App\Models\SubjectAllocation;
use App\Models\TimetableSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicController extends Controller
{
    // Module 5: Courses, Departments, Subjects, NEP 2020 / CBCS, Academic Calendar
    public function index(): View
    {
        $departments = Department::with('college')->get();
        $courses = Course::with(['department', 'subjects'])->get();
        $subjects = Subject::with('course')->get();
        $sessions = AcademicSession::with('academicYear')->get();
        $calendars = AcademicCalendar::with('academicSession')->orderBy('start_date')->get();
        $colleges = College::all();

        return view('academics.index', compact('departments', 'courses', 'subjects', 'sessions', 'calendars', 'colleges'));
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'code' => 'required|string|max:20|unique:departments,code',
            'name' => 'required|string|max:255',
            'hod_name' => 'nullable|string|max:255',
        ]);
        Department::create($data);

        return back()->with('success', 'Department created.');
    }

    public function storeCourse(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'code' => 'required|string|max:20|unique:courses,code',
            'name' => 'required|string|max:255',
            'level' => 'required|in:Undergraduate,Postgraduate,Diploma,Certificate',
            'duration_years' => 'required|integer|min:1|max:6',
            'total_semesters' => 'required|integer|min:1|max:12',
            'total_credits' => 'required|integer|min:10|max:300',
            'intake_capacity' => 'required|integer|min:5|max:500',
            'cutoff_percentage' => 'required|numeric|min:0|max:100',
        ]);
        $data['nep_2020_enabled'] = $request->boolean('nep_2020_enabled');
        $data['cbcs_enabled'] = $request->boolean('cbcs_enabled');
        Course::create($data);

        return back()->with('success', 'Course / Programme created.');
    }

    public function storeSubject(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'code' => 'required|string|max:25|unique:subjects,code',
            'name' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:12',
            'credits' => 'required|numeric|min:1|max:20',
            'subject_type' => 'required|in:Core,Elective,Practical,AECC,SEC,GE,Project',
            'max_internal_marks' => 'required|integer|min:0|max:100',
            'max_external_marks' => 'required|integer|min:0|max:100',
            'syllabus_summary' => 'nullable|string',
        ]);
        Subject::create($data);

        return back()->with('success', 'Subject added to curriculum.');
    }

    public function storeCalendar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'title' => 'required|string|max:255',
            'event_type' => 'required|in:Academic,Holiday,Examination,Cultural,Workshop',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);
        AcademicCalendar::create($data);

        return back()->with('success', 'Academic calendar event added.');
    }

    // Module 6: Class & Timetable Management
    public function timetable(): View
    {
        $sections = ClassSection::with(['course', 'classTeacher'])->get();
        $slots = TimetableSlot::with(['classSection.course', 'subject', 'faculty', 'substituteFaculty'])
            ->orderBy('start_time')
            ->get()
            ->sortBy(fn ($s) => array_search($s->day_of_week, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], true))
            ->values();
        $allocations = SubjectAllocation::with(['academicSession', 'classSection.course', 'subject', 'faculty'])->get();
        $courses = Course::all();
        $subjects = Subject::all();
        $faculty = Employee::where('employee_type', 'Faculty')->where('status', 'Active')->get();
        $sessions = AcademicSession::all();

        return view('academics.timetable', compact('sections', 'slots', 'allocations', 'courses', 'subjects', 'faculty', 'sessions'));
    }

    public function storeSection(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required|integer|min:1|max:12',
            'section_name' => 'required|string|max:20',
            'room_no' => 'nullable|string|max:30',
            'class_teacher_id' => 'nullable|exists:employees,id',
        ]);
        ClassSection::create($data);

        return back()->with('success', 'Class section created.');
    }

    public function storeTimetableSlot(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'faculty_id' => 'required|exists:employees,id',
            'substitute_faculty_id' => 'nullable|exists:employees,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'room_no' => 'required|string|max:30',
            'slot_type' => 'required|in:Lecture,Practical,Lab,Special',
        ]);

        // Conflict detection: same faculty or same room overlapping on same day
        $conflict = TimetableSlot::where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time']);
            })
            ->where(function ($q) use ($data) {
                $q->where('faculty_id', $data['faculty_id'])
                    ->orWhere('room_no', $data['room_no'])
                    ->orWhere('class_section_id', $data['class_section_id']);
            })
            ->first();

        if ($conflict) {
            return back()->withErrors([
                'conflict' => "Timetable conflict detected on {$data['day_of_week']} ({$conflict->start_time}-{$conflict->end_time}) for Faculty, Room {$data['room_no']}, or Class Section!",
            ])->withInput();
        }

        TimetableSlot::create($data);

        return back()->with('success', 'Timetable slot scheduled with zero conflicts.');
    }
}
