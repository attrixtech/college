@extends('layouts.app')
@section('title', 'Certificates & Document Management')
@section('header', 'Digital Certificates (Bonafide / TC / Migration / NOC) & QR Verification')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Generate Digital Certificate</h3>
    <form method="POST" action="{{ route('certificates.store') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <select name="student_id" class="rounded border border-slate-300 px-3 py-2">@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->unique_student_id }} - {{ $s->full_name }}</option>@endforeach</select>
        <select name="certificate_type" class="rounded border border-slate-300 px-3 py-2">
            <option>Bonafide</option><option>Transfer</option><option>Migration</option><option>Character</option><option>NOC</option><option>Course Completion</option><option>Provisional</option><option>Degree</option><option>Duplicate</option>
        </select>
        <input type="text" name="remarks" placeholder="Purpose / Remarks" class="rounded border border-slate-300 px-3 py-2">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Issue &amp; Generate QR</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b"><tr><th class="p-3">Cert No</th><th class="p-3">Student</th><th class="p-3">Type</th><th class="p-3">Issue Date</th><th class="p-3">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($certificates as $c)
                <tr>
                    <td class="p-3 font-semibold text-indigo-700">{{ $c->certificate_no }}</td>
                    <td class="p-3">{{ $c->student->full_name ?? '' }}</td>
                    <td class="p-3">{{ $c->certificate_type }}</td>
                    <td class="p-3">{{ $c->issue_date }}</td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('certificates.print', $c) }}" target="_blank" class="px-2.5 py-1 rounded bg-indigo-50 text-indigo-700 font-medium">Print Certificate</a>
                        <a href="{{ route('certificates.verify', $c->verification_token) }}" target="_blank" class="px-2.5 py-1 rounded border border-slate-300">QR Verify Link</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

