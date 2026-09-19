@extends('layouts.sidebar')

@section('title', 'My Approvals')
@section('page-title', 'My Approvals')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-check-double text-emerald-600 mr-2"></i>My Approvals
                </h2>
                <p class="text-gray-500 mt-1">Activities you have approved, noted, reviewed, or endorsed as <strong>{{ $roleLabel }}</strong></p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('my-approvals.pdf', array_filter(['status' => $status, 'date_from' => $dateFrom, 'date_to' => $dateTo, 'department' => $department])) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('my-approvals.docx', array_filter(['status' => $status, 'date_from' => $dateFrom, 'date_to' => $dateTo, 'department' => $department])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    <i class="fas fa-file-word"></i> Export DOCX
                </a>
            </div>
        </div>
    </div>

    {{-- Filter Buttons --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-3">Filter by Status</p>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('my-approvals.index', array_filter(['status' => 'all', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'department' => $department])) }}"
               class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl text-base font-semibold transition-all duration-200 border-2
                   {{ $status === 'all' ? 'bg-emerald-600 text-white border-emerald-600 shadow-lg shadow-emerald-200' : 'bg-white text-gray-600 border-gray-200 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700' }}">
                <i class="fas fa-list"></i> All Activities
            </a>
            <a href="{{ route('my-approvals.index', array_filter(['status' => 'approved', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'department' => $department])) }}"
               class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl text-base font-semibold transition-all duration-200 border-2
                   {{ $status === 'approved' ? 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-200' : 'bg-white text-gray-600 border-gray-200 hover:bg-green-50 hover:border-green-300 hover:text-green-700' }}">
                <i class="fas fa-check-circle"></i> Approved
            </a>
            <a href="{{ route('my-approvals.index', array_filter(['status' => 'pending', 'date_from' => $dateFrom, 'date_to' => $dateTo, 'department' => $department])) }}"
               class="inline-flex items-center gap-2.5 px-6 py-3 rounded-xl text-base font-semibold transition-all duration-200 border-2
                   {{ $status === 'pending' ? 'bg-yellow-500 text-white border-yellow-500 shadow-lg shadow-yellow-200' : 'bg-white text-gray-600 border-gray-200 hover:bg-yellow-50 hover:border-yellow-300 hover:text-yellow-700' }}">
                <i class="fas fa-hourglass-half"></i> Pending
            </a>
        </div>
    </div>

    {{-- Date & Department Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-3">
            <i class="fas fa-filter text-emerald-500 mr-1"></i> Filter by Date
            @if(in_array(auth()->user()->role, ['psg_adviser', 'director', 'vp']))
                & Department
            @endif
        </p>
        <form id="dateFilterForm" method="GET" action="{{ route('my-approvals.index') }}">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                {{-- Date From --}}
                <div class="w-full md:flex-1">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>

                {{-- Date To --}}
                <div class="w-full md:flex-1">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>

                {{-- Department Dropdown (only for PSG, Director, VP) --}}
                @if(in_array(auth()->user()->role, ['psg_adviser', 'director', 'vp']))
                <div class="w-full md:flex-1">
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select id="department" name="department"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit"
                            class="flex-1 md:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-search"></i> Apply
                    </button>
                    <a href="{{ route('my-approvals.index', ['status' => $status]) }}"
                       class="flex-1 md:flex-none inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Active Filters Display --}}
    @if($dateFrom || $dateTo || $department)
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm font-semibold text-emerald-700"><i class="fas fa-filter mr-1"></i> Active Filters:</span>
            @if($dateFrom)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-emerald-300 rounded-full text-xs font-medium text-emerald-700">
                    <i class="fas fa-calendar"></i> From: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }}
                </span>
            @endif
            @if($dateTo)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-emerald-300 rounded-full text-xs font-medium text-emerald-700">
                    <i class="fas fa-calendar"></i> To: {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                </span>
            @endif
            @if($department)
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-emerald-300 rounded-full text-xs font-medium text-emerald-700">
                    <i class="fas fa-building"></i> {{ $department }}
                </span>
            @endif
            <a href="{{ route('my-approvals.index', ['status' => $status]) }}" class="text-xs text-red-500 hover:text-red-700 font-medium ml-2">
                <i class="fas fa-times-circle"></i> Clear All
            </a>
        </div>
    </div>
    @endif

    {{-- Activities Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($activities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-emerald-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">Activity</th>
                            <th class="px-4 py-3 text-left font-medium">Date Submitted</th>
                            <th class="px-4 py-3 text-left font-medium">Submitted By</th>
                            <th class="px-4 py-3 text-left font-medium">Department</th>
                            <th class="px-4 py-3 text-left font-medium">Schedule</th>
                            <th class="px-4 py-3 text-left font-medium">Location</th>
                            <th class="px-4 py-3 text-left font-medium">Current Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-800">{{ $activity->title }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $activity->type ?? 'General')) }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-700">
                                    {{ $activity->created_at ? $activity->created_at->format('M d, Y') : 'N/A' }}
                                    <div class="text-xs text-gray-500">{{ $activity->created_at ? $activity->created_at->format('g:i A') : '' }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $activity->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $activity->organization ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-700">
                                    @if($activity->activity_date)
                                        {{ $activity->activity_date->format('M d, Y') }}
                                        @if($activity->start_time)
                                            <div class="text-xs text-gray-500">
                                                {{ $activity->start_time->format('g:i A') }}
                                                @if($activity->end_time)
                                                    - {{ $activity->end_time->format('g:i A') }}
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ Str::limit($activity->location, 30) ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $wfStatus = $activity->workflow_status;
                                        $isApproved = $wfStatus === 'approved_by_vp';
                                        $isRejected = $wfStatus === 'rejected';
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $isApproved ? 'bg-green-100 text-green-700' : ($isRejected ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        @if($isApproved)
                                            <i class="fas fa-check-circle"></i> Approved
                                        @elseif($isRejected)
                                            <i class="fas fa-times-circle"></i> Rejected
                                        @else
                                            <i class="fas fa-hourglass-half"></i> {{ ucwords(str_replace('_', ' ', $wfStatus)) }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Approvals Found</h3>
                <p class="text-gray-500">
                    @if($dateFrom || $dateTo || $department)
                        No activities match your current filters. Try adjusting your filters.
                    @else
                        You haven't processed any activities yet. Activities you approve will appear here.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
