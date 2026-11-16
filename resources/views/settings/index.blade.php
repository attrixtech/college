@extends('layouts.app')
@section('title', 'System Settings & RBAC')
@section('header', 'Multi-Campus, RBAC, Integrations, Audit Logs & Backup')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add Campus</h3>
        <form method="POST" action="{{ route('settings.campuses.store') }}" class="space-y-2 text-xs">
            @csrf
            <input type="text" name="code" placeholder="Campus Code (e.g. DEL-03)" required class="w-full rounded border border-slate-300 px-3 py-1.5">
            <input type="text" name="name" placeholder="Campus Name" required class="w-full rounded border border-slate-300 px-3 py-1.5">
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="city" placeholder="City" required class="rounded border border-slate-300 px-3 py-1.5">
                <input type="text" name="state" placeholder="State" required class="rounded border border-slate-300 px-3 py-1.5">
            </div>
            <button class="w-full rounded bg-indigo-600 text-white font-semibold py-2">Add Campus</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add College under University Group</h3>
        <form method="POST" action="{{ route('settings.colleges.store') }}" class="space-y-2 text-xs">
            @csrf
            <select name="campus_id" class="w-full rounded border border-slate-300 px-3 py-1.5">@foreach($campuses as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="code" placeholder="College Code" required class="rounded border border-slate-300 px-3 py-1.5">
                <input type="text" name="naac_grade" placeholder="NAAC Grade (A+)" class="rounded border border-slate-300 px-3 py-1.5">
            </div>
            <input type="text" name="name" placeholder="College Name" required class="w-full rounded border border-slate-300 px-3 py-1.5">
            <button class="w-full rounded bg-slate-800 text-white font-semibold py-2">Add College</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col justify-between">
        <div>
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Database Backup &amp; Data Export</h3>
            <p class="text-xs text-slate-500 mb-4">Download institutional JSON backup or export Student SIS records to CSV.</p>
        </div>
        <div class="space-y-2 text-xs">
            <a href="{{ route('settings.backup') }}" class="block text-center w-full rounded-lg bg-emerald-600 text-white font-semibold py-2 hover:bg-emerald-700">Download Database Backup (JSON)</a>
            <a href="{{ route('settings.export_students') }}" class="block text-center w-full rounded-lg border border-slate-300 font-semibold py-2 hover:bg-slate-50">Export Students (CSV)</a>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3 text-xs">
        <h3 class="text-sm font-semibold text-slate-900">Campuses, Colleges &amp; Roles (RBAC)</h3>
        @foreach($colleges as $col)
            <div class="p-3 rounded bg-slate-50 border border-slate-200 flex justify-between">
                <div><strong>{{ $col->name }}</strong> ({{ $col->code }})<div class="text-slate-500">{{ $col->campus->name ?? '' }} &bull; AISHE: {{ $col->aishe_code ?? 'N/A' }}</div></div>
                <span class="px-2 py-0.5 h-fit rounded bg-indigo-100 text-indigo-800 font-bold">NAAC {{ $col->naac_grade ?? '-' }}</span>
            </div>
        @endforeach

        <div class="font-semibold pt-2">System Roles</div>
        <div class="grid grid-cols-2 gap-2">
            @foreach($roles as $role)
                <div class="p-2 rounded border border-slate-200"><strong>{{ $role->name }}</strong><div class="text-[11px] text-slate-500">{{ $role->slug }}</div></div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">System Audit Logs</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b"><tr><th class="p-3">User</th><th class="p-3">Action</th><th class="p-3">Description</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($auditLogs as $log)
                    <tr><td class="p-3 font-medium">{{ $log->user->name ?? 'System' }}</td><td class="p-3 font-semibold text-indigo-700">{{ $log->action }}</td><td class="p-3">{{ $log->description }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

