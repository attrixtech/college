@extends('layouts.app')
@section('title', 'Examination Management')
@section('header', 'Module 8: Examination Management, Marks Entry & SGPA/CGPA')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Examination</h3>
        <form method="POST" action="{{ route('exams.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <input type="text" name="name" placeholder="Exam Name (e.g. End-Sem Nov 2026)" required class="w-full rounded border border-slate-300 px-3 py-2">
            <select name="exam_type" class="w-full rounded border border-slate-300 px-3 py-2">
                <option>End-Semester</option><option>Mid-Semester</option><option>Internal</option><option>Practical</option><option>Supplementary</option><option>Backlog</option>
            </select>
            <select name="academic_session_id" class="w-full rounded border border-slate-300 px-3 py-2">@foreach($sessions as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
            <div class="grid grid-cols-2 gap-2">
                <select name="course_id" class="rounded border border-slate-300 px-2 py-2">@foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->code }}</option>@endforeach</select>
                <input type="number" name="semester" value="3" placeholder="Sem" class="rounded border border-slate-300 px-2 py-2">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="date" name="start_date" required class="rounded border border-slate-300 px-2 py-2">
                <input type="date" name="end_date" required class="rounded border border-slate-300 px-2 py-2">
            </div>
            <button class="w-full rounded-lg bg-indigo-600 text-white font-semibold py-2">Create Exam</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Schedule &amp; Invigilator Allocation</h3>
        <form method="POST" action="{{ route('exams.schedules.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="exam_id" class="w-full rounded border border-slate-300 px-2 py-2">@foreach($exams as $ex)<option value="{{ $ex->id }}">{{ $ex->name }}</option>@endforeach</select>
            <select name="subject_id" class="w-full rounded border border-slate-300 px-2 py-2">@foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach</select>
            <div class="grid grid-cols-3 gap-2">
                <input type="date" name="exam_date" required class="rounded border border-slate-300 px-2 py-2">
                <input type="time" name="start_time" value="10:00" class="rounded border border-slate-300 px-2 py-2">
                <input type="time" name="end_time" value="13:00" class="rounded border border-slate-300 px-2 py-2">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="room_no" placeholder="Exam Hall" required class="rounded border border-slate-300 px-2 py-2">
                <select name="invigilator_id" class="rounded border border-slate-300 px-2 py-2">
                    <option value="">Invigilator</option>
                    @foreach($faculty as $f)<option value="{{ $f->id }}">{{ $f->full_name }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input type="number" name="max_marks" value="100" class="rounded border border-slate-300 px-2 py-2">
                <input type="number" name="passing_marks" value="40" class="rounded border border-slate-300 px-2 py-2">
            </div>
            <button class="w-full rounded-lg bg-slate-800 text-white font-semibold py-2">Schedule Paper</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Marks Entry &amp; Auto Grade/SGPA</h3>
        <form method="POST" action="{{ route('exams.marks.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="exam_id" class="w-full rounded border border-slate-300 px-2 py-2">@foreach($exams as $ex)<option value="{{ $ex->id }}">{{ $ex->name }}</option>@endforeach</select>
            <select name="student_id" class="w-full rounded border border-slate-300 px-2 py-2">@foreach($students as $stu)<option value="{{ $stu->id }}">{{ $stu->unique_student_id }} - {{ $stu->full_name }}</option>@endforeach</select>
            <select name="subject_id" class="w-full rounded border border-slate-300 px-2 py-2">@foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach</select>
            <div class="grid grid-cols-2 gap-2">
                <input type="number" step="0.5" name="internal_marks" placeholder="Internal (Max 30)" required class="rounded border border-slate-300 px-2 py-2">
                <input type="number" step="0.5" name="external_marks" placeholder="External (Max 70)" required class="rounded border border-slate-300 px-2 py-2">
            </div>
            <button class="w-full rounded-lg bg-emerald-600 text-white font-semibold py-2 hover:bg-emerald-700">Save Marks &amp; Compute Grade</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Active Examinations &amp; Admit Cards</h3>
    <div class="space-y-3 text-xs">
        @foreach($exams as $ex)
            <div class="p-3 rounded-lg border border-slate-200 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <div class="font-bold text-slate-900">{{ $ex->name }} ({{ $ex->exam_type }})</div>
                    <div class="text-slate-500">{{ $ex->course->code ?? '' }} Sem {{ $ex->semester }} &bull; {{ $ex->start_date }} to {{ $ex->end_date }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('exams.publish', $ex) }}">
                        @csrf
                        <button class="px-3 py-1.5 rounded {{ $ex->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} font-semibold">
                            {{ $ex->is_published ? 'Results Published ✓' : 'Publish Results' }}
                        </button>
                    </form>
                    @if($students->first())
                        <a href="{{ route('exams.admit_card', [$ex, $students->first()]) }}" target="_blank" class="px-3 py-1.5 rounded border border-slate-300 hover:bg-slate-50">Sample Admit Card</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase">
            <tr>
                <th class="px-4 py-3">Student</th>
                <th class="px-4 py-3">Subject</th>
                <th class="px-4 py-3">Internal / External</th>
                <th class="px-4 py-3">Total &amp; Grade</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Marksheet</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($marks as $m)
                <tr>
                    <td class="px-4 py-2.5 font-medium">{{ $m->student->full_name ?? '' }}</td>
                    <td class="px-4 py-2.5">{{ $m->subject->code ?? '' }} - {{ $m->subject->name ?? '' }}</td>
                    <td class="px-4 py-2.5">{{ $m->internal_marks }} + {{ $m->external_marks }}</td>
                    <td class="px-4 py-2.5 font-bold">{{ $m->total_marks }} ({{ $m->grade }} / {{ $m->grade_point }} GP)</td>
                    <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded {{ $m->result_status === 'Pass' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">{{ $m->result_status }}</span></td>
                    <td class="px-4 py-2.5"><a href="{{ route('exams.marksheet', $m->student_id) }}" target="_blank" class="text-indigo-600 underline">View Marksheet</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

