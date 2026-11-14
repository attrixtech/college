@extends('layouts.app')
@section('title', 'Scholarships & Financial Aid')
@section('header', 'Module 22: Government (NSP/SC/ST/OBC/EWS) & Institutional Scholarships')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Submit Scholarship Application</h3>
    <form method="POST" action="{{ route('scholarships.apply') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <select name="scholarship_id" class="rounded border border-slate-300 px-3 py-2">@foreach($scholarships as $sch)<option value="{{ $sch->id }}">{{ $sch->name }} (₹{{ number_format($sch->amount) }})</option>@endforeach</select>
        <select name="student_id" class="rounded border border-slate-300 px-3 py-2">@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->unique_student_id }} - {{ $s->full_name }} ({{ $s->category }})</option>@endforeach</select>
        <input type="text" name="remarks" placeholder="Income / Merit Remarks" class="rounded border border-slate-300 px-3 py-2">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Apply for Scholarship</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b"><tr><th class="p-3">Student</th><th class="p-3">Scheme</th><th class="p-3">Amount</th><th class="p-3">Status</th><th class="p-3">Workflow</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($applications as $app)
                <tr>
                    <td class="p-3 font-medium">{{ $app->student->full_name ?? '' }} ({{ $app->student->category ?? '' }})</td>
                    <td class="p-3">{{ $app->scholarship->name ?? '' }} <div class="text-[10px] text-slate-400">{{ $app->scholarship->scheme_type ?? '' }}</div></td>
                    <td class="p-3 font-bold">₹{{ number_format($app->scholarship->amount ?? 0) }}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 font-medium">{{ $app->status }}</span></td>
                    <td class="p-3">
                        <form method="POST" action="{{ route('scholarships.status', $app) }}" class="flex gap-1">
                            @csrf
                            <select name="status" class="rounded border border-slate-300 px-1.5 py-1 text-[11px]">
                                @foreach(['Applied','Verified','Approved','Disbursed','Rejected'] as $st)<option @selected($app->status===$st)>{{ $st }}</option>@endforeach
                            </select>
                            <button class="px-2 py-1 rounded bg-emerald-600 text-white text-[11px]">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

