@extends('layouts.app')
@section('title', 'Learning Management System')
@section('header', 'Module 20: Learning Management System (LMS, Video Lectures, Assignments & Forum)')

@section('content')
<div class="grid lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Upload Study Material / Video Lecture</h3>
        <form method="POST" action="{{ route('lms.materials.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="subject_id" class="w-full rounded border border-slate-300 px-3 py-2">@foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach</select>
            <select name="material_type" class="w-full rounded border border-slate-300 px-3 py-2"><option>PDF</option><option>Document</option><option>Video Lecture</option><option>Recorded Class</option><option>Quiz</option></select>
            <input type="text" name="title" placeholder="Material Title *" required class="w-full rounded border border-slate-300 px-3 py-2">
            <input type="text" name="file_url" placeholder="Resource / Video URL" class="w-full rounded border border-slate-300 px-3 py-2">
            <button class="w-full rounded-lg bg-indigo-600 text-white font-semibold py-2">Publish Material</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Create Online Assignment</h3>
        <form method="POST" action="{{ route('lms.assignments.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="subject_id" class="w-full rounded border border-slate-300 px-3 py-2">@foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach</select>
            <input type="text" name="title" placeholder="Assignment Title *" required class="w-full rounded border border-slate-300 px-3 py-2">
            <div class="grid grid-cols-2 gap-2">
                <input type="date" name="due_date" required class="rounded border border-slate-300 px-3 py-2">
                <input type="number" name="max_marks" value="20" placeholder="Max Marks" class="rounded border border-slate-300 px-3 py-2">
            </div>
            <textarea name="description" rows="2" placeholder="Instructions..." class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
            <button class="w-full rounded-lg bg-emerald-600 text-white font-semibold py-2">Assign to Class</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Post to Discussion Forum</h3>
        <form method="POST" action="{{ route('lms.discussions.store') }}" class="space-y-2.5 text-xs">
            @csrf
            <select name="subject_id" class="w-full rounded border border-slate-300 px-3 py-2">@foreach($subjects as $sub)<option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>@endforeach</select>
            <input type="text" name="topic" placeholder="Discussion Topic *" required class="w-full rounded border border-slate-300 px-3 py-2">
            <textarea name="message" rows="3" placeholder="Write your question or note..." required class="w-full rounded border border-slate-300 px-3 py-2"></textarea>
            <button class="w-full rounded-lg bg-slate-800 text-white font-semibold py-2">Post Discussion</button>
        </form>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
        <h3 class="text-sm font-semibold text-slate-900">Study Materials &amp; Recorded Classes</h3>
        @foreach($materials as $mat)
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs">
                <div class="flex justify-between"><strong>{{ $mat->title }}</strong><span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">{{ $mat->material_type }}</span></div>
                <div class="text-slate-500 mt-1">{{ $mat->subject->code ?? '' }} &bull; {{ $mat->description }}</div>
            </div>
        @endforeach

        <h3 class="text-sm font-semibold text-slate-900 pt-3">Discussion Forum</h3>
        @foreach($discussions as $disc)
            <div class="p-3 rounded-lg border border-slate-200 text-xs">
                <div class="font-bold">{{ $disc->topic }} <span class="font-normal text-slate-400">({{ $disc->subject->code ?? '' }})</span></div>
                <div class="text-slate-600 mt-1">{{ $disc->message }}</div>
                <div class="text-[11px] text-slate-400 mt-1">Posted by {{ $disc->user->name ?? '' }}</div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
        <h3 class="text-sm font-semibold text-slate-900">Assignments &amp; Student Submissions</h3>
        @foreach($assignments as $as)
            <div class="p-3 rounded-lg border border-slate-200 text-xs space-y-2">
                <div class="flex justify-between"><strong>{{ $as->title }}</strong><span class="text-rose-600">Due: {{ $as->due_date }}</span></div>
                <div class="text-slate-500">{{ $as->subject->code ?? '' }} &bull; Max Marks: {{ $as->max_marks }}</div>
                @foreach($as->submissions as $sub)
                    <div class="p-2 rounded bg-slate-50 border border-slate-100 flex items-center justify-between gap-2">
                        <div>
                            <span class="font-semibold">{{ $sub->student->full_name ?? '' }}</span>
                            <div class="text-[11px] text-indigo-600">{{ $sub->submission_url }}</div>
                        </div>
                        <form method="POST" action="{{ route('lms.submissions.grade', $sub) }}" class="flex gap-1 items-center">
                            @csrf
                            <input type="number" step="0.5" name="marks_obtained" value="{{ $sub->marks_obtained }}" placeholder="Marks" class="w-16 rounded border border-slate-300 px-1.5 py-1 text-[11px]">
                            <button class="px-2 py-1 rounded bg-emerald-600 text-white text-[11px]">Grade</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endsection

