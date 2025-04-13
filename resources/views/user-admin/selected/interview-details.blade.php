@extends('layouts.admin')

@section('breadcrumbs')
<nav aria-label="Breadcrumb" class="mb-4 mt-2">
    <ol class="flex items-center gap-1 text-sm text-gray-700">
      <li>
        <a href="#" class="block transition-colors hover:text-gray-900"> Applications </a>
      </li>
  
      <li class="rtl:rotate-180">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="size-4"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"
          />
        </svg>
      </li>
  
      <li>
        <a href="/selected-applications" class="block transition-colors hover:text-gray-900"> Selected Applications </a>
      </li>
  
      <li class="rtl:rotate-180">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="size-4"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
            clip-rule="evenodd"
          />
        </svg>
      </li>
  
      <li>
        <a href="#" class="block transition-colors hover:text-gray-900"> Interview Details </a>
      </li>
    </ol>
</nav>
  
@endsection

@section('modal')
<div id="modal-bg" class="absolute bottom-0 left-0 bg-[#0f111c]/40 h-0 w-full z-20 ease-in-out duration-150 overflow-hidden">

    <div class="flex items-center justify-center h-screen w-screen">
    <x-modal modal="Record Interview Result">

        <form action="" class="py-2 px-4 space-y-2">

            <label for="passed" class="flex items-center justify-between has-checked:bg-red-500 has-checked:ring-red-500">
                <p>Passed</p>
                <input type="radio" name="status" id="passed" checked class="">
            </label>

            <label for="failed" class="flex items-center justify-between has-checked:ring-2 has-checked:ring-red-500">
                <p>Failed</p>
                <input type="radio" name="status" id="failed" class="">
            </label>
            

        </form>

        <x-slot name="modal_buttons">
            <button id="cancel-btn" class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
            <button form="interview-form" class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
        </x-slot>
    </x-modal>
    </div>
</div>
@endsection

@section('content')
<div class="flex flex-col">
    <div class="flex flex-row items-center space-x-2 text-start pl-[14px] py-[10px]">
        <i class="fi fi-rs-member-list flex text-[#0f111c] flex flex-row items-center"></i>
        <p class="text-[14px] md:text-[16px] font-bold">Interview details: <span></span></p>
    </div>
    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>
    <div class="flex flex-row pl-[14px] py-[16px] text-[14px]">
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Grade</p>
                <p class="font-bold">Grade 11</p>
            </span>
            <span>
                <p class="opacity-80">Track</p>
                <p class="font-bold">HUMSS</p>
            </span>
            <span>
                <p class="opacity-80">Contact</p>
                <p class="font-bold">09123456789</p>
            </span>
        </div>
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Interview Date</p>
                <p class="font-bold">-</p>
            </span>
            <span>
                <p class="opacity-80">Interview Time</p>
                <p class="font-bold">-</p>
            </span>
            <span>
                <p class="opacity-80">Location</p>
                <p class="font-bold">-</p>
            </span>
        </div>
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Interviewer</p>
                <p class="font-bold">-</p>
            </span>
            <span>
                <p class="opacity-80">Status</p>
                <p class="font-bold">-</p>
            </span>
        </div>
        <div class="flex flex-col flex-1 space-y-4">
            <span>
                <p class="opacity-80">Remarks</p>
                <p class="font-bold">-</p>
            </span>
        </div>
    </div>
    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>
    <div class="flex flex-row items-center justify-between px-[14px] py-[10px] text-[14px] font-medium">
        <button id="show-details-btn" class="flex flex-row gap-2 border border-[#1e1e1e]/15 rounded-md px-2 py-1 text-[#0f111c]/80">View Applicant's Full Details <i class="fi fi-rs-angle-small-down flex flex-row items-center text-[18px] text-[#0f111c]/80"></i></button>
        <button id="record-btn" class="border border-[#1e1e1e]/15 bg-[#199BCF] text-[#f8f8f8] rounded-md px-2 py-1">Record Interview Result</button>
    </div>
</div>

    <div id="details-container" class="hidden flex-col px-[14px] py-[14px] space-y-3 ">
    <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
        <table class="text-[#0f111c] w-full">
            <thead class="">
                <tr class="">
                    <th class="px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px]">Learner Information</th>
                    <th class="bg-[#E3ECFF] text-start rounded-tr-[8px]"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-t border-[#1e1e1e]/15 opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px]">Returning (Balik-Aral):</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">With LRN:<span class="font-bold"> Yes</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN: <span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to Enroll:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary Track:<span class="font-bold"> </span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span class="font-bold"> </span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of Birth:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP community:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px]">Learner with disability:</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
        <table class="text-[#0f111c] w-full">
            <thead class="">
                <tr class="">
                    <th class="border-r border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">Current Address</th>
                    <th class="px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]">Permanent Address</th>
                </tr>
            </thead>
            <tbody>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">House No:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">House No:<span class="font-bold"></span> </td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Sitio/Street Name:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Barangay:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Municipality/City:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Municipality/City:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Country:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Country:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Zip Code:</td>
                    <td class="px-4 py-2 text-[14px] w-1/2">Zip Code:</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
        <table class="text-[#0f111c] w-full table-fixed">
            <thead class="">
                <tr class="">
                    <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]">Parent/Guardian's Information</th>
                    <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start text-[16px]"></th>
                    <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Mother's Information:</td>
                    <td class="px-4 py-2 text-[16px] border-b border-r border-[#1e1e1e]/15 font-bold">Father's Information:<span class="font-bold"></span></td>
                    <td class="px-4 py-2 text-[16px] border-b border-[#1e1e1e]/15 font-bold">Guardian's Information:<span class="font-bold"></span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">First Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:</td>
                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Contact Number:</td>
                    <td class="px-4 py-2 text-[14px] w-1/2">Contact Number:</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class=" border border-[#1e1e1e]/15 rounded-[8px]">
        <table class="text-[#0f111c] w-full">
            <thead class="">
                <tr class="">
                    <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tl-[8px] text-[16px]"> Other Informations </th>
                    <th class="border-b border-[#1e1e1e]/15 px-4 py-2 bg-[#E3ECFF] text-start rounded-tr-[8px] text-[16px]"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">Preferred Class Schedule:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold"></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-r border-[#1e1e1e]/15 w-1/2">Parent/Guardian's Signature:</td>
                    <td class="px-4 py-2 text-[14px] w-1/2">Date Applied:</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">

    let modal = document.querySelector('#modal-bg');
    let openButton = document.querySelector('#record-btn');
    let closeButton = document.querySelector('#close-btn');
    let cancelButton = document.querySelector('#cancel-btn');
    let body = document.querySelector('body')

    document.addEventListener("DOMContentLoaded", function () {

        let showBtn = document.querySelector('#show-details-btn');
        let detailsContainer = document.querySelector('#details-container');

        showBtn.addEventListener('click', function () {

            if (detailsContainer.classList.contains('hidden')) {
                detailsContainer.classList.remove('hidden');
                detailsContainer.classList.add('flex')
            } else if (!detailsContainer.classList.contains('hidden')) {
                detailsContainer.classList.remove('flex');
                detailsContainer.classList.add('hidden')
            }

        })

        openButton.addEventListener('click', function() {

            if (modal.classList.contains('h-0')) {

                modal.classList.remove('h-0');
                modal.classList.add('h-full');
                body.classList.add('overflow-hidden')

            }

            if (!modal.classList.contains('h-0')) {



                closeButton.addEventListener('click', () => {
                    
                    modal.classList.remove('h-full');
                    modal.classList.add('h-0');
                    body.classList.remove('overflow-hidden')
                    
                })

                modal.addEventListener('click', () => {
                    
                    modal.classList.remove('h-full');
                    modal.classList.add('h-0');
                    body.classList.remove('overflow-hidden')
                    
                })

                cancelButton.addEventListener('click', () => {
                    
                    modal.classList.remove('h-full');
                    modal.classList.add('h-0');
                    body.classList.remove('overflow-hidden')
                    
                })
            }

        })

    });
</script>
@endpush
