@extends('layouts.app')
@section('title', 'Course & Academic Management')
@section('header', 'Course, Curriculum (NEP 2020 / CBCS) & Academic Calendar')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Department</h3>
        <form method="POST" action="{{ route('academics.departments.store') }}" class="grid grid-cols-2 gap-3 text-xs">
            @csrf
            <select name="college_id" class="rounded-lg border border-slate-300 px-3 py-2">@foreach($colleges as $col)<option value="{{ $col->id }}">{{ $col->code }}</option>@endforeach</select>
            <input type="text" name="code" placeholder="Dept Code (e.g. MECH)" required class="rounded-lg border border-slate-300 px-3 py-2">
            <input type="text" name="name" placeholder="Department Name" required class="rounded-lg border border-slate-300 px-3 py-2 col-span-2">
            <input type="text" name="hod_name" placeholder="HOD Name" class="rounded-lg border border-slate-300 px-3 py-2">
            <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Add Department</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Course / Programme (UG / PG / Diploma / Certificate)</h3>
        <form method="POST" action="{{ route('academics.courses.store') }}" class="grid grid-cols-3 gap-2.5 text-xs">
            @csrf
            <select name="department_id" class="rounded-lg border border-slate-300 px-2 py-2">@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }}</option>@endforeach</select>
            <input type="text" name="code" placeholder="Course Code" required class="rounded-lg border border-slate-300 px-2 py-2">
            <select name="level" class="rounded-lg border border-slate-300 px-2 py-2"><option>Undergraduate</option><option>Postgraduate</option><option>Diploma</option><option>Certificate</option></select>
            <input type="text" name="name" placeholder="Programme Name" required class="rounded-lg border border-slate-300 px-2 py-2 col-span-3">
            <input type="number" name="duration_years" value="3" placeholder="Years" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="number" name="total_semesters" value="6" placeholder="Semesters" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="number" name="total_credits" value="120" placeholder="Credits" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="number" name="intake_capacity" value="60" placeholder="Intake" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="number" step="0.1" name="cutoff_percentage" value="60" placeholder="Cut-off %" class="rounded-lg border border-slate-300 px-2 py-2">
            <div class="flex items-center gap-3 col-span-3">
                <label class="flex items-center gap-1"><input type="checkbox" name="nep_2020_enabled" value="1" checked> NEP 2020</label>
                <label class="flex items-center gap-1"><input type="checkbox" name="cbcs_enabled" value="1" checked> CBCS</label>
                <button class="ml-auto px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold">Create Course</button>
            </div>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add Subject to Curriculum</h3>
        <form method="POST" action="{{ route('academics.subjects.store') }}" class="grid grid-cols-3 gap-2.5 text-xs">
            @csrf
            <select name="course_id" class="rounded-lg border border-slate-300 px-2 py-2 col-span-2">@foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>@endforeach</select>
            <input type="number" name="semester" value="1" placeholder="Sem" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="text" name="code" placeholder="Subject Code" required class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="text" name="name" placeholder="Subject Name" required class="rounded-lg border border-slate-300 px-2 py-2 col-span-2">
            <select name="subject_type" class="rounded-lg border border-slate-300 px-2 py-2"><option>Core</option><option>Elective</option><option>Practical</option><option>AECC</option><option>SEC</option><option>GE</option><option>Project</option></select>
            <input type="number" step="0.5" name="credits" value="4" placeholder="Credits" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="number" name="max_internal_marks" value="30" placeholder="Internal" class="rounded-lg border border-slate-300 px-2 py-2">
            <input type="number" name="max_external_marks" value="70" placeholder="External" class="rounded-lg border border-slate-300 px-2 py-2">
            <button class="col-span-2 rounded-lg bg-emerald-600 text-white font-semibold py-2">Add Subject</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Academic Calendar</h3>
        <form method="POST" action="{{ route('academics.calendar.store') }}" class="grid grid-cols-2 gap-2.5 text-xs mb-3">
            @csrf
            <select name="academic_session_id" class="rounded-lg border border-slate-300 px-2 py-2">@foreach($sessions as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select>
            <select name="event_type" class="rounded-lg border border-slate-300 px-2 py-2"><option>Academic</option><option>Holiday</option><option>Examination</option><option>Cultural</option><option>Workshop</option></select>
            <input type="text" name="title" placeholder="Event / Holiday Title" required class="rounded-lg border border-slate-300 px-2 py-2 col-span-2">
            <input type="date" name="start_date" required class="rounded-lg border border-slate-300 px-2 py-2">
            <button class="rounded-lg bg-slate-800 text-white font-semibold py-2">Add to Calendar</button>
        </form>
        <div class="space-y-1.5 text-xs max-h-40 overflow-y-auto">
            @foreach($calendars as $cal)
                <div class="flex justify-between p-2 rounded bg-slate-50 border border-slate-100">
                    <span><strong>{{ $cal->title }}</strong> ({{ $cal->event_type }})</span>
                    <span class="text-slate-500">{{ $cal->start_date }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase">
            <tr>
                <th class="px-4 py-3">Code</th>
                <th class="px-4 py-3">Programme</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Level</th>
                <th class="px-4 py-3">Credits / Sem</th>
                <th class="px-4 py-3">NEP / CBCS</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($courses as $c)
                <tr>
                    <td class="px-4 py-3 font-semibold text-indigo-700">{{ $c->code }}</td>
                    <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                    <td class="px-4 py-3">{{ $c->department->code ?? '' }}</td>
                    <td class="px-4 py-3">{{ $c->level }}</td>
                    <td class="px-4 py-3">{{ $c->total_credits }} Credits / {{ $c->total_semesters }} Sem</td>
                    <td class="px-4 py-3">{{ $c->nep_2020_enabled ? 'NEP 2020' : '' }} {{ $c->cbcs_enabled ? '• CBCS' : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

