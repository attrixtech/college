@extends('layouts.app')
@section('title', 'Attendance Management')
@section('header', 'Student & Faculty Attendance (Manual / QR / Biometric / RFID)')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-900">Mark Student Attendance ({{ $date }})</h3>
            <form method="POST" action="{{ route('attendance.shortage_alerts') }}">
                @csrf
                <button class="px-3 py-1.5 rounded-lg bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700">Send Shortage Alerts (&lt;75%)</button>
            </form>
        </div>

        <form method="POST" action="{{ route('attendance.students.mark') }}" class="space-y-3 text-xs">
            @csrf
            <div class="grid grid-cols-3 gap-2">
                <input type="date" name="attendance_date" value="{{ $date }}" class="rounded border border-slate-300 px-2 py-1.5">
                <select name="subject_id" class="rounded border border-slate-300 px-2 py-1.5">
                    @foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach
                </select>
                <select name="method" class="rounded border border-slate-300 px-2 py-1.5">
                    <option>Manual</option><option>QR</option><option>Biometric</option><option>RFID</option>
                </select>
            </div>

            <div class="max-h-64 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100">
                @foreach($students as $stu)
                    <div class="px-3 py-2 flex items-center justify-between">
                        <div>
                            <span class="font-semibold">{{ $stu->unique_student_id }}</span> &mdash; {{ $stu->full_name }}
                        </div>
                        <select name="statuses[{{ $stu->id }}]" class="rounded border border-slate-300 px-2 py-1">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                            <option value="Excused">Excused</option>
                        </select>
                    </div>
                @endforeach
            </div>
            <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Save Student Attendance</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h3 class="text-sm font-semibold text-slate-900">Faculty / Staff Biometric Log</h3>
        <form method="POST" action="{{ route('attendance.employees.mark') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="employee_id" class="w-full rounded border border-slate-300 px-2 py-1.5">
                @foreach($employees as $emp)<option value="{{ $emp->id }}">{{ $emp->staff_id }} - {{ $emp->full_name }}</option>@endforeach
            </select>
            <input type="date" name="attendance_date" value="{{ $date }}" class="w-full rounded border border-slate-300 px-2 py-1.5">
            <div class="grid grid-cols-2 gap-2">
                <input type="time" name="check_in" value="09:00" class="rounded border border-slate-300 px-2 py-1.5">
                <input type="time" name="check_out" value="17:00" class="rounded border border-slate-300 px-2 py-1.5">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <select name="status" class="rounded border border-slate-300 px-2 py-1.5"><option>Present</option><option>Late</option><option>Half Day</option><option>Absent</option><option>On Leave</option></select>
                <select name="method" class="rounded border border-slate-300 px-2 py-1.5"><option>Biometric</option><option>RFID</option><option>Manual</option></select>
            </div>
            <button class="w-full rounded bg-slate-800 text-white font-semibold py-2">Log Staff Attendance</button>
        </form>

        <div class="border-t border-slate-200 pt-3">
            <div class="text-xs font-semibold text-rose-700 mb-2">Shortage Alert List (&lt;75%)</div>
            @forelse($shortageList as $item)
                <div class="text-xs p-2 rounded bg-rose-50 border border-rose-200 mb-1.5 flex justify-between">
                    <span>{{ $item['student']->full_name }}</span>
                    <strong class="text-rose-700">{{ $item['percentage'] }}%</strong>
                </div>
            @empty
                <div class="text-xs text-slate-400">All students have ≥ 75% attendance.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase">
            <tr>
                <th class="px-4 py-3">Student ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Total Classes</th>
                <th class="px-4 py-3">Present</th>
                <th class="px-4 py-3">Percentage</th>
                <th class="px-4 py-3">Eligibility</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($studentSummary as $row)
                <tr>
                    <td class="px-4 py-2.5 font-semibold">{{ $row['student']->unique_student_id }}</td>
                    <td class="px-4 py-2.5">{{ $row['student']->full_name }}</td>
                    <td class="px-4 py-2.5">{{ $row['total'] }}</td>
                    <td class="px-4 py-2.5">{{ $row['present'] }}</td>
                    <td class="px-4 py-2.5 font-bold">{{ $row['percentage'] }}%</td>
                    <td class="px-4 py-2.5">
                        @if($row['shortage'])
                            <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-medium">Shortage Alert</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-medium">Eligible</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

