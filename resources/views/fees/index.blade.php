@extends('layouts.app')
@section('title', 'Fee & Student Accounts')
@section('header', 'Fee Structures, Student Accounts & Online/Offline Payments')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Configure Course &amp; Semester Fee Structure</h3>
    <form method="POST" action="{{ route('fees.structures.store') }}" class="grid md:grid-cols-6 gap-2.5 text-xs">
        @csrf
        <select name="course_id" class="rounded border border-slate-300 px-2 py-2">@foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->code }}</option>@endforeach</select>
        <input type="number" name="semester" value="1" placeholder="Sem" class="rounded border border-slate-300 px-2 py-2">
        <select name="category" class="rounded border border-slate-300 px-2 py-2"><option>All</option><option>General</option><option>OBC</option><option>SC</option><option>ST</option><option>EWS</option></select>
        <input type="number" name="tuition_fee" value="50000" placeholder="Tuition Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="exam_fee" value="2500" placeholder="Exam Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="library_fee" value="1500" placeholder="Library Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="lab_fee" value="4000" placeholder="Lab Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="admission_fee" value="0" placeholder="Admission Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="hostel_fee" value="0" placeholder="Hostel Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="transport_fee" value="0" placeholder="Transport Fee" class="rounded border border-slate-300 px-2 py-2">
        <input type="number" name="misc_fee" value="1000" placeholder="Misc Fee" class="rounded border border-slate-300 px-2 py-2">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Save Structure</button>
    </form>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b border-slate-200 font-semibold text-sm">Student Fee Ledgers &amp; Collection</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b text-slate-600">
                <tr><th class="p-3">Student</th><th class="p-3">Total / Concession</th><th class="p-3">Paid / Balance</th><th class="p-3">Collect Payment</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($accounts as $acc)
                    <tr>
                        <td class="p-3 font-medium">{{ $acc->student->full_name ?? '' }}<div class="text-[10px] text-slate-400">{{ $acc->student->unique_student_id ?? '' }}</div></td>
                        <td class="p-3">₹{{ number_format($acc->total_amount) }}<div class="text-[10px] text-emerald-600">Concession: ₹{{ number_format($acc->concession_amount) }}</div></td>
                        <td class="p-3">₹{{ number_format($acc->paid_amount) }}<div class="font-bold {{ $acc->balance_amount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">Bal: ₹{{ number_format($acc->balance_amount) }}</div></td>
                        <td class="p-3">
                            @if($acc->balance_amount > 0)
                                <form method="POST" action="{{ route('fees.collect', $acc) }}" class="flex gap-1 items-center">
                                    @csrf
                                    <input type="number" name="amount" value="{{ $acc->balance_amount }}" max="{{ $acc->balance_amount }}" class="w-20 rounded border border-slate-300 px-1.5 py-1 text-[11px]">
                                    <select name="payment_mode" class="rounded border border-slate-300 px-1 py-1 text-[11px]"><option>UPI</option><option>Card</option><option>NetBanking</option><option>Cash</option><option>DD</option></select>
                                    <button class="px-2 py-1 rounded bg-emerald-600 text-white text-[11px]">Pay</button>
                                </form>
                            @else
                                <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Paid ✓</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b border-slate-200 font-semibold text-sm">Recent Fee Receipts</div>
        <div class="divide-y divide-slate-100 text-xs">
            @foreach($payments as $p)
                <div class="p-3 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-slate-800">{{ $p->receipt_no }}</div>
                        <div class="text-slate-500">{{ $p->student->full_name ?? '' }} &bull; {{ $p->payment_mode }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-emerald-700">₹{{ number_format($p->amount, 2) }}</div>
                        <a href="{{ route('fees.receipt', $p) }}" target="_blank" class="text-indigo-600 underline text-[11px]">Print Receipt</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

