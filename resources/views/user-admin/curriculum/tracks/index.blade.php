@extends('layouts.admin', ['title' => 'Curriculum'])
@section('modal')
    <x-modal modal_id="edit-track-modal" modal_name="Edit Track" close_btn_id="edit-track-modal-close-btn"
        modal_container_id="modal-container-1">

        <form id="edit-track-form" class="p-6">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="space-y-6">

                <!-- Track Name -->
                <div>
                    <label for="edit_track_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-school mr-2"></i>
                        Track Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="edit_track_name" required
                        placeholder="e.g., Academic, Technical-Vocational, Sports, Arts and Design"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Track Code -->
                <div>
                    <label for="edit_track_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Track Code
                    </label>
                    <input type="text" name="code" id="edit_track_code" placeholder="e.g., ACAD, TECH, SPORTS"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Track Description -->
                <div>
                    <label for="edit_track_description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-document mr-2"></i>
                        Description
                    </label>
                    <textarea name="description" id="edit_track_description" rows="3" placeholder="Enter track description..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Track Status -->
                <div>
                    <label for="edit_track_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-check-circle mr-2"></i>
                        Status
                    </label>
                    <select name="status" id="edit_track_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="edit-track-form"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Update
            </button>
        </x-slot>

    </x-modal>
    {{-- Create strand/program --}}
    <x-modal modal_id="create-program-modal" modal_name="Create Program" close_btn_id="create-program-modal-close-btn"
        modal_container_id="modal-container-2">

        <form id="create-program-form" class="p-6">
            @csrf
            <div class="space-y-6">
                <!-- Program Code -->
                <div>
                    <label for="program_code" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-tags mr-2"></i>
                        Program Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" id="program_code" required placeholder="e.g., STEM, ABM, HUMSS"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Program Name/Description -->
                <div>
                    <label for="program_name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-graduation-cap mr-2"></i>
                        Program Description <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="program_name" required
                        placeholder="e.g., Science, Technology, Engineering and Mathematics"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Program Track -->
                <div>
                    <label for="program_track" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-school mr-2"></i>
                        Program Track
                    </label>
                    <input type="text" name="track" id="program_track"
                        placeholder="e.g., Academic, Technical-Vocational, Sports, Arts and Design"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Program Status -->
                <div>
                    <label for="program_status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fi fi-rr-check-circle mr-2"></i>
                        Status
                    </label>
                    <select name="status" id="program_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-program-form"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Create
            </button>
        </x-slot>

    </x-modal>
@endsection
@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Academic Programs</h1>
            <p class="text-[14px]  text-gray-900/60">View and manage program list and associated sections and subjects.
            </p>
        </div>
    </div>
@endsection
@section('stat')
    <div class="flex justify-center items-center">
        <div
            class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">

            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg ">

                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black" id="section_name">Curriculum Overview</h1>
                    <p class="text-[16px] text-white/60">{{ $academicTermData['year'] ?? 'No active term yet' }} • {{ $academicTermData['semester'] ?? 'No active semester yet' }}</p>
                </div>

                <div class="flex flex-col items-end justify-center">
                    <p class="text-[50px] font-bold">{{ $tracks->count() }}</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Total Tracks</p>
                    </div>
                </div>


            </div>
            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-150">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Programs</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $totalPrograms }}</p>
                    <p class="text-[12px] truncate text-gray-300">Programs across all tracks</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-school flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Sections</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $totalSections }}</p>
                    <p class="text-[12px] truncate text-gray-300">Sections across all programs</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-book flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Subjects</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $totalSubjects }}</p>
                    <p class="text-[12px] truncate text-gray-300">Subjects across all programs</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Students</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $totalStudents }}</p>
                    <p class="text-[12px] truncate text-gray-300">Students across all tracks</p>
                </div>
            </div>

            <div class="flex flex-row justify-center items-center w-full gap-4 mt-4">
                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-chalkboard-teacher flex justify-center items-center"></i>
                        <p class="text-[14px]">Total Teachers</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $totalTeachers }}</p>
                    <p class="text-[12px] truncate text-gray-300">Teachers across all programs</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-check-circle flex justify-center items-center"></i>
                        <p class="text-[14px]">Active Tracks</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $activeTracks }}</p>
                    <p class="text-[12px] truncate text-gray-300">Currently active tracks</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-pause flex justify-center items-center"></i>
                        <p class="text-[14px]">Inactive Tracks</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $inactiveTracks }}</p>
                    <p class="text-[12px] truncate text-gray-300">Currently inactive tracks</p>
                </div>

                <div
                    class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg hover:-translate-y-1 hover:bg-[#E3ECFF]/40 transition duration-300">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-calendar flex justify-center items-center"></i>
                        <p class="text-[14px]">Academic Year</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $academicTermData['year'] ?? 'No Active Term' }}</p>
                    <p class="text-[12px] truncate text-gray-300">Current academic year</p>
                </div>
            </div>



        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-col justify-center items-start gap-4">
        <div
            class="flex flex-col justify-start items-start flex-grow p-6 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-full">
            <div class="flex flex-row justify-between items-center w-full">
                <div>
                    <span class="font-semibold text-[18px]">
                        Tracks
                    </span>
                    <p class="text-[14px] text-gray-500">Manage all tracks</p>
                </div>
            </div>
            <div class="flex flex-row justify-start items-start flex-wrap gap-4">
                @foreach ($tracks as $track)
                    <div
                        class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden w-[49.3%] hover:-translate-y-2">
                        <!-- Track Header -->
                        <div
                            class="p-4 border-b border-gray-100 {{ $track->status === 'active' ? ' bg-[#199BCF]' : 'bg-gray-400' }}">
                            <div class="flex flex-row items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 {{ $track->status === 'active' ? ' bg-[#C8A165] ]' : 'bg-gray-300' }} rounded-full flex items-center justify-center">
                                            <i
                                                class="fi fi-rr-graduation-cap flex justify-center items-center {{ $track->status === 'active' ? 'text-white' : 'text-gray-400' }} text-lg"></i>
                                        </div>
                                        <div class="flex flex-col justify-center items-start">
                                            <h3
                                                class="text-lg font-bold {{ $track->status === 'active' ? 'text-white' : 'text-gray-300' }}">
                                                {{ $track->name }}</h3>
                                            <p
                                                class="text-[14px] {{ $track->status === 'active' ? 'text-gray-300' : 'text-gray-300' }}">
                                                {{ ucfirst($track->status) }}</p>
                                        </div>
                                    </div>
                                    @if ($track->description)
                                        <p class="text-sm text-slate-600 leading-relaxed">
                                            {{ Str::limit($track->description, 100) }}</p>
                                    @endif
                                </div>
                                <div class="h-full flex-1 flex flex-row justify-end items-center gap-2">
                                    @can('edit track')
                                        <button id="edit-track-btn-{{ $track->id }}" data-id="{{ $track->id }}"
                                            class="edit-track-btns bg-white/20 p-2 rounded-lg text-white hover:bg-white/30 transition duration-200">
                                            <i
                                                class="fi fi-sr-pencil flex opacity-80 justify-center items-center text-[17px]"></i>
                                        </button>
                                    @endcan
                                    @can('create strand')
                                        <button id="create-program-btn-{{ $track->id }}" data-id="{{ $track->id }}"
                                            class="create-program-btns bg-white/20 p-2 rounded-lg text-white hover:bg-white/30 transition duration-200">
                                            <i
                                                class="fi fi-sr-square-plus opacity-80 flex justify-center items-center text-[20px]"></i>
                                        </button>
                                    @endcan


                                </div>
                            </div>
                        </div>

                        <!-- Programs Section -->
                        <div class="py-4 px-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">Strands</h4>
                            </div>

                            <div class="space-y-3">
                                @php
                                    $trackPrograms = $track
                                        ->programs()
                                        ->withCount(['sections', 'subjects'])
                                        ->get()
                                        ->map(function ($program) {
                                            $totalStudents = \App\Models\Student::where(
                                                'program_id',
                                                $program->id,
                                            )->count();
                                            return [
                                                'id' => $program->id,
                                                'name' => $program->name,
                                                'code' => $program->code,
                                                'status' => $program->status,
                                                'sections_count' => $program->getTotalSections(),
                                                'teachers_count' => $program->totalTeachers(),
                                                'subjects_count' => $program->subjects_count,
                                                'students_count' => $totalStudents,
                                            ];
                                        });
                                @endphp

                                @if ($trackPrograms->count() > 0)
                                    @foreach ($trackPrograms as $program)
                                        <div
                                            class="bg-white border border-gray-200 rounded-lg p-4 hover:border-[#199BCF]/70 hover:shadow-md transition-all duration-200 cursor-pointer group">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="flex flex-row justify-between items-center gap-2 mb-2">
                                                        @if ($program['code'])
                                                            <span
                                                                class="px-2 py-1 bg-[#199BCF] text-white rounded text-xs font-semibold">{{ $program['code'] }}</span>
                                                        @endif
                                                        <span
                                                            class="px-2 py-1 text-xs rounded-full font-medium {{ $program['status'] === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                            {{ $program['status'] }}
                                                        </span>
                                                    </div>
                                                    <h5
                                                        class="text-slate-800 font-semibold text-[16px] py-2 leading-tight group-hover:text-[#199BCF] transition-colors duration-200">
                                                        {{ $program['name'] }}</h5>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4 text-xs text-slate-600">
                                                    <div class="flex items-center gap-1" title="Total sections">
                                                        <div
                                                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-[14px] ">
                                                            <i
                                                                class="fi fi-rr-lesson flex justify-center items-center text-blue-600"></i>
                                                        </div>
                                                        <span
                                                            class="font-medium">{{ $program['sections_count'] ?? 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1" title="Total student">
                                                        <div
                                                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-[14px] ">
                                                            <i
                                                                class="fi fi-rr-user flex justify-center items-center text-emerald-600"></i>
                                                        </div>
                                                        <span
                                                            class="font-medium">{{ $program['students_count'] ?? 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1" title="Total teachers">
                                                        <div
                                                            class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-[14px] ">
                                                            <i
                                                                class="fi fi-rr-user flex justify-center items-center text-purple-600"></i>
                                                        </div>
                                                        <span
                                                            class="font-medium">{{ $program['teachers_count'] ?? 0 }}</span>
                                                    </div>
                                                </div>
                                                @can('view strand')
                                                    <a href="/program/{{ $program['id'] }}/sections"
                                                        class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                        <div
                                                            class="w-8 h-8 bg-[#199BCF] hover:bg-[#C8A165] rounded-full flex items-center justify-center transition duration-200">
                                                            <i
                                                                class="fi fi-rr-arrow-right flex justify-center items-center text-white text-[14px]"></i>
                                                        </div>
                                                    </a>
                                                @endcan

                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8 text-slate-500">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fi fi-rr-folder-open text-xl text-slate-400"></i>
                                        </div>
                                        <p class="text-sm font-medium">No strands found</p>
                                        <p class="text-xs text-slate-400 mt-1">This track doesn't have any strands yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Track Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 text-xs text-slate-500">
                                    <div class="flex items-center gap-1">
                                        <i class="fi fi-rr-chart-line"></i>
                                        <span>{{ $trackPrograms->count() }}
                                            program{{ $trackPrograms->count() !== 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>

        {{-- 
        @forelse ($tracks as $track)
            <div
                class="flex flex-col justify-start items-start flex-grow p-5 space-y-4 bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-full">
                <div class="flex flex-row justify-between items-center w-full">
                    <div>
                        <span class="font-semibold text-[18px]">
                            Programs
                        </span>
                        <p class="text-[14px] text-gray-500">Manage all programs</p>
                    </div>
                </div>



            </div>
        @empty
        @endforelse --}}


    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "/js/dropdown.js";
        import {
            clearSearch
        } from "/js/clearSearch.js"
        import {
            initModal
        } from "/js/modal.js";
        import {
            showAlert
        } from "/js/alert.js";

        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        let table1;
        let selectedPageLength = '';


        document.addEventListener("DOMContentLoaded", function() {

            const editBtns = document.querySelectorAll('.edit-track-btns');
            const createBtns = document.querySelectorAll('.create-program-btns');

            let currentTrackId;

            editBtns.forEach(button => {
                let trackId = button.getAttribute('data-id');

                initModal('edit-track-modal', `edit-track-btn-${trackId}`, 'edit-track-modal-close-btn',
                    'cancel-btn',
                    'modal-container-1');

                button.addEventListener('click', () => {
                    currentTrackId = button.getAttribute('data-id');

                    // Fetch track data and populate the form
                    fetch(`/tracks/${trackId}`, {
                            method: 'GET',
                            headers: {
                                "Accept": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data) {
                                const track = data.data;

                                // Populate form fields with real data
                                document.getElementById('edit_track_name').value = track.name ||
                                    '';
                                document.getElementById('edit_track_code').value = track.code ||
                                    '';
                                document.getElementById('edit_track_description').value = track
                                    .description || '';
                                document.getElementById('edit_track_status').value = track
                                    .status || 'active';
                            }
                        })
                        .catch(err => {
                            showAlert('error', 'Failed to load track data');
                        });
                });
            });


            document.getElementById('edit-track-form').addEventListener('submit', (e) => {
                let id = currentTrackId;

                e.preventDefault();

                let form = e.target;

                // Get form data as object instead of FormData
                const formData = {
                    name: document.getElementById('edit_track_name').value,
                    code: document.getElementById('edit_track_code').value,
                    description: document.getElementById('edit_track_description').value,
                    status: document.getElementById('edit_track_status').value,
                    _method: 'PUT'
                };

                // Validate form data
                const nameField = document.getElementById('edit_track_name');

                if (!nameField.value.trim()) {
                    showAlert('error', 'Track name is required');
                    return;
                }

                showLoader('Editing track...');

                fetch(`/tracks/${id}`, {
                        method: 'POST', // Use POST for Laravel method spoofing
                        body: JSON.stringify(formData),
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('edit-track-modal', 'modal-container-1');

                            // Show success alert
                            showAlert('success', data.message);

                            // Reload the page to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);

                        } else {
                            // Handle validation errors
                            if (data.errors) {
                                let errorMessage = 'Validation errors:\n';
                                for (let field in data.errors) {
                                    errorMessage += `• ${field}: ${data.errors[field].join(', ')}\n`;
                                }
                                showAlert('error', errorMessage);
                            } else {
                                showAlert('error', data.message || 'Unknown error occurred');
                            }
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('edit-track-modal', 'modal-container-1');
                        showAlert('error', 'Something went wrong while updating the track');
                    });
            })


            createBtns.forEach(button => {
                let trackId = button.getAttribute('data-id');

                initModal('create-program-modal', `create-program-btn-${trackId}`,
                    'create-program-modal-close-btn',
                    'cancel-btn',
                    'modal-container-2');
                button.addEventListener('click', () => {
                    currentTrackId = button.getAttribute('data-id');
                })

            });


            // Create Program Form Submission
            document.getElementById('create-program-form').addEventListener('submit', function(e) {
                e.preventDefault();

                let form = e.target;

                // Get form data as object
                const formData = {
                    name: document.getElementById('program_name').value,
                    code: document.getElementById('program_code').value
                };

                // Show loader
                showLoader("Creating program...");

                fetch(`/programs/${currentTrackId}`, {
                        method: "POST",
                        body: JSON.stringify(formData),
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        hideLoader();

                        if (data.success) {

                            // Reset form
                            form.reset();

                            // Close modal
                            closeModal('create-program-modal', 'modal-container-2');

                            // Show success alert
                            showAlert('success', data.success);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);

                        } else if (data.error) {
                            closeModal('create-program-modal', 'modal-container-2');
                            showAlert('error', data.error);
                        }
                    })
                    .catch(err => {
                        hideLoader();
                        closeModal('create-program-modal', 'modal-container-1');
                        showAlert('error', 'Something went wrong while creating the program');
                    });
            });


            function closeModal(modalId, modalContainerId) {

                let modal = document.querySelector(`#${modalId}`)
                let body = document.querySelector(`#${modalContainerId}`);

                if (modal && body) {
                    modal.classList.remove('opacity-100', 'scale-100');
                    modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    body.classList.remove('opacity-100');
                    body.classList.add('opacity-0', 'pointer-events-none');
                }

            }

            function openAlert() {
                const alertContainer = document.querySelector('#alert-container');
                alertContainer.classList.toggle('opacity-100');
                alertContainer.classList.toggle('scale-95');
                alertContainer.classList.toggle('pointer-events-none');
                alertContainer.classList.toggle('translate-y-5');
            }


        });
    </script>
@endpush
