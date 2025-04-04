@extends('layouts.admin')

@section('header')
    <span class="flex flex-row space-x-4">
        <i class="fi fi-rs-chart-simple text-[20px]"></i>
        <p class="text-[18px] md:text-[20px] font-bold">Dashboard</p>
    </span>
@endsection

@section('content')
    <div class="flex flex-col h-full">
        <div class="text-start border-b border-[#1e1e1e]/10 pl-[22px] py-[16px]">

            <p class="text-[16px] md:text-[18px] font-bold">Recent Applications</p>

        </div>

        <div class="flex flex-col items-center flex-grow px-[22px] py-[10px] space-y-2">

            <div class="border border-[#1e1e1e]/15 self-start">
                <i class="fi fi-rs-search text-[#0f111c]"></i>
                <input type="search" name="" id="" class="bg-transparent" placeholder="Search...">
            </div>

            <div class="border border-[#1e1e1e]/15 rounded-md w-full ">
                {{-- <table class="w-full">
                    <tr class="bg-[#E3ECFF] border-b border-[#1e1e1e]/15">
                        <th class="text-start py-[8px]">LRN</th>
                        <th class="text-start py-[8px]">Full Name</th>
                        <th class="text-start py-[8px]">Age</th>
                        <th class="text-start py-[8px]">Birthdate</th>
                        <th class="text-start py-[8px]">Program</th>
                        <th class="text-start py-[8px]">Grade Level</th>
                    </tr>

                    <tr class="border-b border-[#1e1e1e]/15 ">
                        <th class="text-start font-regular py-[8px] text-[14px] opacity-80">165511090059</th>
                        <th class="text-start font-regular py-[8px] text-[14px] opacity-80">Pedro Penduko</th>
                        <th class="text-start font-regular py-[8px] text-[14px] opacity-80">21</th>
                        <th class="text-start font-regular py-[8px] text-[14px] opacity-80">January 1, 2001</th>
                        <th class="text-start font-regular py-[8px] text-[14px] opacity-80">HUMSS</th>
                        <th class="text-start font-regular py-[8px] text-[14px] opacity-80">Grade 11</th>
                    </tr>
                    <tr>
                        <th class=" text-start">165511090059</th>
                        <th class=" text-start">Pedro Penduko</th>
                        <th class=" text-start">21</th>
                        <th class=" text-start">January 1, 2001</th>
                        <th class=" text-start">HUMSS</th>
                        <th class=" text-start">Grade 11</th>
                    </tr>
    
                </table> --}}
                <table id="myTable" class="w-full">
                    <thead>
                        <tr>
                            <th class="bg-[#E3ECFF] border-b border-[#1e1e1e]/15">                <span class="flex items-center">
                                Name
                                <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                </svg>
                            </span></th>
                            <th class="bg-[#E3ECFF] border-b border-[#1e1e1e]/15">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="border-b border-[#1e1e1e]/15 text-start font-regular py-[8px] text-[14px] opacity-80">{{ $user->name }}</td>
                            <td class="border-b border-[#1e1e1e]/15 text-start font-regular py-[8px] text-[14px] opacity-80">{{ $user->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
@endsection