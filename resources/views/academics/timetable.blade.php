@extends('layouts.app')
@section('title', 'Class & Timetable Management')
@section('header', 'Module 6: Class Sections & Timetable Management (with Conflict Detection)')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Class / Section</h3>
        <form method="POST" action="{{ route('timetable.sections.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="course_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">@foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->code }}</option>@endforeach</select>
            <div class="grid grid-cols-2 gap-2">
                <input type="number" name="semester" value="1" placeholder="Semester" class="rounded-lg border border-slate-300 px-3 py-2">
                <input type="text" name="section_name" value="A" placeholder="Section (A/B)" class="rounded-lg border border-slate-300 px-3 py-2">
            </div>
            <input type="text" name="room_no" placeholder="Classroom No (e.g. CS-301)" class="w-full rounded-lg border border-slate-300 px-3 py-2">
            <select name="class_teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Class Teacher / Mentor</option>
                @foreach($faculty as $f)<option value="{{ $f->id }}">{{ $f->full_name }}</option>@endforeach
            </select>
            <button class="w-full rounded-lg bg-slate-800 text-white font-semibold py-2">Create Section</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Schedule Lecture / Practical / Special Class (Auto Conflict Check)</h3>
        <form method="POST" action="{{ route('timetable.slots.store') }}" class="grid md:grid-cols-3 gap-3 text-xs">
            @csrf
            <select name="class_section_id" required class="rounded-lg border border-slate-300 px-3 py-2">
                @foreach($sections as $sec)<option value="{{ $sec->id }}">{{ $sec->course->code ?? '' }} Sem {{ $sec->semester }} - {{ $sec->section_name }}</option>@endforeach
            </select>
            <select name="subject_id" required class="rounded-lg border border-slate-300 px-3 py-2">
                @foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach
            </select>
            <select name="faculty_id" required class="rounded-lg border border-slate-300 px-3 py-2">
                @foreach($faculty as $f)<option value="{{ $f->id }}">{{ $f->full_name }}</option>@endforeach
            </select>
            <select name="day_of_week" class="rounded-lg border border-slate-300 px-3 py-2">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)<option>{{ $day }}</option>@endforeach
            </select>
            <input type="time" name="start_time" value="11:00" required class="rounded-lg border border-slate-300 px-3 py-2">
            <input type="time" name="end_time" value="12:00" required class="rounded-lg border border-slate-300 px-3 py-2">
            <input type="text" name="room_no" placeholder="Room / Lab No *" required class="rounded-lg border border-slate-300 px-3 py-2">
            <select name="slot_type" class="rounded-lg border border-slate-300 px-3 py-2">
                <option>Lecture</option><option>Practical</option><option>Lab</option><option>Special</option>
            </select>
            <select name="substitute_faculty_id" class="rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Substitute Faculty (Optional)</option>
                @foreach($faculty as $f)<option value="{{ $f->id }}">{{ $f->full_name }}</option>@endforeach
            </select>
            <button class="md:col-span-3 rounded-lg bg-indigo-600 text-white font-semibold py-2 hover:bg-indigo-700">Check Conflicts &amp; Schedule Slot</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase">
            <tr>
                <th class="px-4 py-3">Day &amp; Time</th>
                <th class="px-4 py-3">Class Section</th>
                <th class="px-4 py-3">Subject</th>
                <th class="px-4 py-3">Faculty / Substitute</th>
                <th class="px-4 py-3">Room &amp; Type</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($slots as $slot)
                <tr>
                    <td class="px-4 py-3 font-semibold text-indigo-700">{{ $slot->day_of_week }} <div class="text-[11px] text-slate-500">{{ substr($slot->start_time,0,5) }} - {{ substr($slot->end_time,0,5) }}</div></td>
                    <td class="px-4 py-3">{{ $slot->classSection->course->code ?? '' }} Sem {{ $slot->classSection->semester }} ({{ $slot->classSection->section_name }})</td>
                    <td class="px-4 py-3 font-medium">{{ $slot->subject->code ?? '' }} - {{ $slot->subject->name ?? '' }}</td>
                    <td class="px-4 py-3">{{ $slot->faculty->full_name ?? '' }} @if($slot->substituteFaculty)<span class="text-amber-600">(Sub: {{ $slot->substituteFaculty->full_name }})</span>@endif</td>
                    <td class="px-4 py-3">{{ $slot->room_no }} &bull; <span class="px-2 py-0.5 rounded bg-slate-100">{{ $slot->slot_type }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

