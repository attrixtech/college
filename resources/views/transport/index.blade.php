@extends('layouts.app')
@section('title', 'Transport Management')
@section('header', 'Module 15: Transport Fleet, Routes, Bus Stops & GPS Integration')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Allocate Student Transport Route</h3>
    <form method="POST" action="{{ route('transport.allocate') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <select name="transport_route_id" class="rounded border border-slate-300 px-3 py-2">@foreach($routes as $r)<option value="{{ $r->id }}">{{ $r->route_name }} (₹{{ $r->monthly_fee }}/mo)</option>@endforeach</select>
        <select name="student_id" class="rounded border border-slate-300 px-3 py-2">@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->unique_student_id }} - {{ $s->full_name }}</option>@endforeach</select>
        <input type="text" name="pickup_stop" placeholder="Pickup Bus Stop *" required class="rounded border border-slate-300 px-3 py-2">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Allocate Transport</button>
    </form>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Vehicles &amp; Routes</h3>
        <div class="space-y-3 text-xs">
            @foreach($routes as $r)
                <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                    <div class="font-bold text-slate-900">{{ $r->route_name }}</div>
                    <div class="text-slate-500 mt-1">Bus: {{ $r->vehicle->registration_no ?? '' }} &bull; Driver: {{ $r->vehicle->driver_name ?? '' }} ({{ $r->vehicle->driver_phone ?? '' }}) &bull; GPS: {{ $r->vehicle->gps_device_id ?? '' }}</div>
                    <div class="mt-1 text-indigo-700">Stops: {{ implode(' → ', $r->stops_json ?? []) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Route-wise Student Allocations</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b"><tr><th class="p-3">Student</th><th class="p-3">Route</th><th class="p-3">Pickup Stop</th><th class="p-3">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($allocations as $al)
                    <tr><td class="p-3 font-medium">{{ $al->student->full_name ?? '' }}</td><td class="p-3">{{ $al->route->route_name ?? '' }}</td><td class="p-3">{{ $al->pickup_stop }}</td><td class="p-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $al->status }}</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

