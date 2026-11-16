@extends('layouts.app')
@section('title', 'Placement & Career Management')
@section('header', 'Placement Drives, Internships, Campus Recruitment & Offers')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Schedule Placement / Internship Drive</h3>
        <form method="POST" action="{{ route('placements.drives.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <select name="placement_company_id" class="rounded border border-slate-300 px-3 py-2">@foreach($companies as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
            <select name="drive_type" class="rounded border border-slate-300 px-3 py-2"><option>Full-Time</option><option>Internship</option></select>
            <input type="text" name="job_title" placeholder="Job Role *" required class="rounded border border-slate-300 px-3 py-2 col-span-2">
            <input type="number" step="0.1" name="package_lpa" placeholder="Package (LPA) *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="number" step="0.1" name="eligibility_cgpa" value="6.5" placeholder="Min CGPA" class="rounded border border-slate-300 px-3 py-2">
            <input type="date" name="drive_date" required class="rounded border border-slate-300 px-3 py-2">
            <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Publish Drive</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Nominate / Register Student for Drive</h3>
        <form method="POST" action="{{ route('placements.apply') }}" class="space-y-3 text-xs">
            @csrf
            <select name="placement_drive_id" class="w-full rounded border border-slate-300 px-3 py-2">@foreach($drives as $d)<option value="{{ $d->id }}">{{ $d->company->name ?? '' }} - {{ $d->job_title }} ({{ $d->package_lpa }} LPA)</option>@endforeach</select>
            <select name="student_id" class="w-full rounded border border-slate-300 px-3 py-2">@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->unique_student_id }} - {{ $s->full_name }}</option>@endforeach</select>
            <button class="w-full rounded-lg bg-emerald-600 text-white font-semibold py-2">Register Student</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <div class="p-4 border-b font-semibold text-sm">Campus Recruitment &amp; Selection Tracker</div>
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b"><tr><th class="p-3">Student</th><th class="p-3">Company &amp; Role</th><th class="p-3">Package</th><th class="p-3">Status</th><th class="p-3">Update</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($applications as $app)
                <tr>
                    <td class="p-3 font-medium">{{ $app->student->full_name ?? '' }}<div class="text-[10px] text-slate-400">{{ $app->student->course->code ?? '' }}</div></td>
                    <td class="p-3">{{ $app->drive->company->name ?? '' }} &bull; {{ $app->drive->job_title ?? '' }}</td>
                    <td class="p-3 font-semibold text-indigo-700">{{ $app->drive->package_lpa ?? 0 }} LPA</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded {{ $app->status === 'Selected' ? 'bg-emerald-100 text-emerald-800 font-bold' : 'bg-slate-100' }}">{{ $app->status }}</span></td>
                    <td class="p-3">
                        <form method="POST" action="{{ route('placements.status', $app) }}" class="flex gap-1">
                            @csrf
                            <select name="status" class="rounded border border-slate-300 px-1.5 py-1 text-[11px]">
                                @foreach(['Applied','Shortlisted','Interviewed','Selected','Rejected'] as $st)<option @selected($app->status===$st)>{{ $st }}</option>@endforeach
                            </select>
                            <button class="px-2 py-1 rounded bg-slate-800 text-white text-[11px]">Save</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

