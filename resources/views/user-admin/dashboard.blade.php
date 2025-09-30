@extends('layouts.admin')

@section('modal')
    {{-- academic term modal --}}
    <x-modal modal_id="acad-term-modal" modal_name="Add new academic term" close_btn_id="at-close-btn" modal_container_id="modal-container-1">
        <form action="/academic-terms" method="POST" id="academic-term-form" class="pt-2 pb-4 px-4 space-y-2">
            @csrf
            <div class="flex flex-row space-x-2">
                <div class="flex-1 space-y-1">
                    <label for="year" class="text-[14px] font-bold opacity-90">Period</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                        <input type="text" name="year" id="year" placeholder="XXXX-XXXX"
                            class="appearance-none     
                        [&::-webkit-outer-spin-button]:appearance-none
                        [&::-webkit-inner-spin-button]:appearance-none
                        [-moz-appearance:textfield] bg-transparent outline-none font-medium text-[14px] w-full">
                    </div>
                </div>
                <div class="flex-1 space-y-1">
                    <label for="semester" class="text-[14px] font-bold opacity-90">Semester</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-clock-five flex items-center opacity-60"></i>
                        <select name="semester" id="semester" class="w-full bg-transparent text-[14px]">
                            <option></option>
                            <option value="1st Semester" class="text-[14px]">1st Semester</option>
                            <option value="2nd Semester" class="text-[14px]">2nd Semester</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-row space-x-2">
                <div class="flex-1 space-y-1">
                    <label for="start_date" class="text-[14px] font-bold">Start Date</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-check flex items-center opacity-60"></i>
                        <input type="date" name="start_date" id="start_date" placeholder="XXXX-XXXX"
                            class="bg-transparent outline-none font-medium text-[14px] w-full text-[#0f111c]/80">
                    </div>
                </div>
                <div class="flex-1 space-y-1">
                    <label for="end_date" class="text-[14px] font-bold">End Date</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-xmark flex items-center opacity-60"></i>
                        <input type="date" name="end_date" id="end_date" placeholder="XXXX-XXXX"
                            class="bg-transparent outline-none font-medium text-[14px] w-full text-[#0f111c]/80">
                    </div>
                </div>
            </div>
            <div class="flex-1 space-y-1">
                <label for="is_active" class="text-[14px] font-bold">Set as Active?</label>
                <div
                    class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                    <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                    <select name="is_active" id="is_active" class="w-full bg-transparent text-[14px]">
                        <option selected disabled class="text-[14px]">Set status</option>
                        <option value="1" class="text-[14px]">Yes</option>
                        <option value="0" class="text-[14px]">No</option>
                    </select>
                </div>
            </div>
        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
            <button form="academic-term-form"
                class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
        </x-slot>
    </x-modal>
    {{-- enrollment period modal --}}
    <x-modal modal_id="enrollment-period-modal" modal_name="Add Enrollment Period" close_btn_id="ep-close-btn" modal_container_id="modal-container-2">
        <form action="/enrollment-period" method="POST" id="enrollment-period-form" class="pt-2 pb-4 px-4 space-y-2">
            @csrf
            <input type="hidden" name="academic_terms_id" value="{{ $currentAcadTerm->id ?? '' }}">
            <div class="flex flex-row space-x-2">
                <div class="flex-1 space-y-1">
                    <label for="name" class="text-[14px] font-bold opacity-90">Name</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                        <input type="text" name="name" id="name"
                            placeholder="(Early registration, Regular, etc.)"
                            class="appearance-none     
                        [&::-webkit-outer-spin-button]:appearance-none
                        [&::-webkit-inner-spin-button]:appearance-none
                        [-moz-appearance:textfield] bg-transparent outline-none font-medium text-[14px] w-full">
                    </div>
                </div>
                <div class="flex-1 space-y-1">
                    <label for="max_applicants" class="text-[14px] font-bold opacity-90">Max Applicant</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-clock-five flex items-center opacity-60"></i>
                        <select name="max_applicants" id="max_applicants"
                            class="w-full bg-transparent text-[14px] opacity-80">
                            <option disabled selected class="text-[14px] opacity-60">Set maximum number of applicant
                            </option>
                            <option value="20" class="text-[14px]">20 Applicants</option>
                            <option value="40" class="text-[14px]">40 Applicants</option>
                            <option value="60" class="text-[14px]">60 Applicants</option>
                            <option value="80" class="text-[14px]">80 Applicants</option>
                            <option value="100" class="text-[14px]">100 Applicants</option>
                            <option value="150" class="text-[14px]">150 Applicants</option>
                            <option value="200" class="text-[14px]">200 Applicants</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-row space-x-2">
                <div class="flex-1 space-y-1">
                    <label for="start_date" class="text-[14px] font-bold">Application Start Date</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-check flex items-center opacity-60"></i>
                        <input type="date" name="application_start_date" id="start_date" placeholder="XXXX-XXXX"
                            class="bg-transparent outline-none font-medium text-[14px] w-full text-[#0f111c]/80">
                    </div>
                </div>
                <div class="flex-1 space-y-1">
                    <label for="end_date" class="text-[14px] font-bold">Application End Date</label>
                    <div
                        class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-calendar-xmark flex items-center opacity-60"></i>
                        <input type="date" name="application_end_date" id="end_date" placeholder="XXXX-XXXX"
                            class="bg-transparent outline-none font-medium text-[14px] w-full text-[#0f111c]/80">
                    </div>
                </div>
            </div>

        </form>

        <x-slot name="modal_buttons">
            <button id="ep-cancel-btn"
                class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
            <button form="enrollment-period-form"
                class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
        </x-slot>
    </x-modal>
    @if ($activeEnrollmentPeriod)
        <x-modal modal_id="end-enrollment-modal" modal_name="End enrollment period confirmation"
            close_btn_id="end-enrollment-close-btn" modal_container_id="modal-container-3">
            <form action="/enrollment-period/{{ $activeEnrollmentPeriod->id }}" method="POST" id="end-enrollment-form"
                class="pt-2 pb-4 px-4 space-y-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" id="ep-status" value="Closed">
                <p class="text-[16px] font-semibold">Are you sure you want to end the enrollment period?</p>
                <p class="text-[14px] font-medium opacity-80">Please ensure that all applications have been reviewed and
                    there are no pending or unprocessed submissions before proceeding.</p>
                <p class="text-[14px] font-medium opacity-80">This action may prevent further access or updates to ongoing
                    applications.</p>
            </form>

            <x-slot name="modal_buttons">
                <button id="end-enrollment-cancel-btn"
                    class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
                <button form="end-enrollment-form" id="end-enrollment-period-confirmation"
                    data-id="{{ $activeEnrollmentPeriod->id }}"
                    class="bg-[#F97316] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
            </x-slot>
        </x-modal>
    @endif
    {{-- end enrollment period modal --}}
@endsection

@section('dashboard-acad-term')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Dashboard</h1>
        <p class="text-[14px]  text-gray-900/60">Monitor and manage application activity for the current academic term.
        </p>
    </div>
@endsection

@section('header')
    <x-header-container>
        {{-- <div class="flex flex-row items-center space-x-2 px-[14px] py-[10px]">
        <i class="fi fi-rs-chart-simple text-[20px]"></i>
        <p class="text-[16px] md:text-[18px] font-bold">Dashboard</p>
        </div>
        <span class="flex items-center">  
        <span class="h-px flex-1 bg-[#1e1e1e]/15 dark:bg-[#1e1e1e]/15"></span>
    </span>
      
    <div class="flex flex-row space-x-2 px-[14px] py-[14px]">
        <x-total-stat-card
            card_title="Total Application"
            color="#1A73E8"
            class="border-[#1A73E8] bg-[#E7F0FD]"
            text_color="#1A73E8"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-pending flex flex-row items-center text-[16px] text-[#1A73E8]"></i>
            </x-slot>
            {{ 0 }}
        </x-total-stat-card>
        <x-total-stat-card
            card_title="Selected Application"
            color="#34A853"
            class="border-[#34A853] bg-[#E6F4EA]"
            text_color="#34A853"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-check-circle flex flex-row items-center text-[16px] text-[#34A853]"></i>
            </x-slot>
            {{ 0 }}
        </x-total-stat-card>
        <x-total-stat-card
            card_title="Pending Application"
            color="#FBBC04"
            class="border-[#FBBC04] bg-[#FFF4E5]"
            text_color="#FBBC04"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-pending flex flex-row items-center text-[16px] text-[#FBBC04]"></i>
            </x-slot>
            {{ $pending_applications }}
        </x-total-stat-card>
        <x-total-stat-card
            card_title="Rejected Application"
            color="#EA4335"
            class="border-[#EA4335] bg-[#FCE8E6]"
            text_color="#EA4335"
        >
            <x-slot name="card_icon">
                <i class="fi fi-ss-cross-circle flex flex-row items-center text-[16px] text-[#EA4335]"></i>
            </x-slot>
            {{ 0 }}
        </x-total-stat-card>


    </div>            --}}

        <div class="flex flex-row items-center justify-between space-x-2 p-6">
            <div class="flex flex-row items-center space-x-1">
                <button
                    class="flex flex-col justify-center items-start text-blue-500 hover:text-blue-400 ease-in-out duration-150">

                    @if (@isset($currentAcadTerm->year))
                        @if ($currentAcadTerm->year === null)
                            <span class="font-bold text-[18px] ">No active academic year yet.</span>
                        @else
                            <span class="font-bold text-[18px] ">Academic Year {{ $currentAcadTerm->year }}</span>
                            <span class="text-[14px] font-medium opacity-80">{{ $currentAcadTerm->semester }}</span>
                        @endif
                    @else
                        <span class="font-bold text-[18px] ">No academic year found, please create first.</span>
                    @endif



                </button>
            </div>
            <div>
                <button id="acad-term-btn"
                    class="flex flex-row justify-center items-center gap-1 py-2 px-3 bg-blue-100 text-blue-500 rounded-xl font-bold hover:bg-blue-500 hover:text-white hover:ring hover:ring-blue-200 transition duration-200">
                    <i class="fi fi-rs-plus-small flex justify-center items-center text-[20px]"></i>Add
                    new term</button>

            </div>
        </div>
    </x-header-container>
@endsection

@section('stat')

    <div class="flex flex-row space-x-4">
        <div class="bg-[#f8f8f8] flex-1 rounded-xl p-6 shadow-sm border border-[#1e1e1e]/10">
            <div class="flex flex-row items-center justify-between pb-3 border-b border-[#1e1e1e]/10">
                <span class="font-semibold text-[18px] opacity-90">Active Enrollment Period</span>
                @if ($activeEnrollmentPeriod)
                    @if ($activeEnrollmentPeriod->status == 'Ongoing')
                        <span id="status-span" class="text-[12px] md:text-[14px] font-bold text-[#34A853] bg-[#E6F4EA] px-2 py-1 rounded-full">Ongoing</span>
                    @elseif ($activeEnrollmentPeriod->status == 'Paused')
                        <span id="status-span" class="text-[12px] md:text-[14px] font-bold text-[#EA4335] bg-[#FCE8E6] px-2 py-1 rounded-full">Paused</span>
                    @endif
                @endif
            </div>

            @if ($activeEnrollmentPeriod)
                <div id="ep-details"
                    class="{{ $activeEnrollmentPeriod->status == 'Paused' ? 'opacity-30' : 'opacity-100' }}">
                @else
                    <div id="ep-details">
            @endif
            @if ($activeEnrollmentPeriod)
                <div class="flex flex-row py-4 justify-between items-center">
                    <div class="flex flex-col">
                        <span class="font-semibold text-[16px] opacity-90">{{ $activeEnrollmentPeriod->name }}</span>
                        <span class="font-medium text-[14px] opacity-60">{{ $activeEnrollmentPeriod->academicTerms->full_name }}</span>
                    </div>
                    <div>
                        @if ($activeEnrollmentPeriod)
                            <span class="text-[14px] font-bold">
                                {{-- toggle --}}
                                <label for="toggleEnrollmentPeriod" class="relative inline-flex items-center cursor-pointer select-none">
                                    <input type="checkbox" id="toggleEnrollmentPeriod" class="sr-only peer"
                                        @if ($activeEnrollmentPeriod->status == 'Ongoing') checked 
                                    value="Paused"
                                @elseif ($activeEnrollmentPeriod->status == 'Paused')
                                    value="Ongoing" @endif>
                                    <div class="w-12 h-6 bg-[#EA4335]/70 peer-checked:bg-[#34A853]/70 rounded-full transition-colors duration-200">
                                    </div>
                                    <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 peer-checked:translate-x-6"></span>
                                </label>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
            @if ($activeEnrollmentPeriod)
                <div class="flex flex-row gap-2 py-4">
                    <div class="flex flex-row gap-3 items-center bg-white/70 backdrop-blur-sm border border-[#34A853]/20 rounded-xl px-5 py-3">
                        <div class="bg-[#E6F4EA] px-3 py-2 rounded-full">
                            <i class="fi fi-sr-calendar-check text-[22px] text-[#34A853]"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-bold whitespace-nowrap">{{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_start_date)->format('F d') }}</span>
                            <span class="text-[13px] md:text-[14px] opacity-60">Start Date</span>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center justify-center w-1/2">
                        <i class="fi fi-rs-arrow-right text-[24px] opacity-40"></i>
                    </div>

                    <div class="flex flex-row gap-3 items-center bg-white/70 backdrop-blur-sm border border-[#EA4335]/20 rounded-xl px-5 py-3">
                        <div class="bg-[#FCE8E6] px-3 py-2 rounded-full">
                            <i class="fi fi-sr-calendar-xmark text-[22px] text-[#EA4335]"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-bold whitespace-nowrap">{{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->format('F d') }}</span>
                            <span class="text-[13px] md:text-[14px] opacity-60">End Date</span>
                        </div>
                    </div>
                </div>
            @elseif (!$activeEnrollmentPeriod)
                <div class="flex flex-row justify-evenly py-2">

                    <div class="flex flex-col justify-center items-center space-y-3">
                        <img src="{{ asset('images/empty-box.png') }}" alt="empty-box" class="size-[120px]">
                        <span class="opacity-60">There is currently no active enrollment period</span>
                        @if ($currentAcadTerm)
                            <button id="enrollment-period-btn"
                                class="border border-[#1A73E8] px-3 py-1 text-[#1A73E8] font-bold text-[14px] rounded-md">
                                Add Enrollment Period
                            </button>
                        @endif

                    </div>

                </div>
            @endif
        </div>
        @if ($activeEnrollmentPeriod)
            <div class="flex flex-row items-center justify-between pt-3">
                <span id="ep-time"
                      data-end="{{ \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->toIso8601String() }}"
                      data-status="{{ $activeEnrollmentPeriod->status }}"
                      class="{{ $activeEnrollmentPeriod->status == 'Paused' ? 'opacity-30' : 'opacity-100' }} text-[14px] md:text-[15px]">Time Remaining:
                    <span id="ep-time-value" class="opacity-100 font-bold">
                        @php
                            $remainingDays = max(
                                0,
                                \Carbon\Carbon::parse($activeEnrollmentPeriod->application_end_date)->diffInDays(
                                    \Carbon\Carbon::now(),
                                ),
                            );
                            echo $remainingDays . ' ' . Str::plural('Day', $remainingDays);
                        @endphp
                    </span>
                </span>
                <div>
                    <button id="end-enrollment-btn" class="bg-red-100 text-red-500 px-3 py-1 rounded-xl text-[14px] font-semibold hover:bg-red-500 hover:text-white hover:ring hover:ring-red-200 ease-in-out duration-150">End
                        Period
                    </button>
                </div>
            </div>
        @endif
    </div>
    {{-- Application overview --}}
    <div class="bg-[#f8f8f8] flex-1 rounded-xl p-6 space-y-4 shadow-sm border border-[#1e1e1e]/10">
        <div class="flex flex-row items-center justify-between">
            <span class="font-semibold text-[18px] opacity-90">Application Overview</span>
        </div>

        @php
            $maxApplicants = $activeEnrollmentPeriod->max_applicants ?? 0;
            $currentCount = $applicationCount ?? 0;
            $percent = $maxApplicants > 0 ? min(100, round(($currentCount / $maxApplicants) * 100)) : 0;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- KPI card --}}
            <div class="flex flex-col justify-between bg-[#E3ECFF]/40 rounded-xl p-4">
                <div class="flex flex-col items-center justify-center py-4">
                    <span id="total-application" class="text-[36px] md:text-[40px] font-extrabold">{{ $currentCount }}<span
                            class="text-[18px] md:text-[20px] opacity-60">/{{ $maxApplicants ?: '-' }}</span></span>
                    <span class="font-medium opacity-60">Total Applications</span>
                </div>
                <div class="space-y-2">
                    <div class="w-full">
                        <div class="bg-[#d9d9d9] h-2 rounded-full w-full overflow-hidden">
                            <div class="bg-blue-500 h-2" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    <div class="text-[13px] md:text-[14px] text-center">{{ $percent }}% of max applications</div>
                </div>
            </div>

            {{-- Stat tiles --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-3">
                <div class="flex flex-col bg-white/70 backdrop-blur-sm gap-2 px-4 py-4 rounded-xl border border-[#FBBC04]/20">
                    <div class="flex flex-row items-center gap-3">
                        <div class="bg-[#FFF4E5] border border-[#FBBC04]/60 text-[#FBBC04] rounded-full text-[18px] font-bold size-10 flex items-center justify-center px-3 py-1">
                            {{ $pending_applications ?? '0' }}</div>
                        <p class="font-semibold text-[15px] md:text-[16px]">Pending</p>
                    </div>
                    <span class="self-start text-[13px] md:text-[14px] opacity-60"><a href="">View All</a></span>
                </div>

                <div class="flex flex-col bg-white/70 backdrop-blur-sm gap-2 px-4 py-4 rounded-xl border border-[#34A853]/20">
                    <div class="flex flex-row items-center gap-3">
                        <div class="bg-[#E6F4EA] border border-[#34A853]/60 text-[#34A853] rounded-full text-[18px] font-bold size-10 flex items-center justify-center px-3 py-1">
                            {{ $selected_applications ?? '0' }}</div>
                        <p class="font-semibold text-[15px] md:text-[16px]">Selected</p>
                    </div>
                    <span class="self-start text-[13px] md:text-[14px] opacity-60"><a href="">View All</a></span>
                </div>

                <div class="flex flex-col bg-white/70 backdrop-blur-sm gap-2 px-4 py-4 rounded-xl border border-[#9C27B0]/20">
                    <div class="flex flex-row items-center gap-3">
                        <div class="bg-[#F3E5F5] border border-[#9C27B0]/60 text-[#9C27B0] rounded-full text-[18px] font-bold size-10 flex items-center justify-center px-3 py-1">
                            0</div>
                        <p class="font-semibold text-[15px] md:text-[16px]">Pending Docs</p>
                    </div>
                    <span class="self-start text-[13px] md:text-[14px] opacity-60"><a href="">View All</a></span>
                </div>

                <div class="flex flex-col bg-white/70 backdrop-blur-sm gap-2 px-4 py-4 rounded-xl border border-[#1A73E8]/20">
                    <div class="flex flex-row items-center gap-3">
                        <div class="bg-[#E7F0FD] border border-[#1A73E8]/60 text-[#1A73E8] rounded-full text-[18px] font-bold size-10 flex items-center justify-center px-3 py-1">
                            0</div>
                        <p class="font-semibold text-[15px] md:text-[16px]">Enrolled</p>
                    </div>
                    <span class="self-start text-[13px] md:text-[14px] opacity-60"><a href="">View All</a></span>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('content')

    @error('year')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('semester')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('start_date')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('end_date')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('is_active')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @error('error')
        <div class="text-red-500 text-[14px] font-bold">{{ $message }}</div>
    @enderror
    @if (session('error'))
        <div class="text-red-500 text-[14px] font-bold">{{ session('error') }}</div>
    @endif


    <div class="flex flex-row justify-center items-start gap-4">

        <div class="flex flex-col w-[70%] h-auto bg-[#f8f8f8] rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">

            <span class="text-[16px] md:text-[18px] font-semibold opacity-90">Recent Applications</span>


            <div class="flex flex-col items-center flex-grow space-y-2">

                <div class="w-full">
                    <table id="myTable" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th
                                    class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Applicant Id</span>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Full Name</span>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Program</span>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Grade Level</span>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Created at</span>
                                </th>
                                <th
                                    class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-70">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated by DataTables AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="w-[30%] h-full space-y-4">
            <div class="flex-1 flex flex-col bg-[#f8f8f8] rounded-xl border shadow-sm border-[#1e1e1e]/10 p-6 gap-4">
                <span class="font-semibold text-[18px]">Quick Actions</span>
                <div class="flex flex-col justify-center items-center  gap-2 text-center">
                    <x-nav-link href="/pending-documents"
                        class="w-full bg-blue-500 py-2 px-4 rounded-xl font-medium text-white hover:ring hover:ring-blue-200 hover:bg-blue-400 hover:shadow-md transition duration-150">
                        Create Document
                    </x-nav-link>
                    <x-nav-link
                        class="w-full bg-blue-500 py-2 px-4 rounded-xl font-medium text-white hover:ring hover:ring-blue-200 hover:bg-blue-400 hover:shadow-md transition duration-150">
                        Create Document
                    </x-nav-link>
                    <x-nav-link
                        class="w-full bg-blue-500 py-2 px-4 rounded-xl font-medium text-white hover:ring hover:ring-blue-200 hover:bg-blue-400 hover:shadow-md transition duration-150">
                        Create Document
                    </x-nav-link>
                </div>
            </div>
            <div
                class="flex-1 flex flex-col bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-md p-6 gap-4 text-white">
                <span class="font-semibold text-[18px]">Today's Summary</span>
                <div class="flex flex-col gap-2">
                    <div class="flex flex-row justify-between items-center">
                        <span class="opacity-70">New Applications</span>
                        <span class="font-bold">20</span>
                    </div>
                    <div class="flex flex-row justify-between items-center">
                        <span class="opacity-70">Interviews Completed</span>
                        <span class="font-bold">20</span>
                    </div>
                    <div class="flex flex-row justify-between items-center">
                        <span class="opacity-70">Documents Verified</span>
                        <span class="font-bold">20</span>
                    </div>
                    <div class="flex flex-row justify-between items-center">
                        <span class="opacity-70">Enrollments Finalized</span>
                        <span class="font-bold">20</span>
                    </div>




                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="module">
        import {
            initModal
        } from "/js/modal.js";
        import {
            initCustomDataTable
        } from "/js/initTable.js";

        let table;
        let totalApplications = document.querySelector('#total-application');
        let endEnrollmentBtn = document.querySelector('#end-enrollment-period-confirmation');
        let toggle = document.querySelector('#toggleEnrollmentPeriod');

        document.addEventListener("DOMContentLoaded", function() {

            // Initialize table using the AJAX component
            table = initCustomDataTable(
                'myTable',
                '/admin/recent-applications',
                [
                    {
                        data: 'applicant_id',
                        width: '16%',
                        orderable: true
                    },
                    {
                        data: 'full_name',
                        width: '20%',
                        orderable: true
                    },
                    {
                        data: 'program',
                        width: '15%',
                        orderable: true
                    },
                    {
                        data: 'grade_level',
                        width: '15%',
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        width: '15%',
                        orderable: true
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '10%',
                        render: function(data, type, row) {
                            return `<a href="/pending-application/form-details/${data}" class="flex justify-center items-center text-blue-600 bg-blue-200 rounded hover:bg-blue-300 transition duration-150 p-1"><button type="button" class=""><i class="fi fi-rr-eye text-[16px] flex justify-center items-center"></i></button></a>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [[4, 'desc']], // Order by created_at descending
                null, // No custom search input
                [] // columnDefs parameter
            );

            // Override some settings for dashboard table
            table.page.len(10).draw();
            table.search('').draw(); // Disable searching for dashboard

            initModal('acad-term-modal', 'acad-term-btn', 'at-close-btn', 'cancel-btn', 'modal-container-1');
            initModal('enrollment-period-modal', 'enrollment-period-btn', 'ep-close-btn', 'ep-cancel-btn', 'modal-container-2');
            initModal('end-enrollment-modal', 'end-enrollment-btn', 'end-enrollment-close-btn',
                'end-enrollment-cancel-btn', 'modal-container-3');

            console.log(window.Echo);

            // Restrict fetching-recent-applications channel to super_admin and admin only
            const userRoles = window.Laravel?.user?.roles?.map(role => role.name || role) || [];
            
            if (userRoles.some(role => ['super_admin', 'admin'].includes(role))) {
                console.log('Setting up recent applications listener for admin users');
                
                window.Echo.channel('fetching-recent-applications').listen('RecentApplicationTableUpdated', (event) => {
                    console.log('New application received:', event);
                    console.log('Total applications:', event.total_applications);
                    console.log('Application data:', event.application);
                    
                    // Update total applications counter
                    if (totalApplications) {
                        totalApplications.innerHTML = event.total_applications;
                    }

                    // Reload the table to show new data
                    table.ajax.reload(function() {
                        console.log('Table reloaded with new application data');
                        
                        // Highlight the first row (newest application) after reload
                        setTimeout(() => {
                            let firstRow = document.querySelector('#myTable tbody tr:first-child');
                            if (firstRow) {
                                firstRow.classList.add(
                                    'duration-300',
                                    'ease-in-out',
                                    'bg-[#FBBC04]/30'
                                );

                                // Remove highlight after 4000ms
                                setTimeout(() => {
                                    firstRow.classList.remove('bg-[#FBBC04]/30');
                                }, 4000);
                            }
                        }, 500); // Small delay to ensure DOM is updated
                    }, false); // false = don't reset paging
                });
            } else {
                console.log('User does not have super_admin or admin role, skipping recent applications channel subscription');
            }

            // if (endEnrollmentBtn) {
            //     endEnrollmentBtn.addEventListener('click', async () => {
            //         const id = endEnrollmentBtn.dataset.id;
            //         console.log(id);
            //         try {
            //             const response = await window.axios.patch(`/enrollment-period/${id}`, {
            //                 status: 'Closed'
            //             }, {
            //                 headers: {
            //                     'Content-Type': 'application/json'
            //                 }
            //             });
            //             if (response.status == 200) {
            //                     window.location.reload();
            //             }
            //         } catch (error) {
            //             console.error(error);
            //         }
            //     });
            // }

            if (toggle) {
                toggle.addEventListener('change', async () => {
                    const id = endEnrollmentBtn.dataset.id;
                    const status = toggle.checked ? 'Ongoing' : 'Paused';
                    console.log(id);
                    try {
                        const response = await window.axios.patch(`/enrollment-period/${id}`, {
                            status: status
                        }, {
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        });
                    } catch (error) {
                        console.error(error);
                    }
                });
            }

            window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (
                event) => {
                console.log(event.enrollmentPeriod.status);
                let epDetails = document.querySelector('#ep-details');
                let epTime = document.querySelector('#ep-time');
                let statusSpan = document.querySelector('#status-span');

                if (event.enrollmentPeriod.status == 'Paused') {
                    epDetails.classList.add('opacity-30');
                    epTime.classList.add('opacity-30');
                    statusSpan.innerHTML = event.enrollmentPeriod.status;
                    statusSpan.classList.remove('text-[#34A853]');
                    statusSpan.classList.add('text-[#EA4335]');
                } else if (event.enrollmentPeriod.status == 'Ongoing') {
                    epDetails.classList.remove('opacity-30');
                    epTime.classList.remove('opacity-30');
                    statusSpan.innerHTML = event.enrollmentPeriod.status;
                    statusSpan.classList.remove('text-[#EA4335]');
                    statusSpan.classList.add('text-[#34A853]');
                }


            });


            // Live countdown for Time Remaining
            const epTimeSpan = document.querySelector('#ep-time');
            const epTimeValue = document.querySelector('#ep-time-value');
            let countdownInterval;

            function formatRemaining(ms) {
                if (ms <= 0) return '0 Days';
                const totalSeconds = Math.floor(ms / 1000);
                const days = Math.floor(totalSeconds / (3600 * 24));
                const hours = Math.floor((totalSeconds % (3600 * 24)) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                const dd = days + ' ' + (days === 1 ? 'Day' : 'Days');
                const hh = String(hours).padStart(2, '0');
                const mm = String(minutes).padStart(2, '0');
                const ss = String(seconds).padStart(2, '0');
                return `${dd} ${hh}:${mm}:${ss}`;
            }

            function startCountdown() {
                if (!epTimeSpan || !epTimeValue) return;
                const endIso = epTimeSpan.getAttribute('data-end');
                const status = epTimeSpan.getAttribute('data-status');
                if (!endIso) return;
                const end = new Date(endIso);
                const tzOffsetMs = new Date().getTimezoneOffset() * 60000; // local offset in ms

                function tick() {
                    if (epTimeSpan.getAttribute('data-status') === 'Paused') return; // don't update while paused
                    const nowLocal = new Date(Date.now());
                    const remaining = end - nowLocal;
                    epTimeValue.textContent = formatRemaining(remaining);
                    if (remaining <= 0) {
                        clearInterval(countdownInterval);
                    }
                }

                // initial draw
                tick();
                // update every second
                countdownInterval = setInterval(tick, 1000);
            }

            startCountdown();

            // Update countdown opacity and status dynamically on status events
            window.Echo.channel('updating-enrollment-period-status').listen('EnrollmentPeriodStatusUpdated', (event) => {
                if (!epTimeSpan) return;
                epTimeSpan.setAttribute('data-status', event.enrollmentPeriod.status);
            });





        });
    </script>
@endpush
