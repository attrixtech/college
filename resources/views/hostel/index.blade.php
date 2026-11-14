@extends('layouts.app')
@section('title', 'Hostel Management')
@section('header', 'Module 14: Boys & Girls Hostels, Room Allocation, Mess & Outing Logs')

@section('content')
<div class="grid md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Allocate Hostel Room &amp; Bed</h3>
        <form method="POST" action="{{ route('hostel.allocate') }}" class="space-y-3 text-xs">
            @csrf
            <select name="hostel_room_id" class="w-full rounded border border-slate-300 px-3 py-2">
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}">{{ $r->hostel->name ?? '' }} - Room {{ $r->room_no }} ({{ $r->occupied_beds }}/{{ $r->capacity }} beds)</option>
                @endforeach
            </select>
            <select name="student_id" class="w-full rounded border border-slate-300 px-3 py-2">
                @foreach($students as $s)<option value="{{ $s->id }}">{{ $s->unique_student_id }} - {{ $s->full_name }}</option>@endforeach
            </select>
            <div class="grid grid-cols-2 gap-2">
                <input type="text" name="bed_no" placeholder="Bed No (e.g. B2)" required class="rounded border border-slate-300 px-3 py-2">
                <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Allocate Bed</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Log Visitor / Complaint / Outing / Maintenance</h3>
        <form method="POST" action="{{ route('hostel.activities.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <select name="hostel_id" class="rounded border border-slate-300 px-2 py-2">@foreach($hostels as $h)<option value="{{ $h->id }}">{{ $h->name }}</option>@endforeach</select>
            <select name="activity_type" class="rounded border border-slate-300 px-2 py-2"><option>Complaint</option><option>Outing Request</option><option>Visitor</option><option>Maintenance</option><option>Attendance</option></select>
            <select name="student_id" class="rounded border border-slate-300 px-2 py-2 col-span-2"><option value="">Select Student (Optional)</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->full_name }}</option>@endforeach</select>
            <input type="text" name="title" placeholder="Title / Summary *" required class="rounded border border-slate-300 px-2 py-2 col-span-2">
            <input type="text" name="details" placeholder="Details..." class="rounded border border-slate-300 px-2 py-2 col-span-2">
            <button class="col-span-2 rounded-lg bg-slate-800 text-white font-semibold py-2">Submit Log</button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Hostels &amp; Active Allocations</div>
        <div class="p-4 space-y-2 text-xs">
            @foreach($hostels as $h)
                <div class="p-3 rounded bg-slate-50 border border-slate-200 flex justify-between">
                    <div><strong>{{ $h->name }}</strong> ({{ $h->type }})<div class="text-slate-500">Warden: {{ $h->warden->full_name ?? 'Assigned' }}</div></div>
                    <div class="text-right">Fee: ₹{{ number_format($h->monthly_fee) }}/mo<div class="text-slate-500">Mess: ₹{{ number_format($h->mess_fee) }}/mo</div></div>
                </div>
            @endforeach
            <div class="font-semibold pt-2">Allocated Students</div>
            @foreach($allocations as $al)
                <div class="p-2 rounded border border-slate-100 flex justify-between">
                    <span>{{ $al->student->full_name ?? '' }}</span>
                    <span class="font-semibold">{{ $al->hostel->name ?? '' }} &bull; Room {{ $al->room->room_no ?? '' }} ({{ $al->bed_no }})</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Visitor, Outing &amp; Complaint Register</div>
        <div class="divide-y divide-slate-100 text-xs">
            @foreach($activities as $act)
                <div class="p-3">
                    <div class="flex justify-between"><strong>{{ $act->title }}</strong><span class="px-2 py-0.5 rounded bg-slate-100">{{ $act->activity_type }} &bull; {{ $act->status }}</span></div>
                    <div class="text-slate-500 mt-0.5">{{ $act->hostel->name ?? '' }} {{ $act->student ? '• '.$act->student->full_name : '' }}</div>
                    <div class="text-slate-600 mt-1">{{ $act->details }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

