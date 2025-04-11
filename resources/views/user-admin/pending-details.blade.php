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

@section('header')
    <div class="px-[16px] py-[16px] flex flex-row items-center justify-between space-x-2">
        <div class="flex flex-row items-center space-x-2">
            <i class="fi fi-rs-member-list flex text-[#0f111c]"></i>
            <h2 class="text-[16px]"> <span class="text-[#0f111c]/80">Applicant Details:</span><span class="opacity-100 font-medium  font-bold"> Juan Dela Cruz</span></h2>
        </div>
        <div class="flex flex-row items-center space-x-1">
            <button class="border border-[#1e1e1e]/15 bg-[#199BCF] px-4 py-2 rounded-md text-[#f8f8f8] text-[14px] font-bold">Accept...</button>
            <button class="border border-[#1e1e1e]/15 px-4 py-2 rounded-md text-[#0f111c]/80 text-[14px] font-bold">Reject</button>
        </div>
    </div>

@endsection

@section('content')
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
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2 text-bold">With LRN:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2 text-bold">LRN:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Grade Level to Enroll:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Semester:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Primary Track:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Secondary Track:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Last Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">First Name:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Middle Name:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Extension Name:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Birthdate:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Age:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Place of Birth:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Mother Tongue:</td>
                </tr>
                <tr class="opacity-[0.87]">
                    <td class="px-4 py-2 text-[14px] border-b border-r border-[#1e1e1e]/15 w-1/2">Belong to any IP community:</td>
                    <td class="px-4 py-2 text-[14px] border-b border-[#1e1e1e]/15 w-1/2">Beneficiary of 4Ps:</td>
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
        <button class="my-2 border border-[#1e1e1e]/15 bg-[#199BCF] px-4 py-2 rounded-md text-[#f8f8f8] text-[14px] font-bold">Accept...</button>
        <button class="my-2 border border-[#1e1e1e]/15 px-4 py-2 rounded-md text-[#0f111c]/80 text-[14px] font-bold">Reject</button>
    </div>
</div>

@endsection
