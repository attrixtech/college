@extends('layouts.app')
@section('title', 'Grievance & Discipline')
@section('header', 'Student Grievance Redressal, Anti-Ragging & Discipline')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Submit Grievance / Anti-Ragging Complaint</h3>
        <form method="POST" action="{{ route('grievances.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-2">
                <select name="student_id" class="rounded border border-slate-300 px-3 py-2"><option value="">Anonymous / Student</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }}</option>@endforeach</select>
                <select name="category" class="rounded border border-slate-300 px-3 py-2"><option>General</option><option>Anti-Ragging</option><option>Harassment</option><option>Academic</option><option>Hostel</option><option>Fee</option></select>
            </div>
            <input type="text" name="subject" placeholder="Complaint Subject *" required class="w-full rounded border border-slate-300 px-3 py-2">
            <textarea name="description" rows="2" placeholder="Describe incident..." required class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-1.5"><input type="checkbox" name="is_confidential" value="1"> Confidential Committee Handling</label>
                <button class="px-4 py-2 rounded-lg bg-rose-600 text-white font-semibold">Submit Complaint</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Log Disciplinary &amp; Counselling Record</h3>
        <form method="POST" action="{{ route('disciplinary.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <div class="grid grid-cols-2 gap-2">
                <select name="student_id" class="rounded border border-slate-300 px-3 py-2">@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }}</option>@endforeach</select>
                <input type="date" name="incident_date" value="{{ now()->toDateString() }}" class="rounded border border-slate-300 px-3 py-2">
            </div>
            <input type="text" name="action_taken" placeholder="Action Taken / Warning *" required class="w-full rounded border border-slate-300 px-3 py-2">
            <textarea name="description" rows="2" placeholder="Incident & Counselling notes..." required class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
            <button class="w-full rounded-lg bg-slate-800 text-white font-semibold py-2">Save Disciplinary Record</button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b"><tr><th class="p-3">Ticket</th><th class="p-3">Category</th><th class="p-3">Subject</th><th class="p-3">Status</th><th class="p-3">Resolution</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($grievances as $g)
                <tr>
                    <td class="p-3 font-semibold">{{ $g->ticket_no }}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded {{ in_array($g->category, ['Anti-Ragging','Harassment']) ? 'bg-rose-100 text-rose-800 font-semibold' : 'bg-slate-100' }}">{{ $g->category }}</span></td>
                    <td class="p-3"><div class="font-medium">{{ $g->subject }}</div><div class="text-slate-500">{{ $g->is_confidential ? 'Confidential' : ($g->student->full_name ?? 'Anonymous') }}</div></td>
                    <td class="p-3">{{ $g->status }}</td>
                    <td class="p-3">
                        <form method="POST" action="{{ route('grievances.resolve', $g) }}" class="flex gap-1">
                            @csrf
                            <input type="text" name="resolution_notes" value="{{ $g->resolution_notes }}" placeholder="Resolution note..." class="rounded border border-slate-300 px-2 py-1 text-[11px]">
                            <select name="status" class="rounded border border-slate-300 px-1.5 py-1 text-[11px]"><option>Under Review</option><option>Resolved</option><option>Closed</option></select>
                            <button class="px-2 py-1 rounded bg-emerald-600 text-white text-[11px]">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

