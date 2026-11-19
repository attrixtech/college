<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'thrumble.college') — thrumble.college</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">
<header class="bg-slate-900 text-white px-6 py-4 flex flex-wrap items-center justify-between gap-4">
    <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">thrumble<span class="text-amber-400">.college</span> <span class="text-xs font-normal text-slate-400 ml-2">Higher-Education ERP</span></a>
    <nav class="flex items-center gap-4 text-sm">
        <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-500 font-medium">ERP Login</a>
    </nav>
</header>

<main class="flex-1 container mx-auto px-4 py-8">
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>

<footer class="bg-white border-t border-slate-200 py-4 px-4 text-center text-xs text-slate-500 space-y-1">
    <div>thrumble.college &bull; Complete 30-Module Institutional Suite (NEP 2020 &amp; NAAC Ready)</div>
    <div>
        Developed by <a href="https://www.attrixtech.com" target="_blank" rel="noopener noreferrer" class="font-medium text-indigo-600 hover:underline">Attrix Technologies</a>
        &bull;
        Marketed by <a href="https://www.infusionn.in" target="_blank" rel="noopener noreferrer" class="font-medium text-indigo-600 hover:underline">Infusionn Pvt. Ltd.</a>
    </div>
</footer>
</body>
</html>

