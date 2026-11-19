@extends('layouts.app')
@section('title', 'System Settings & RBAC')
@section('header', 'Multi-Campus, RBAC, Integrations, Audit Logs & Backup')

@section('content')
<!-- Master Institution Brand & Data Tools -->
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <!-- Institution / University Group Master Setting -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-2">Master Institution / University Name</h3>
        <p class="text-xs text-slate-500 mb-3">Overall parent university or institutional group name.</p>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-3 text-xs">
            @csrf
            <input type="hidden" name="group_name" value="general">
            <input type="hidden" name="key" value="institution_name">
            <div>
                <label class="block text-slate-600 mb-1 font-medium">Institution / Group Name</label>
                <input type="text" name="value" value="{{ $settings->firstWhere('key', 'institution_name')?->value ?? 'thrumble.college' }}" required class="w-full rounded border border-slate-300 px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>
            <button class="w-full rounded bg-indigo-600 text-white font-semibold py-2 hover:bg-indigo-700">Save Master Institution Name</button>
        </form>
    </div>

    <!-- Add College under University Group -->
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add New College Unit</h3>
        <form method="POST" action="{{ route('settings.colleges.store') }}" class="space-y-2 text-xs">
            @csrf
            <select name="campus_id" class="w-full rounded border border-slate-300 px-3 py-1.5">
                @foreach($campuses as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="code" placeholder="College Code (e.g. WAIT)" required class="rounded border border-slate-300 px-3 py-1.5">
                <input type="text" name="naac_grade" placeholder="NAAC Grade (A+)" class="rounded border border-slate-300 px-3 py-1.5">
            </div>
            <input type="text" name="name" placeholder="College Full Name" required class="w-full rounded border border-slate-300 px-3 py-1.5">
            <input type="text" name="university_affiliation" placeholder="University Affiliation" class="w-full rounded border border-slate-300 px-3 py-1.5">
            <button class="w-full rounded bg-slate-800 text-white font-semibold py-2 hover:bg-slate-700">Create College Unit</button>
        </form>
    </div>

    <!-- Backup & Export -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 flex flex-col justify-between">
        <div>
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Database Backup &amp; Data Export</h3>
            <p class="text-xs text-slate-500 mb-4">Download institutional JSON database snapshot or export Student SIS records to CSV.</p>
        </div>
        <div class="space-y-2 text-xs">
            <a href="{{ route('settings.backup') }}" class="block text-center w-full rounded-lg bg-emerald-600 text-white font-semibold py-2 hover:bg-emerald-700">Download Database Backup (JSON)</a>
            <a href="{{ route('settings.export_students') }}" class="block text-center w-full rounded-lg border border-slate-300 font-semibold py-2 hover:bg-slate-50">Export Students (CSV)</a>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <!-- Existing Colleges & Edit Forms -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4 text-xs">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Configured Colleges &amp; Institutions</h3>
                <p class="text-[11px] text-slate-500">Click &ldquo;Edit College Name&rdquo; on any college to update its name, code, or details.</p>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800 font-semibold text-[11px]">{{ $colleges->count() }} Colleges</span>
        </div>

        @foreach($colleges as $col)
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200" id="college-card-{{ $col->id }}">
                <div class="flex justify-between items-start gap-3">
                    <div>
                        <div class="text-sm font-bold text-slate-900">{{ $col->name }}</div>
                        <div class="text-slate-500 mt-0.5">Code: <span class="font-semibold text-indigo-700">{{ $col->code }}</span> &bull; Campus: {{ $col->campus->name ?? '' }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $col->university_affiliation ?? 'Affiliation N/A' }} &bull; AISHE: {{ $col->aishe_code ?? 'N/A' }}</div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5">
                        <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-800 font-bold text-[11px]">NAAC {{ $col->naac_grade ?? '-' }}</span>
                        <button type="button" onclick="document.getElementById('edit-college-form-{{ $col->id }}').classList.toggle('hidden')" class="px-2.5 py-1 rounded border border-slate-300 bg-white font-medium hover:bg-slate-100 text-[11px]">
                            ✏️ Edit College Name
                        </button>
                    </div>
                </div>

                <!-- Inline Edit Form -->
                <form id="edit-college-form-{{ $col->id }}" method="POST" action="{{ route('settings.colleges.update', $col) }}" class="hidden mt-3 pt-3 border-t border-slate-200 space-y-2 bg-white p-3 rounded-lg border">
                    @csrf
                    <div class="font-semibold text-slate-800 text-[11px] mb-1">Edit College Information</div>
                    <div>
                        <label class="block text-[11px] text-slate-600 mb-0.5 font-medium">College Name *</label>
                        <input type="text" name="name" value="{{ $col->name }}" required class="w-full rounded border border-slate-300 px-2.5 py-1.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-0.5">College Code *</label>
                            <input type="text" name="code" value="{{ $col->code }}" required class="w-full rounded border border-slate-300 px-2 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-0.5">Campus *</label>
                            <select name="campus_id" class="w-full rounded border border-slate-300 px-2 py-1.5 text-xs">
                                @foreach($campuses as $c)
                                    <option value="{{ $c->id }}" @selected($col->campus_id == $c->id)>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-0.5">University Affiliation</label>
                            <input type="text" name="university_affiliation" value="{{ $col->university_affiliation }}" class="w-full rounded border border-slate-300 px-2 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[11px] text-slate-600 mb-0.5">NAAC Grade</label>
                            <input type="text" name="naac_grade" value="{{ $col->naac_grade }}" class="w-full rounded border border-slate-300 px-2 py-1.5 text-xs">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" onclick="document.getElementById('edit-college-form-{{ $col->id }}').classList.add('hidden')" class="px-3 py-1.5 rounded border border-slate-300 text-slate-600">Cancel</button>
                        <button type="submit" class="px-4 py-1.5 rounded bg-emerald-600 text-white font-semibold hover:bg-emerald-700">Save College Name</button>
                    </div>
                </form>
            </div>
        @endforeach

        <!-- Campuses Section -->
        <div class="border-t pt-3">
            <h4 class="text-xs font-bold text-slate-800 mb-2 uppercase tracking-wide">Campus Locations</h4>
            <div class="space-y-2">
                @foreach($campuses as $camp)
                    <div class="p-2.5 rounded border border-slate-200 flex justify-between items-center bg-slate-50">
                        <div>
                            <strong>{{ $camp->name }}</strong> ({{ $camp->code }})
                            <div class="text-[11px] text-slate-500">{{ $camp->city }}, {{ $camp->state }}</div>
                        </div>
                        <button type="button" onclick="document.getElementById('edit-campus-form-{{ $camp->id }}').classList.toggle('hidden')" class="px-2 py-1 rounded border border-slate-300 bg-white hover:bg-slate-100 text-[11px]">
                            Edit Campus
                        </button>
                    </div>
                    <form id="edit-campus-form-{{ $camp->id }}" method="POST" action="{{ route('settings.campuses.update', $camp) }}" class="hidden p-3 rounded border border-slate-200 bg-white space-y-2">
                        @csrf
                        <div class="font-semibold text-slate-800 text-[11px]">Edit Campus Location</div>
                        <div>
                            <label class="block text-[11px] text-slate-600">Campus Name</label>
                            <input type="text" name="name" value="{{ $camp->name }}" required class="w-full rounded border border-slate-300 px-2 py-1 text-xs">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="code" value="{{ $camp->code }}" required placeholder="Code" class="rounded border border-slate-300 px-2 py-1 text-xs">
                            <input type="text" name="city" value="{{ $camp->city }}" required placeholder="City" class="rounded border border-slate-300 px-2 py-1 text-xs">
                        </div>
                        <input type="text" name="state" value="{{ $camp->state }}" required placeholder="State" class="w-full rounded border border-slate-300 px-2 py-1 text-xs">
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="document.getElementById('edit-campus-form-{{ $camp->id }}').classList.add('hidden')" class="px-3 py-1 rounded border text-[11px]">Cancel</button>
                            <button type="submit" class="px-3 py-1 rounded bg-indigo-600 text-white text-[11px]">Save Campus</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <!-- System Roles & Audit Logs -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5 text-xs">
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Role-Based Access Control (RBAC)</h3>
            <div class="grid grid-cols-2 gap-2 mt-3">
                @foreach($roles as $role)
                    <div class="p-2.5 rounded bg-slate-50 border border-slate-200">
                        <strong class="text-slate-800">{{ $role->name }}</strong>
                        <div class="text-[11px] text-indigo-700 font-mono mt-0.5">{{ $role->slug }}</div>
                        <div class="text-[11px] text-slate-500 mt-1">{{ $role->description }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
            <div class="p-4 border-b font-semibold text-sm">System Audit Logs</div>
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b text-slate-600"><tr><th class="p-3">User</th><th class="p-3">Action</th><th class="p-3">Description</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($auditLogs as $log)
                        <tr><td class="p-3 font-medium">{{ $log->user->name ?? 'System' }}</td><td class="p-3 font-semibold text-indigo-700">{{ $log->action }}</td><td class="p-3">{{ $log->description }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
