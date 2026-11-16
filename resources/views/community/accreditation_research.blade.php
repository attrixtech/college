@extends('layouts.app')
@section('title', 'Accreditation & Research')
@section('header', 'NAAC / NIRF / AISHE / IQAC Compliance & Research / Patents')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add NAAC / NIRF / AISHE / UGC / AICTE / NBA Record</h3>
        <form method="POST" action="{{ route('accreditation.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <select name="framework" class="rounded border border-slate-300 px-3 py-2"><option>NAAC</option><option>NIRF</option><option>AISHE</option><option>UGC</option><option>AICTE</option><option>NBA</option><option>IQAC</option></select>
            <input type="text" name="cycle_year" value="2026-27" placeholder="Cycle Year" required class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="criterion" placeholder="Criterion / Metric *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="metric_code" placeholder="Code (e.g. 1.1.1)" class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="title" placeholder="Title / Evidence Description *" required class="rounded border border-slate-300 px-3 py-2 col-span-2">
            <input type="text" name="score_or_value" placeholder="Score / Value (e.g. 3.65 / 4.00)" class="rounded border border-slate-300 px-3 py-2">
            <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Save Compliance</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add Research Publication / Grant / Patent</h3>
        <form method="POST" action="{{ route('research.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <select name="record_type" class="rounded border border-slate-300 px-3 py-2"><option>Faculty Publication</option><option>Student Project</option><option>Research Grant</option><option>Patent</option><option>Conference</option><option>Incubation</option></select>
            <select name="status" class="rounded border border-slate-300 px-3 py-2"><option>Published</option><option>Granted</option><option>Ongoing</option><option>Completed</option></select>
            <input type="text" name="title" placeholder="Research / Patent Title *" required class="rounded border border-slate-300 px-3 py-2 col-span-2">
            <input type="text" name="lead_name" placeholder="Principal Investigator / Author *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="funding_agency" placeholder="Funding Agency (DST/AICTE)" class="rounded border border-slate-300 px-3 py-2">
            <input type="number" name="grant_amount" value="0" placeholder="Grant Amount (₹)" class="rounded border border-slate-300 px-3 py-2">
            <input type="number" name="year" value="2026" class="rounded border border-slate-300 px-3 py-2">
            <button class="col-span-2 rounded-lg bg-emerald-600 text-white font-semibold py-2">Save Research Record</button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5 text-xs">
        <h3 class="text-sm font-semibold text-slate-900">Accreditation &amp; Statutory Compliance Repository</h3>
        @foreach($accreditations as $acc)
            <div class="p-3 rounded bg-slate-50 border border-slate-200 flex justify-between">
                <div><span class="px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-800 font-bold">{{ $acc->framework }}</span> <strong>{{ $acc->title }}</strong><div class="text-slate-500 mt-0.5">{{ $acc->criterion }} ({{ $acc->cycle_year }})</div></div>
                <span class="font-bold text-emerald-700">{{ $acc->score_or_value }}</span>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5 text-xs">
        <h3 class="text-sm font-semibold text-slate-900">Research, Grants &amp; Patents</h3>
        @foreach($research as $res)
            <div class="p-3 rounded bg-slate-50 border border-slate-200 flex justify-between">
                <div><span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">{{ $res->record_type }}</span> <strong>{{ $res->title }}</strong><div class="text-slate-500 mt-0.5">Lead: {{ $res->lead_name }} &bull; {{ $res->funding_agency ?? $res->patent_no ?? $res->year }}</div></div>
                <span class="font-bold text-indigo-700">{{ $res->grant_amount > 0 ? '₹'.number_format($res->grant_amount) : $res->status }}</span>
            </div>
        @endforeach
    </div>
</div>
@endsection

