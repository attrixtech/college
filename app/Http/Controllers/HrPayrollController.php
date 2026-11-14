<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeEvaluation;
use App\Models\EmployeeLeave;
use App\Models\Payroll;
use App\Models\Role;
use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class HrPayrollController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with(['department', 'college', 'salaryStructure'])->latest()->get();
        $leaves = EmployeeLeave::with('employee')->latest()->get();
        $evaluations = EmployeeEvaluation::with('employee')->latest()->get();
        $departments = Department::all();
        $colleges = College::all();

        return view('hr.index', compact('employees', 'leaves', 'evaluations', 'departments', 'colleges'));
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'department_id' => 'nullable|exists:departments,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'employee_type' => 'required|in:Faculty,Non-Teaching,Admin',
            'designation' => 'required|string|max:100',
            'qualification' => 'nullable|string|max:255',
            'experience_years' => 'integer|min:0|max:50',
            'joining_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:5000',
        ]);

        $roleSlug = $data['employee_type'] === 'Faculty' ? 'faculty' : 'super_admin';
        $role = Role::where('slug', $roleSlug)->first();
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['full_name'],
                'password' => Hash::make('password'),
                'role_id' => $role?->id,
                'college_id' => $data['college_id'],
                'phone' => $data['phone'],
            ]
        );

        $prefix = $data['employee_type'] === 'Faculty' ? 'FAC' : 'STF';
        $data['staff_id'] = $prefix . '-' . rand(300, 999);
        $data['user_id'] = $user->id;

        $emp = Employee::create($data);

        SalaryStructure::create([
            'employee_id' => $emp->id,
            'basic_salary' => $emp->basic_salary,
            'hra' => round($emp->basic_salary * 0.20, 2),
            'da' => round($emp->basic_salary * 0.15, 2),
            'ta' => 3200,
            'pf_enabled' => true,
            'pt_enabled' => true,
            'tds_monthly' => $emp->basic_salary > 60000 ? 3500 : 800,
        ]);

        return back()->with('success', "Employee {$emp->full_name} ({$emp->staff_id}) registered.");
    }

    public function updateLeave(Request $request, EmployeeLeave $leave): RedirectResponse
    {
        $status = $request->validate(['status' => 'required|in:Approved,Rejected'])['status'];
        $leave->update(['status' => $status, 'approved_by' => Auth::id()]);

        return back()->with('success', "Leave request {$status}.");
    }

    // Module 13: Payroll
    public function payroll(Request $request): View
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $payrolls = Payroll::with('employee.department')->where('month', $month)->where('year', $year)->get();
        $allPayrolls = Payroll::with('employee')->latest()->take(20)->get();

        return view('hr.payroll', compact('payrolls', 'allPayrolls', 'month', 'year'));
    }

    public function processPayroll(Request $request): RedirectResponse
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);
        $employees = Employee::with('salaryStructure')->where('status', 'Active')->get();

        foreach ($employees as $emp) {
            $ss = $emp->salaryStructure;
            if (! $ss) {
                continue;
            }
            $gross = $ss->basic_salary + $ss->hra + $ss->da + $ss->ta + $ss->special_allowance;
            $pf = $ss->pf_enabled ? round($ss->basic_salary * 0.12, 2) : 0;
            $esi = $ss->esi_enabled ? round($gross * 0.0075, 2) : 0;
            $pt = $ss->pt_enabled ? 200 : 0;
            $tds = $ss->tds_monthly;
            $net = $gross - ($pf + $esi + $pt + $tds);

            Payroll::updateOrCreate(
                ['employee_id' => $emp->id, 'month' => $month, 'year' => $year],
                [
                    'working_days' => 26,
                    'gross_salary' => $gross,
                    'pf_amount' => $pf,
                    'esi_amount' => $esi,
                    'professional_tax' => $pt,
                    'tds_amount' => $tds,
                    'net_salary' => $net,
                    'status' => 'Paid',
                    'paid_at' => now()->toDateString(),
                    'bank_ref' => 'NEFT-' . $year . $month . '-' . $emp->id,
                ]
            );
        }

        return back()->with('success', "Monthly payroll processed for {$month}/{$year} ({$employees->count()} employees).");
    }

    public function payslip(Payroll $payroll): View
    {
        $payroll->load('employee.department.college');

        return view('hr.payslip', compact('payroll'));
    }
}

