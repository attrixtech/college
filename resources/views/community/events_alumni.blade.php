@extends('layouts.app')
@section('title', 'Events & Alumni')
@section('header', 'Modules 25 & 26: Campus Events, Clubs & Alumni Association')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Campus Event / Fest / Workshop</h3>
        <form method="POST" action="{{ route('events.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <input type="text" name="title" placeholder="Event Title *" required class="rounded border border-slate-300 px-3 py-2 col-span-2">
            <select name="category" class="rounded border border-slate-300 px-3 py-2"><option>Seminar</option><option>Workshop</option><option>Cultural</option><option>Sports</option><option>Club</option><option>Annual Fest</option></select>
            <input type="date" name="event_date" required class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="venue" placeholder="Venue *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="number" name="budget" value="25000" placeholder="Budget (₹)" class="rounded border border-slate-300 px-3 py-2">
            <button class="col-span-2 rounded-lg bg-indigo-600 text-white font-semibold py-2">Publish Event</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Register Alumni &amp; Mentors</h3>
        <form method="POST" action="{{ route('alumni.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <input type="text" name="full_name" placeholder="Alumnus Name *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="email" name="email" placeholder="Email *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="number" name="graduation_year" value="2023" placeholder="Batch Year" class="rounded border border-slate-300 px-3 py-2">
            <select name="course_id" class="rounded border border-slate-300 px-3 py-2">@foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->code }}</option>@endforeach</select>
            <input type="text" name="current_company" placeholder="Company" class="rounded border border-slate-300 px-3 py-2">
            <input type="number" name="donation_total" value="0" placeholder="Donation (₹)" class="rounded border border-slate-300 px-3 py-2">
            <label class="flex items-center gap-1.5"><input type="checkbox" name="is_mentor" value="1"> Available as Mentor</label>
            <button class="rounded-lg bg-emerald-600 text-white font-semibold py-2">Add Alumnus</button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5 text-xs">
        <h3 class="text-sm font-semibold text-slate-900">Upcoming &amp; Recent Campus Events</h3>
        @foreach($events as $ev)
            <div class="p-3 rounded bg-slate-50 border border-slate-200 flex justify-between">
                <div><strong>{{ $ev->title }}</strong> ({{ $ev->category }})<div class="text-slate-500">{{ $ev->venue }} &bull; Budget: ₹{{ number_format($ev->budget) }}</div></div>
                <span class="font-semibold text-indigo-700">{{ $ev->event_date }}</span>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5 text-xs">
        <h3 class="text-sm font-semibold text-slate-900">Alumni Directory &amp; Mentors</h3>
        @foreach($alumni as $al)
            <div class="p-3 rounded bg-slate-50 border border-slate-200 flex justify-between">
                <div><strong>{{ $al->full_name }}</strong> (Batch {{ $al->graduation_year }})<div class="text-slate-500">{{ $al->designation }} @ {{ $al->current_company }}</div></div>
                <div class="text-right">
                    @if($al->is_mentor)<span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Mentor</span>@endif
                    <div class="text-slate-500 mt-1">Donated: ₹{{ number_format($al->donation_total) }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

