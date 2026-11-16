<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebotApp ERP') — WebotApp Indian College ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .print-full { width: 100% !important; margin: 0 !important; padding: 0 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="no-print w-64 bg-slate-900 text-slate-200 flex-shrink-0 flex flex-col">
        <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
            <div>
                <div class="text-lg font-bold tracking-tight text-white">Webot<span class="text-amber-400">App</span> <span class="text-xs font-normal text-indigo-400">ERP</span></div>
                <div class="text-[11px] text-slate-400">Indian Higher-Ed Suite (30 Modules)</div>
            </div>
        </div>

        @php($roleSlug = auth()->user()->role?->slug)
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-5 text-sm">
            @if($roleSlug === 'super_admin')
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Overview</div>
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white font-medium' : 'hover:bg-slate-800' }}">1. Dashboard & Analytics</a>
            </div>

            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Admissions & SIS</div>
                <a href="{{ route('admissions.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('admissions.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">2. Online Admissions</a>
                <a href="{{ route('enquiries.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('enquiries.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">3. Enquiry & CRM</a>
                <a href="{{ route('students.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('students.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">4. Student Info (SIS)</a>
            </div>
            @endif

            @if(in_array($roleSlug, ['super_admin', 'faculty'], true))
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Academics & Exams</div>
                @if($roleSlug === 'super_admin')
                <a href="{{ route('academics.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('academics.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">5. Courses & NEP 2020</a>
                @endif
                <a href="{{ route('timetable.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('timetable.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">6. Class & Timetable</a>
                <a href="{{ route('attendance.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('attendance.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">7. Attendance</a>
                <a href="{{ route('exams.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('exams.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">8. Examinations & CGPA</a>
            </div>
            @endif

            @if(in_array($roleSlug, ['super_admin', 'accountant', 'librarian', 'faculty'], true))
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Finance, HR & Assets</div>
                @if(in_array($roleSlug, ['super_admin', 'librarian', 'faculty'], true))
                <a href="{{ route('library.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('library.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">9. Library</a>
                @endif
                @if(in_array($roleSlug, ['super_admin', 'accountant'], true))
                <a href="{{ route('fees.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('fees.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">10. Student Fees</a>
                <a href="{{ route('finance.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('finance.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">11. College Finance</a>
                @endif
                @if($roleSlug === 'super_admin')
                <a href="{{ route('hr.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('hr.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">12. Faculty & Staff HR</a>
                @endif
                @if(in_array($roleSlug, ['super_admin', 'accountant'], true))
                <a href="{{ route('payroll.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('payroll.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">13. Payroll (PF/ESI/TDS)</a>
                <a href="{{ route('procurement.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('procurement.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">16. Procurement & Stock</a>
                @endif
            </div>
            @endif

            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Campus & Portals</div>
                @if($roleSlug === 'super_admin')
                <a href="{{ route('hostel.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('hostel.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">14. Hostel & Mess</a>
                <a href="{{ route('transport.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('transport.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">15. Transport & GPS</a>
                @endif
                @if(in_array($roleSlug, ['super_admin', 'student'], true))
                <a href="{{ route('portal.student') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('portal.student') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">17. Student Portal</a>
                @endif
                @if(in_array($roleSlug, ['super_admin', 'faculty'], true))
                <a href="{{ route('portal.faculty') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('portal.faculty') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">18. Faculty Portal</a>
                @endif
                @if(in_array($roleSlug, ['super_admin', 'parent'], true))
                <a href="{{ route('portal.parent') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('portal.parent') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">19. Parent Portal</a>
                @endif
                @if(in_array($roleSlug, ['super_admin', 'faculty'], true))
                <a href="{{ route('lms.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('lms.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">20. LMS & E-Learning</a>
                @endif
            </div>

            @if($roleSlug === 'super_admin')
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Career, Compliance & Admin</div>
                <a href="{{ route('placements.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('placements.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">21. Placement & Career</a>
                <a href="{{ route('scholarships.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('scholarships.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">22. Scholarships</a>
                <a href="{{ route('certificates.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('certificates.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">23. Certificates & QR</a>
                <a href="{{ route('grievances.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('grievances.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">24. Grievances & Discipline</a>
                <a href="{{ route('community.events_alumni') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('community.events_alumni') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">25-26. Events & Alumni</a>
                <a href="{{ route('community.accreditation_research') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('community.accreditation_research') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">27-28. NAAC/NIRF & Research</a>
                <a href="{{ route('community.communication') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('community.communication') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">29. Communication</a>
                <a href="{{ route('settings.index') }}" class="block px-3 py-1.5 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800' }}">30. System & RBAC</a>
            </div>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Header -->
        <header class="no-print bg-white border-b border-slate-200 px-6 py-3 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">@yield('header', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3 text-xs">
                @if($roleSlug === 'super_admin')
                <a href="{{ route('admission.public') }}" target="_blank" class="px-3 py-1.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 font-medium">Admission Form ↗</a>
                @endif
                <div class="text-right">
                    <div class="font-semibold text-slate-800">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-slate-500">{{ auth()->user()->role->name ?? 'User' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-md bg-slate-800 text-white hover:bg-slate-700">Logout</button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-6 print-full">
            @if (session('success'))
                <div class="no-print mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="no-print mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>

