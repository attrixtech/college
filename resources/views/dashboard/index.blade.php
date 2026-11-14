@extends('layouts.app')
@section('title', 'Dashboard & Analytics')
@section('header', 'Module 1: Institutional Dashboard & Analytics')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="text-xs text-slate-500">Total Students</div>
        <div class="text-2xl font-bold text-slate-900 mt-1">{{ $totalStudents }}</div>
        <div class="text-[11px] text-emerald-600 mt-1">Active Enrolled</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="text-xs text-slate-500">Faculty & Staff</div>
        <div class="text-2xl font-bold text-slate-900 mt-1">{{ $totalFaculty }} <span class="text-sm font-normal text-slate-400">/ {{ $totalStaff }}</span></div>
        <div class="text-[11px] text-slate-500 mt-1">Teaching / Non-Teaching</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="text-xs text-slate-500">Admission Enquiries</div>
        <div class="text-2xl font-bold text-indigo-600 mt-1">{{ $newEnquiries }}</div>
        <div class="text-[11px] text-indigo-600 mt-1">{{ $conversionRate }}% Conversion</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="text-xs text-slate-500">Online Applications</div>
        <div class="text-2xl font-bold text-slate-900 mt-1">{{ $applicationsReceived }}</div>
        <div class="text-[11px] text-amber-600 mt-1">{{ $applicationsPending }} Pending Verification</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="text-xs text-slate-500">Today's Attendance</div>
        <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $todayAttendancePct }}%</div>
        <div class="text-[11px] text-slate-500 mt-1">Campus Average</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <div class="text-xs text-slate-500">Outstanding Fees</div>
        <div class="text-xl font-bold text-rose-600 mt-1">₹{{ number_format($outstandingFees) }}</div>
        <div class="text-[11px] text-rose-500 mt-1">{{ $pendingFeeAccounts }} Pending Accounts</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl border border-slate-200">
        <div class="text-xs text-slate-500">Library Books Issued / Overdue</div>
        <div class="text-xl font-bold text-slate-900 mt-1">{{ $booksIssued }} <span class="text-sm text-rose-600">({{ $booksOverdue }} Overdue)</span></div>
        <div class="text-[11px] text-slate-400 mt-1">Total Stock: {{ $totalBooks }} copies</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200">
        <div class="text-xs text-slate-500">Placement Drives & Selections</div>
        <div class="text-xl font-bold text-indigo-700 mt-1">{{ $placementDrives }} Drives <span class="text-sm text-emerald-600">({{ $studentsSelected }} Selected)</span></div>
        <div class="text-[11px] text-slate-400 mt-1">2026-27 Placement Season</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200">
        <div class="text-xs text-slate-500">Total Fee & Voucher Revenue</div>
        <div class="text-xl font-bold text-emerald-700 mt-1">₹{{ number_format($totalIncome) }}</div>
        <div class="text-[11px] text-slate-400 mt-1">FY 2026-27 Receipts</div>
    </div>
    <div class="bg-white p-4 rounded-xl border border-slate-200">
        <div class="text-xs text-slate-500">Total Institutional Expenses</div>
        <div class="text-xl font-bold text-slate-800 mt-1">₹{{ number_format($totalExpense) }}</div>
        <div class="text-[11px] text-slate-400 mt-1">Vouchers & Payables</div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-5 rounded-xl border border-slate-200">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Course-wise Student Count</h3>
        <canvas id="courseChart" height="200"></canvas>
    </div>
    <div class="bg-white p-5 rounded-xl border border-slate-200">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Revenue vs Expense (₹)</h3>
        <canvas id="financeChart" height="200"></canvas>
    </div>
    <div class="bg-white p-5 rounded-xl border border-slate-200">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Academic Grade Distribution</h3>
        <canvas id="gradeChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-5 rounded-xl border border-slate-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-900">Upcoming Examinations</h3>
            <a href="{{ route('exams.index') }}" class="text-xs text-indigo-600 hover:underline">Manage Exams →</a>
        </div>
        <div class="divide-y divide-slate-100 text-sm">
            @forelse($upcomingExams as $ex)
                <div class="py-2.5 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-slate-800">{{ $ex->name }}</div>
                        <div class="text-xs text-slate-500">{{ $ex->course->code ?? '' }} &bull; Sem {{ $ex->semester }} &bull; {{ $ex->exam_type }}</div>
                    </div>
                    <div class="text-xs font-medium text-indigo-600">{{ $ex->start_date }}</div>
                </div>
            @empty
                <div class="py-4 text-xs text-slate-500">No upcoming exams scheduled.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl border border-slate-200">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-900">Recent Announcements & Alerts</h3>
            <a href="{{ route('community.communication') }}" class="text-xs text-indigo-600 hover:underline">View All →</a>
        </div>
        <div class="divide-y divide-slate-100 text-sm">
            @foreach($recentAnnouncements as $ann)
                <div class="py-2.5">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-slate-800">{{ $ann->title }}</span>
                        <span class="text-[11px] px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $ann->channel }}</span>
                    </div>
                    <div class="text-xs text-slate-500 mt-0.5">{{ Str::limit($ann->message, 100) }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('courseChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($courseDistribution->pluck('code')) !!},
        datasets: [{
            label: 'Students',
            data: {!! json_encode($courseDistribution->pluck('students_count')) !!},
            backgroundColor: '#4f46e5'
        }]
    },
    options: { plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('financeChart'), {
    type: 'doughnut',
    data: {
        labels: ['Total Revenue', 'Total Expense', 'Outstanding Fees'],
        datasets: [{
            data: [{{ $totalIncome }}, {{ $totalExpense }}, {{ $outstandingFees }}],
            backgroundColor: ['#10b981', '#f43f5e', '#f59e0b']
        }]
    }
});

new Chart(document.getElementById('gradeChart'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($gradeDistribution->keys()) !!},
        datasets: [{
            data: {!! json_encode($gradeDistribution->values()) !!},
            backgroundColor: ['#4f46e5', '#10b981', '#0ea5e9', '#f59e0b', '#ef4444']
        }]
    }
});
</script>
@endsection

