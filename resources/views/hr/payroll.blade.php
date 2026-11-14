@extends('layouts.app')
@section('title', 'Payroll Management')
@section('header', 'Module 13: Payroll Processing (PF, ESI, Professional Tax, TDS & Payslips)')

@section('content')
<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6 flex flex-wrap items-center justify-between gap-4">
    <form method="GET" class="flex items-center gap-2 text-xs">
        <select name="month" class="rounded border border-slate-300 px-3 py-2">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($month == $m)>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
            @endfor
        </select>
        <input type="number" name="year" value="{{ $year }}" class="w-24 rounded border border-slate-300 px-3 py-2">
        <button class="px-4 py-2 rounded bg-slate-800 text-white font-semibold">View Month</button>
    </form>

    <form method="POST" action="{{ route('payroll.process') }}">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">
        <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700">Process Monthly Payroll ({{ $month }}/{{ $year }})</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
    <table class="w-full text-left text-xs">
        <thead class="bg-slate-50 border-b text-slate-600 uppercase">
            <tr>
                <th class="p-3">Employee</th>
                <th class="p-3">Month / Year</th>
                <th class="p-3">Gross Salary</th>
                <th class="p-3">PF / ESI / PT / TDS</th>
                <th class="p-3">Net Salary</th>
                <th class="p-3">Bank Ref</th>
                <th class="p-3">Payslip</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach(($payrolls->isNotEmpty() ? $payrolls : $allPayrolls) as $pr)
                <tr>
                    <td class="p-3 font-medium">{{ $pr->employee->full_name ?? '' }}<div class="text-[10px] text-slate-400">{{ $pr->employee->staff_id ?? '' }}</div></td>
                    <td class="p-3">{{ $pr->month }}/{{ $pr->year }}</td>
                    <td class="p-3">₹{{ number_format($pr->gross_salary, 2) }}</td>
                    <td class="p-3 text-slate-500">PF: ₹{{ number_format($pr->pf_amount) }} | PT: ₹{{ number_format($pr->professional_tax) }} | TDS: ₹{{ number_format($pr->tds_amount) }}</td>
                    <td class="p-3 font-bold text-emerald-700">₹{{ number_format($pr->net_salary, 2) }}</td>
                    <td class="p-3">{{ $pr->bank_ref }}</td>
                    <td class="p-3"><a href="{{ route('payroll.payslip', $pr) }}" target="_blank" class="text-indigo-600 underline font-medium">Print Payslip</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

