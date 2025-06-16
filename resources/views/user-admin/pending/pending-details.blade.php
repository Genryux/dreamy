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
        <a href="/pending-applications" class="block transition-colors hover:text-gray-900"> Pending Applications </a>
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
        <a href="#" class="block transition-colors hover:text-gray-900"> Applicant Details </a>
      </li>
    </ol>
</nav>
  
@endsection

@section('modal')
<div id="modal-bg" class="absolute bottom-0 left-0 bg-[#0f111c]/40 h-0 w-full z-20 ease-in-out duration-150 overflow-hidden">

    <div class="flex items-center justify-center h-screen w-screen">

        <div id="modal" class="bg-[#f8f8f8] flex flex-col rounded-md w-[40%]" onclick="event.stopPropagation()">

            <span class="px-4 py-2 flex flex-row items-center justify-between">
                <p class="font-bold">Accept & Schedule Interview</p>
                <i id="close-btn" class="fi fi-rs-cross-small text-[20px] flex items-center rounded-full cursor-pointer hover:ring hover:ring-[#1e1e1e]/15"></i>
            </span>

            <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

            <form action="/set-interview/{{$form->applicant_id}}" method="post" id="interview-form" class="flex flex-col space-y-2 px-4 py-2">
                @csrf
                <div class="flex flex-row space-x-2">
                    <div class="flex-1 space-y-1">
                        <label for="date" class="text-[14px] font-bold">Date</label>
                        <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-calendar-day flex items-center opacity-60"></i>
                            <input type="date" name="date" id="date" class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>
                        
                    </div >
                    <div class="flex-1 space-y-1">
                        <label for="time" class="text-[14px] font-bold">Time</label>
                        <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-clock-five flex items-center opacity-60"></i>
                            <input type="time" name="time" id="time" class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>
                        
                    </div>
                    <div class="flex-1 space-y-1">
                        <label for="location" class="text-[14px] font-bold">Location</label>
                        <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                            <i class="fi fi-rs-marker flex items-center opacity-60"></i>
                            <input type="text" name="location" id="location" class="bg-transparent outline-none font-medium text-[14px] w-full">
                        </div>
                    </div>
                </div>

                <div class="flex-1 space-y-1">
                    <label for="" class="text-[14px] font-bold">Assign to</label>
                    <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] w-2/3 focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-user flex items-center opacity-60"></i>
                        <select name="" id="" class="bg-transparent outline-none font-medium text-[14px] w-full">
                            <option value="" class="font-Manrope">Juan Dela Cruz</option>
                            <option value="">Peter Dela Cruz</option>
                        </select>
                    </div>
                </div>

                <div class="flex-1 space-y-1">
                    <label for="add_info" class="text-[14px] font-bold">Additional Information</label>
                    <div class="flex items-center px-2 py-2 rounded-md bg-[#E3ECFF] focus-within:ring-2 focus-within:ring-[#199BCF]/40 space-x-2">
                        <i class="fi fi-rs-info flex items-center opacity-60"></i>
                        <textarea name="add_info" id="add_info" cols="10" rows="10" class="bg-transparent outline-none font-medium text-[14px] w-full resize-none h-[100px]"></textarea>
                    </div>
                </div>

            </form>

            <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

            <div class="flex justify-end px-4 py-3 space-x-1">
                <button id="cancel-btn" class="border border-[#1e1e1e]/15 text-[14px] px-2 py-1 rounded-md text-[#0f111c]/80 font-bold">Cancel</button>
                <button form="interview-form" name="action" value="accept-with-schedule" class="bg-[#199BCF] text-[14px] px-2 py-1 rounded-md text-[#f8f8f8] font-bold">Confirm</button>
            </div>

        </div>
        
    </div>

</div>
@endsection

@section('header')

<x-header-container>
    <div class="px-[16px] py-[16px] flex flex-row items-center justify-between space-x-2">
        <div class="flex flex-row items-center space-x-2">
            <i class="fi fi-rs-member-list flex text-[#0f111c]"></i>
            <h2 class="text-[16px]"> <span class="text-[#0f111c]/80">Applicant Details:</span><span class="opacity-100 font-medium  font-bold"> {{ $form->full_name }} </span></h2>
        </div>
        <div class="flex flex-row items-center space-x-1">
            <button type="submit" name="action" form="interview-form" value="accept-only" class="border border-[#1e1e1e]/15 bg-[#199BCF] px-2 py-1 rounded-md text-[#f8f8f8] text-[14px] font-bold">Accept Only</button>
            <button id="accept-btn" class="border border-[#1e1e1e]/15 bg-[#199BCF] px-2 py-1 rounded-md text-[#f8f8f8] text-[14px] font-bold">Accept & Schedule</button>
            <button id="reject-btn" class="border border-[#1e1e1e]/15 px-2 py-1 rounded-md text-[#0f111c]/80 text-[14px] font-bold">Reject</button>
        </div>
    </div>
</x-header-container>

@endsection

@section('content')

@error('date')
    {{ $message }}
@enderror
@error('time')
    {{ $message }}
@enderror
@error('location')
    {{ $message }}
@enderror
@error('add_info')
    {{ $message }}
@enderror
@error('status')
    {{ $message }}
@enderror
@error('remarks')
    {{ $message }}
@enderror


<div class="px-[14px] py-[14px] space-y-3">
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
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN: <span class="font-bold">{{ $form->lrn }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to Enroll:<span class="font-bold"> {{ $form->grade_level }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:<span class="font-bold">{{ $form->lrn }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary Track:<span class="font-bold"> {{ $form->desired_program }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:<span class="font-bold">{{ $form->lrn }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:<span class="font-bold">{{ $form->lrn }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:<span class="font-bold">{{ $form->lrn }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:<span class="font-bold">{{ $form->lrn }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:<span class="font-bold">{{ $form->lrn }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:<span class="font-bold"> {{ $form->birthdate }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:<span class="font-bold"> {{ $form->age }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of Birth:<span class="font-bold">{{ $form->lrn }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:<span class="font-bold">{{ $form->lrn }}</span></td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP community:<span class="font-bold">{{ $form->lrn }}</span></td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:<span class="font-bold">{{ $form->lrn }}</span></td>
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
    <div class="flex flex-row items-center justify-end space-x-1">
        <button id="accept-btn" class="my-2 border border-[#1e1e1e]/15 bg-[#199BCF] px-4 py-2 rounded-md text-[#f8f8f8] text-[14px] font-bold">Accept...</button>
        <button id="reject-btn" class="my-2 border border-[#1e1e1e]/15 px-4 py-2 rounded-md text-[#0f111c]/80 text-[14px] font-bold">Reject</button>
    </div>
</div>

@endsection

@push('scripts')
    <script type="module">

        let modal = document.querySelector('#modal-bg');
        let openButton = document.querySelector('#accept-btn');
        let closeButton = document.querySelector('#close-btn');
        let cancelButton = document.querySelector('#cancel-btn');
        let body = document.querySelector('body')


        document.addEventListener("DOMContentLoaded", function () {

            openButton.addEventListener('click', function() {

                if (modal.classList.contains('h-0')) {

                    modal.classList.remove('h-0');
                    modal.classList.add('h-full');
                    body.classList.add('overflow-hidden')
                
                }

                if (!modal.classList.contains('h-0')) {

                    cancelButton.addEventListener('click', () => {
                        
                        modal.classList.remove('h-full');
                        modal.classList.add('h-0');
                        body.classList.remove('overflow-hidden')
                        
                    })

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
                }
            
            })


        })
    </script>
@endpush
