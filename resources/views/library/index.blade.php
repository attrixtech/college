@extends('layouts.app')
@section('title', 'Library Management')
@section('header', 'Module 9: Library Catalogue, Issue/Return, E-Books & Fines')

@section('content')
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Add Book / Journal / E-Book to Catalogue</h3>
        <form method="POST" action="{{ route('library.books.store') }}" class="grid grid-cols-2 gap-2.5 text-xs">
            @csrf
            <input type="text" name="accession_no" placeholder="Accession No (e.g. ACC-1004) *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="isbn" placeholder="ISBN" class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="title" placeholder="Book Title *" required class="rounded border border-slate-300 px-3 py-2 col-span-2">
            <input type="text" name="author" placeholder="Author *" required class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="publisher" placeholder="Publisher" class="rounded border border-slate-300 px-3 py-2">
            <input type="text" name="subject_category" placeholder="Subject Category *" required class="rounded border border-slate-300 px-3 py-2">
            <select name="book_type" class="rounded border border-slate-300 px-3 py-2">
                <option>Regular</option><option>Reference</option><option>Journal</option><option>Magazine</option><option>E-Book</option>
            </select>
            <input type="text" name="rack_location" placeholder="Rack Location" class="rounded border border-slate-300 px-3 py-2">
            <input type="number" name="total_copies" value="5" min="1" class="rounded border border-slate-300 px-3 py-2">
            <button class="col-span-2 rounded-lg bg-indigo-600 text-white font-semibold py-2">Add to Catalogue</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Issue Book to Student / Faculty</h3>
        <form method="POST" action="{{ route('library.issue') }}" class="space-y-3 text-xs">
            @csrf
            <select name="library_book_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                @foreach($books->where('available_copies', '>', 0) as $b)
                    <option value="{{ $b->id }}">{{ $b->accession_no }} - {{ $b->title }} ({{ $b->available_copies }} avail)</option>
                @endforeach
            </select>
            <select name="user_id" required class="w-full rounded border border-slate-300 px-3 py-2">
                @foreach($members as $m)
                    <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
                @endforeach
            </select>
            <div class="grid grid-cols-2 gap-2">
                <input type="number" name="due_days" value="14" min="1" max="90" class="rounded border border-slate-300 px-3 py-2" placeholder="Loan Days">
                <button class="rounded-lg bg-emerald-600 text-white font-semibold py-2">Issue Book</button>
            </div>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-sm font-semibold">Book Catalogue &amp; Digital Library</h3>
            <form method="GET" class="flex gap-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title/ISBN..." class="rounded border border-slate-300 px-2 py-1 text-xs">
                <button class="px-2.5 py-1 rounded bg-slate-800 text-white text-xs">Go</button>
            </form>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b text-slate-600">
                <tr><th class="p-3">Acc No / Barcode</th><th class="p-3">Title &amp; Author</th><th class="p-3">Type</th><th class="p-3">Stock</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($books as $b)
                    <tr>
                        <td class="p-3 font-semibold">{{ $b->accession_no }}<div class="text-[10px] text-slate-400">{{ $b->barcode }}</div></td>
                        <td class="p-3"><div class="font-medium">{{ $b->title }}</div><div class="text-slate-500">{{ $b->author }}</div></td>
                        <td class="p-3">{{ $b->book_type }}</td>
                        <td class="p-3 font-semibold">{{ $b->available_copies }} / {{ $b->total_copies }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <div class="p-4 border-b border-slate-200">
            <h3 class="text-sm font-semibold">Issued &amp; Overdue Books (₹5/day Fine)</h3>
        </div>
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b text-slate-600">
                <tr><th class="p-3">Book</th><th class="p-3">Member</th><th class="p-3">Due Date</th><th class="p-3">Status / Fine</th><th class="p-3">Action</th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($issues as $iss)
                    <tr>
                        <td class="p-3 font-medium">{{ $iss->book->title ?? '' }}</td>
                        <td class="p-3">{{ $iss->user->name ?? '' }} <span class="text-slate-400">({{ $iss->member_type }})</span></td>
                        <td class="p-3">{{ $iss->due_date }}</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 rounded {{ $iss->status === 'Overdue' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100' }}">{{ $iss->status }}</span>
                            @if($iss->fine_amount > 0)<div class="text-[10px] text-rose-600 font-semibold">Fine: ₹{{ $iss->fine_amount }}</div>@endif
                        </td>
                        <td class="p-3 flex gap-1">
                            @if($iss->status !== 'Returned')
                                <form method="POST" action="{{ route('library.return', $iss) }}">@csrf<button class="px-2 py-1 rounded bg-emerald-600 text-white text-[11px]">Return</button></form>
                                <form method="POST" action="{{ route('library.renew', $iss) }}">@csrf<button class="px-2 py-1 rounded border border-slate-300 text-[11px]">Renew</button></form>
                            @else
                                <span class="text-slate-400">Returned</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

