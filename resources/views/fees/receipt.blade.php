@extends('layouts.app')
@section('title', 'Fee Receipt')
@section('header', 'Official Fee Payment Receipt')

@section('content')
<div class="max-w-2xl mx-auto bg-white border border-slate-300 rounded-xl p-8">
    <div class="flex justify-between items-start border-b border-slate-200 pb-4 mb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ $payment->student->college->name ?? 'VidyaVihar Institute of Technology' }}</h2>
            <p class="text-xs text-slate-500">Official Student Fee Receipt</p>
        </div>
        <button onclick="window.print()" class="no-print px-3 py-1.5 rounded bg-indigo-600 text-white text-xs font-semibold">Print Receipt</button>
    </div>

    <div class="grid grid-cols-2 gap-3 text-sm mb-6">
        <div><strong>Receipt No:</strong> {{ $payment->receipt_no }}</div>
        <div><strong>Payment Date:</strong> {{ $payment->payment_date }}</div>
        <div><strong>Student Name:</strong> {{ $payment->student->full_name }}</div>
        <div><strong>Student ID:</strong> {{ $payment->student->unique_student_id }}</div>
        <div><strong>Programme:</strong> {{ $payment->student->course->name }}</div>
        <div><strong>Payment Mode:</strong> {{ $payment->payment_mode }} ({{ $payment->transaction_ref }})</div>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex justify-between items-center text-base font-bold text-emerald-900 mb-6">
        <span>Amount Received</span>
        <span>₹{{ number_format($payment->amount, 2) }}</span>
    </div>

    <div class="flex justify-between text-xs text-slate-500 pt-4 border-t border-slate-200">
        <span>Balance Remaining: ₹{{ number_format($payment->feeAccount->balance_amount ?? 0, 2) }}</span>
        <span>Accounts Officer</span>
    </div>
</div>
@endsection

