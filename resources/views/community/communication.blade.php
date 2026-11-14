@extends('layouts.app')
@section('title', 'Communication & Notifications')
@section('header', 'Module 29: Announcements, SMS, Email, WhatsApp & Emergency Alerts')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Broadcast Announcement / Multi-Channel Alert</h3>
    <form method="POST" action="{{ route('announcements.store') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <input type="text" name="title" placeholder="Announcement Title *" required class="rounded border border-slate-300 px-3 py-2 md:col-span-2">
        <select name="target_audience" class="rounded border border-slate-300 px-3 py-2"><option>All</option><option>Students</option><option>Faculty</option><option>Parents</option><option>Department</option></select>
        <select name="channel" class="rounded border border-slate-300 px-3 py-2"><option>Portal</option><option>WhatsApp</option><option>SMS</option><option>Email</option><option>Push</option></select>
        <textarea name="message" rows="2" placeholder="Message content..." required class="rounded border border-slate-300 px-3 py-2 md:col-span-3"></textarea>
        <div class="flex flex-col justify-between">
            <label class="flex items-center gap-1.5 text-rose-700 font-medium"><input type="checkbox" name="is_emergency" value="1"> Emergency Alert</label>
            <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Broadcast Now</button>
        </div>
    </form>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5 text-xs">
        <h3 class="text-sm font-semibold text-slate-900">Published Announcements</h3>
        @foreach($announcements as $ann)
            <div class="p-3 rounded border {{ $ann->is_emergency ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-200' }}">
                <div class="flex justify-between font-bold"><span>{{ $ann->title }}</span><span>{{ $ann->target_audience }} ({{ $ann->channel }})</span></div>
                <div class="text-slate-600 mt-1">{{ $ann->message }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">SMS / Email / WhatsApp Dispatch Log</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b"><tr><th class="p-3">Recipient</th><th class="p-3">Channel</th><th class="p-3">Subject</th><th class="p-3">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($logs as $log)
                    <tr><td class="p-3">{{ $log->recipient_name }}<div class="text-[10px] text-slate-400">{{ $log->recipient_contact }}</div></td><td class="p-3">{{ $log->channel }}</td><td class="p-3">{{ $log->subject }}</td><td class="p-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">{{ $log->status }}</span></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

