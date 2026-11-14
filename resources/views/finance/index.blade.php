@extends('layouts.app')
@section('title', 'College Finance & Accounting')
@section('header', 'Module 11: General Ledger, Vouchers (GST/TDS), Budgets & Financial Statements')

@section('content')
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-slate-200"><div class="text-xs text-slate-500">Total Income (Ledger)</div><div class="text-2xl font-bold text-emerald-700">₹{{ number_format($totalIncome, 2) }}</div></div>
    <div class="bg-white p-4 rounded-xl border border-slate-200"><div class="text-xs text-slate-500">Total Expenses (Ledger)</div><div class="text-2xl font-bold text-rose-600">₹{{ number_format($totalExpense, 2) }}</div></div>
    <div class="bg-white p-4 rounded-xl border border-slate-200"><div class="text-xs text-slate-500">Total Assets &amp; Cash/Bank</div><div class="text-2xl font-bold text-indigo-700">₹{{ number_format($totalAssets, 2) }}</div></div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-900 mb-3">Post Financial Voucher (Payment / Receipt / Contra / Journal)</h3>
    <form method="POST" action="{{ route('finance.vouchers.store') }}" class="grid md:grid-cols-4 gap-3 text-xs">
        @csrf
        <select name="voucher_type" class="rounded border border-slate-300 px-3 py-2"><option>Payment</option><option>Receipt</option><option>Contra</option><option>Journal</option></select>
        <input type="date" name="voucher_date" value="{{ now()->toDateString() }}" class="rounded border border-slate-300 px-3 py-2">
        <select name="debit_account_id" class="rounded border border-slate-300 px-3 py-2">@foreach($accounts as $a)<option value="{{ $a->id }}">Dr: {{ $a->code }} - {{ $a->name }}</option>@endforeach</select>
        <select name="credit_account_id" class="rounded border border-slate-300 px-3 py-2">@foreach($accounts as $a)<option value="{{ $a->id }}">Cr: {{ $a->code }} - {{ $a->name }}</option>@endforeach</select>
        <input type="number" step="0.01" name="amount" placeholder="Amount (₹) *" required class="rounded border border-slate-300 px-3 py-2">
        <input type="number" step="0.01" name="gst_amount" value="0" placeholder="GST (₹)" class="rounded border border-slate-300 px-3 py-2">
        <input type="number" step="0.01" name="tds_amount" value="0" placeholder="TDS (₹)" class="rounded border border-slate-300 px-3 py-2">
        <select name="department_id" class="rounded border border-slate-300 px-3 py-2"><option value="">Department</option>@foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->code }}</option>@endforeach</select>
        <input type="text" name="narration" placeholder="Narration / Particulars *" required class="rounded border border-slate-300 px-3 py-2 md:col-span-3">
        <button class="rounded-lg bg-indigo-600 text-white font-semibold py-2">Post Voucher</button>
    </form>
</div>

<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Chart of Accounts &amp; Trial Balance</div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b text-slate-600"><tr><th class="p-3">Code</th><th class="p-3">Account</th><th class="p-3">Type</th><th class="p-3">Balance (₹)</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($accounts as $a)
                    <tr><td class="p-3 font-semibold">{{ $a->code }}</td><td class="p-3">{{ $a->name }}</td><td class="p-3">{{ $a->account_type }}</td><td class="p-3 font-bold">₹{{ number_format($a->balance, 2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b font-semibold text-sm">Department Budgets &amp; Fixed Assets</div>
        <div class="p-4 space-y-3 text-xs">
            @foreach($budgets as $b)
                <div class="p-3 rounded bg-slate-50 border border-slate-200">
                    <div class="font-semibold">{{ $b->department->name ?? '' }} ({{ $b->financial_year }})</div>
                    <div class="text-slate-600 mt-1">Allocated: ₹{{ number_format($b->allocated_amount) }} &bull; Utilized: ₹{{ number_format($b->utilized_amount) }}</div>
                </div>
            @endforeach
            <div class="font-semibold pt-2">Fixed Assets &amp; Depreciation</div>
            @foreach($assets as $ast)
                <div class="p-2 rounded border border-slate-200 flex justify-between">
                    <span>{{ $ast->asset_code }} - {{ $ast->name }}</span>
                    <span class="font-semibold">Value: ₹{{ number_format($ast->current_value) }} ({{ $ast->depreciation_rate }}% dep)</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <div class="p-4 border-b font-semibold text-sm">Recent Vouchers (Cashbook / Bankbook / Journal)</div>
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b text-slate-600"><tr><th class="p-3">Voucher No</th><th class="p-3">Type &amp; Date</th><th class="p-3">Debit / Credit</th><th class="p-3">Amount / GST / TDS</th><th class="p-3">Narration</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($vouchers as $v)
                <tr>
                    <td class="p-3 font-semibold">{{ $v->voucher_no }}</td>
                    <td class="p-3">{{ $v->voucher_type }} ({{ $v->voucher_date }})</td>
                    <td class="p-3">Dr: {{ $v->debitAccount->name ?? '' }}<div class="text-slate-500">Cr: {{ $v->creditAccount->name ?? '' }}</div></td>
                    <td class="p-3 font-bold">₹{{ number_format($v->amount, 2) }}<div class="text-[10px] font-normal text-slate-500">GST: ₹{{ $v->gst_amount }} | TDS: ₹{{ $v->tds_amount }}</div></td>
                    <td class="p-3">{{ $v->narration }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

