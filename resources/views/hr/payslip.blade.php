@extends('layouts.app')
@section('title', 'Salary Payslip')
@section('header', 'Employee Salary Payslip')

@section('content')
<div class="max-w-2xl mx-auto bg-white border border-slate-300 rounded-xl p-8">
    <div class="flex justify-between items-start border-b border-slate-200 pb-4 mb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ $payroll->employee->college->name ?? 'thrumble.college' }}</h2>
            <p class="text-xs text-slate-500">Salary Payslip for {{ date('F', mktime(0,0,0,$payroll->month,1)) }} {{ $payroll->year }}</p>
        </div>
        <button onclick="window.print()" class="no-print px-3 py-1.5 rounded bg-indigo-600 text-white text-xs font-semibold">Print Payslip</button>
    </div>

    <div class="grid grid-cols-2 gap-2 text-xs mb-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
        <div><strong>Employee Name:</strong> {{ $payroll->employee->full_name }}</div>
        <div><strong>Staff ID:</strong> {{ $payroll->employee->staff_id }}</div>
        <div><strong>Designation:</strong> {{ $payroll->employee->designation }}</div>
        <div><strong>Department:</strong> {{ $payroll->employee->department->name ?? 'Admin' }}</div>
        <div><strong>UAN / PAN:</strong> {{ $payroll->employee->uan_number ?? 'N/A' }} / {{ $payroll->employee->pan_number ?? 'N/A' }}</div>
        <div><strong>Bank Ref:</strong> {{ $payroll->bank_ref }}</div>
    </div>

    <div class="grid grid-cols-2 gap-4 text-xs mb-6">
        <div class="border border-slate-200 rounded-lg p-4 space-y-2">
            <div class="font-bold text-slate-800 border-b pb-1">Earnings</div>
            <div class="flex justify-between"><span>Gross Salary (Basic + HRA + DA + TA)</span><strong>₹{{ number_format($payroll->gross_salary, 2) }}</strong></div>
            <div class="flex justify-between"><span>Arrears</span><span>₹{{ number_format($payroll->arrears, 2) }}</span></div>
        </div>
        <div class="border border-slate-200 rounded-lg p-4 space-y-2">
            <div class="font-bold text-slate-800 border-b pb-1">Statutory Deductions</div>
            <div class="flex justify-between"><span>Provident Fund (PF)</span><span>₹{{ number_format($payroll->pf_amount, 2) }}</span></div>
            <div class="flex justify-between"><span>ESI</span><span>₹{{ number_format($payroll->esi_amount, 2) }}</span></div>
            <div class="flex justify-between"><span>Professional Tax (PT)</span><span>₹{{ number_format($payroll->professional_tax, 2) }}</span></div>
            <div class="flex justify-between"><span>TDS</span><span>₹{{ number_format($payroll->tds_amount, 2) }}</span></div>
        </div>
    </div>

    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex justify-between items-center text-base font-bold text-emerald-900">
        <span>Net Salary Credited</span>
        <span>₹{{ number_format($payroll->net_salary, 2) }}</span>
    </div>
</div>
@endsection

