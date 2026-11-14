@extends('layouts.app')
@section('title', 'Student Information System')
@section('header', 'Module 4: Student Information Management System (SIS)')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Enroll New Student</h3>
    <form method="POST" action="{{ route('students.store') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <select name="college_id" required class="rounded-lg border border-slate-300 px-3 py-2">
            @foreach($colleges as $col)<option value="{{ $col->id }}">{{ $col->code }}</option>@endforeach
        </select>
        <select name="department_id" required class="rounded-lg border border-slate-300 px-3 py-2">
            @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }} - {{ $d->name }}</option>@endforeach
        </select>
        <select name="course_id" required class="rounded-lg border border-slate-300 px-3 py-2">
            @foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->code }}</option>@endforeach
        </select>
        <input type="number" name="current_semester" value="1" min="1" max="10" placeholder="Semester" class="rounded-lg border border-slate-300 px-3 py-2">
        <input type="text" name="full_name" placeholder="Student Full Name *" required class="rounded-lg border border-slate-300 px-3 py-2">
        <input type="email" name="email" placeholder="Email *" required class="rounded-lg border border-slate-300 px-3 py-2">
        <input type="text" name="phone" placeholder="Mobile *" required class="rounded-lg border border-slate-300 px-3 py-2">
        <select name="gender" class="rounded-lg border border-slate-300 px-3 py-2"><option>Male</option><option>Female</option><option>Other</option></select>
        <select name="category" class="rounded-lg border border-slate-300 px-3 py-2"><option>General</option><option>OBC</option><option>SC</option><option>ST</option><option>EWS</option></select>
        <input type="text" name="guardian_name" placeholder="Parent / Guardian Name" class="rounded-lg border border-slate-300 px-3 py-2">
        <input type="text" name="guardian_phone" placeholder="Guardian Mobile" class="rounded-lg border border-slate-300 px-3 py-2">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2 hover:bg-indigo-700">Enroll Student</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-4 mb-4 flex flex-wrap gap-3 items-center justify-between">
    <form method="GET" class="flex flex-wrap gap-2 text-xs">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Name / ID / Adm No..." class="rounded-lg border border-slate-300 px-3 py-1.5 w-60">
        <select name="course_id" class="rounded-lg border border-slate-300 px-3 py-1.5">
            <option value="">All Courses</option>
            @foreach($courses as $c)<option value="{{ $c->id }}" @selected(request('course_id')==$c->id)>{{ $c->code }}</option>@endforeach
        </select>
        <select name="status" class="rounded-lg border border-slate-300 px-3 py-1.5">
            <option value="">All Status</option>
            @foreach(['Active','Passed Out','Suspended','Withdrawn'] as $st)<option @selected(request('status')==$st)>{{ $st }}</option>@endforeach
        </select>
        <button class="px-3 py-1.5 rounded-lg bg-slate-800 text-white">Search</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase">
            <tr>
                <th class="px-4 py-3">Student ID</th>
                <th class="px-4 py-3">Name &amp; Contact</th>
                <th class="px-4 py-3">Course / Sem</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($students as $stu)
                <tr>
                    <td class="px-4 py-3 font-semibold text-indigo-700">{{ $stu->unique_student_id }}<div class="text-[10px] text-slate-400">{{ $stu->admission_number }}</div></td>
                    <td class="px-4 py-3"><div class="font-medium text-slate-900">{{ $stu->full_name }}</div><div class="text-slate-500">{{ $stu->email }} &bull; {{ $stu->phone }}</div></td>
                    <td class="px-4 py-3">{{ $stu->course->code ?? '' }} &bull; Sem {{ $stu->current_semester }}</td>
                    <td class="px-4 py-3">{{ $stu->category }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-medium">{{ $stu->status }}</span></td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('students.show', $stu) }}" class="px-2.5 py-1 rounded bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">Profile</a>
                        <a href="{{ route('students.id_card', $stu) }}" target="_blank" class="px-2.5 py-1 rounded border border-slate-300 hover:bg-slate-50">ID Card</a>
                        <a href="{{ route('exams.marksheet', $stu) }}" target="_blank" class="px-2.5 py-1 rounded border border-slate-300 hover:bg-slate-50">Transcript</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

