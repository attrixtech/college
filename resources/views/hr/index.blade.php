@extends('layouts.app')
@section('title', 'Faculty & Staff HR')
@section('header', 'Module 12: Faculty & Staff Management, Workload & Leaves')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Register Faculty / Staff Member</h3>
    <form method="POST" action="{{ route('hr.employees.store') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <select name="college_id" class="rounded border border-slate-300 px-3 py-2">@foreach($colleges as $col)<option value="{{ $col->id }}">{{ $col->code }}</option>@endforeach</select>
        <select name="department_id" class="rounded border border-slate-300 px-3 py-2"><option value="">Department</option>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }}</option>@endforeach</select>
        <select name="employee_type" class="rounded border border-slate-300 px-3 py-2"><option>Faculty</option><option>Non-Teaching</option><option>Admin</option></select>
        <input type="text" name="designation" placeholder="Designation *" required class="rounded border border-slate-300 px-3 py-2">
        <input type="text" name="full_name" placeholder="Full Name *" required class="rounded border border-slate-300 px-3 py-2">
        <input type="email" name="email" placeholder="Email *" required class="rounded border border-slate-300 px-3 py-2">
        <input type="text" name="phone" placeholder="Mobile *" required class="rounded border border-slate-300 px-3 py-2">
        <input type="text" name="qualification" placeholder="Qualification (e.g. Ph.D.)" class="rounded border border-slate-300 px-3 py-2">
        <input type="number" name="experience_years" value="5" placeholder="Experience (Yrs)" class="rounded border border-slate-300 px-3 py-2">
        <input type="date" name="joining_date" value="{{ now()->toDateString() }}" class="rounded border border-slate-300 px-3 py-2">
        <input type="number" name="basic_salary" value="50000" placeholder="Basic Salary (₹)" required class="rounded border border-slate-300 px-3 py-2">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Add Employee</button>
    </form>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Staff Directory &amp; Workload</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b"><tr><th class="p-3">Staff ID</th><th class="p-3">Name &amp; Qualification</th><th class="p-3">Type / Dept</th><th class="p-3">Workload</th><th class="p-3">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($employees as $emp)
                    <tr>
                        <td class="p-3 font-semibold text-indigo-700">{{ $emp->staff_id }}</td>
                        <td class="p-3"><div class="font-medium">{{ $emp->full_name }}</div><div class="text-slate-500">{{ $emp->designation }} &bull; {{ $emp->qualification }}</div></td>
                        <td class="p-3">{{ $emp->employee_type }} ({{ $emp->department->code ?? 'Admin' }})</td>
                        <td class="p-3">{{ $emp->weekly_workload_hours }} hrs/wk</td>
                        <td class="p-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $emp->status }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Leave Requests</div>
        <div class="divide-y divide-slate-100 text-xs">
            @foreach($leaves as $lv)
                <div class="p-3 space-y-1.5">
                    <div class="flex justify-between">
                        <strong>{{ $lv->employee->full_name ?? '' }}</strong>
                        <span class="px-2 py-0.5 rounded bg-slate-100">{{ $lv->status }}</span>
                    </div>
                    <div class="text-slate-500">{{ $lv->leave_type }} ({{ $lv->days }}d): {{ $lv->from_date }} to {{ $lv->to_date }}</div>
                    <div class="text-slate-600">{{ $lv->reason }}</div>
                    @if($lv->status === 'Pending')
                        <div class="flex gap-2 pt-1">
                            <form method="POST" action="{{ route('hr.leaves.update', $lv) }}">@csrf<input type="hidden" name="status" value="Approved"><button class="px-2.5 py-1 rounded bg-emerald-600 text-white">Approve</button></form>
                            <form method="POST" action="{{ route('hr.leaves.update', $lv) }}">@csrf<input type="hidden" name="status" value="Rejected"><button class="px-2.5 py-1 rounded bg-rose-600 text-white">Reject</button></form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

