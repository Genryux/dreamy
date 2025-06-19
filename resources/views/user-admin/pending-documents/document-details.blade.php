@extends('layouts.admin')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li>
                <a href="#" class="block transition-colors hover:text-gray-900"> Applications </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="/pending-applications" class="block transition-colors hover:text-gray-900"> Pending Documents
                </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="#" class="block transition-colors hover:text-gray-900"> Documents Details </a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="flex flex-row items-center space-x-2 text-start pl-[14px] py-[10px]">
        <i class="fi fi-rs-member-list flex text-[#0f111c] flex flex-row items-center"></i>
        <p class="text-[14px] md:text-[16px] font-medium">Document submission details: <span class="font-bold">aasdas
            </span></p>
    </div>
    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>
            <div class="flex flex-row pl-[14px] py-[16px] text-[14px]">
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Grade</p>
                    <p class="font-bold"></p>
                </span>
                <span>
                    <p class="opacity-80">Track</p>
                    <p class="font-bold"></p>
                </span>
                <span>
                    <p class="opacity-80">Contact</p>
                    <p class="font-bold"></p>
                </span>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Interview Date</p>
                    <p class="font-bold"></p>
                </span>
                <span>
                    <p class="opacity-80">Interview Time</p>
                    <p class="font-bold"></p>
                </span>
                <span>
                    <p class="opacity-80">Location</p>
                    <p class="font-bold"></p>
                </span>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Interviewer</p>
                    <p class="font-bold"></p>
                </span>
                <span>
                    <p class="opacity-80">Status</p>

                </span>
            </div>
            <div class="flex flex-col flex-1 space-y-4">
                <span>
                    <p class="opacity-80">Remarks</p>
                    <p class="font-bold"></p>
                </span>
            </div>
        </div>
@endsection
