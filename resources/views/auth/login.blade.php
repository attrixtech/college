@extends('layouts.guest')
@section('title', 'Sign In')

@section('content')
<div class="max-w-md mx-auto mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6 md:p-8">
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Sign in to thrumble.college</h2>
        <p class="text-xs text-slate-500 mt-1">Authentication is required to access the College Management System.</p>
    </div>

    <form id="login-form" method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-xs font-medium text-slate-700 mb-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="you@thrumble.college" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label for="password" class="block text-xs font-medium text-slate-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
                Keep me signed in
            </label>
        </div>
        <button type="submit" class="w-full rounded-lg bg-indigo-600 text-white py-2.5 text-sm font-semibold hover:bg-indigo-700">Sign In</button>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-200">
        <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2.5">Quick Demo Login</div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach ([
                ['label' => 'Super Admin', 'email' => 'admin@thrumble.college', 'badge' => 'Admin'],
                ['label' => 'Faculty', 'email' => 'faculty@thrumble.college', 'badge' => 'Faculty'],
                ['label' => 'Student', 'email' => 'student@thrumble.college', 'badge' => 'Student'],
                ['label' => 'Parent / Guardian', 'email' => 'parent@thrumble.college', 'badge' => 'Parent'],
            ] as $demo)
                <a href="#"
                   onclick="event.preventDefault(); quickLogin('{{ $demo['email'] }}');"
                   class="group flex flex-col rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-left hover:border-indigo-400 hover:bg-indigo-50 transition">
                    <span class="text-xs font-semibold text-slate-800 group-hover:text-indigo-700">{{ $demo['label'] }}</span>
                    <span class="text-[11px] text-slate-500 truncate">{{ $demo['email'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    function quickLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
        document.getElementById('login-form').submit();
    }
</script>
@endsection
