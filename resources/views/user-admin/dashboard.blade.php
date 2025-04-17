@extends('layouts.admin')

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

    <div class="flex flex-row items-center justify-between space-x-2 px-[14px] py-4">
        <span class="">Academic Term: <span class="font-bold">2023-2024, 1st Semester</span></span>
        <div>
            <button class="border border-[#1A73E8] bg-[#1A73E8] px-3 py-1 text-[14px] font-bold text-[#f8f8f8] rounded-md">Change S.Y.</button>
            <button class="border border-[#1A73E8] px-3 py-1 text-[#1A73E8] font-bold text-[14px] rounded-md">Add Enrollment Period</button>
        </div>
    </div>
</x-header-container>

@endsection

@section('stat')

    <div class="flex flex-row space-x-2">
        <div class="bg-[#f8f8f8] flex-1 border border-[#1e1e1e]/20 rounded-md px-[16px] py-4">
            <div class="flex flex-row justify-between">
                <span class="font-bold">Active Enrollment Period</span>
                <span class="text-[14px] text-[#EA4335] font-bold">-</span>
            </div>
            <div class="flex flex-col py-2 opacity-30 hidden">
                <span class="font-bold text-[16px]">-</span>
                <span class="font-medium text-[14px] opacity-60">-</span>
            </div>
            <div class="flex flex-row justify-evenly py-6 ">
                {{-- <div class="flex flex-row gap-4 items-center">
                    <div class="bg-[#E6F4EA] px-4 py-3 rounded-full">
                        <i class="fi fi-ss-calendar-check text-[20px] text-[#34A853]"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold">March 15</span>
                        <span class="text-[14px] opacity-60">Start Date</span>
                    </div>
                </div>
                <span class="flex items-center">
                    <i class="fi fi-rs-arrow-right text-[24px] opacity-40"></i>
                </span>
                <div class="flex flex-row gap-4 items-center">
                    <div class="bg-[#FCE8E6] px-4 py-3 rounded-full">
                        <i class="fi fi-ss-calendar-xmark text-[20px] text-[#EA4335]"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold">March 30</span>
                        <span class="text-[14px] opacity-60">End Date</span>
                    </div>
                </div> --}}
            <div class="flex flex-col justify-center items-center gap-2">
                <img src="{{ asset('images/empty-box.png') }}" alt="empty-box" class="size-[120px]">
                <span class="opacity-60">There is currently no active enrollment period</span>
            </div>

                
            </div>
            <div class="flex flex-row items-center justify-between pt-2 hidden">
                <span class="text-[15px] opacity-30">Time Remaining: <span class="opacity-100 font-bold">-</span></span>
                <div>
                    <button class="border border-[#F97316] px-3 py-1 rounded-md text-[#F97316] font-bold text-[14px]">Edit</button>
                    <button class="bg-[#F97316] px-3 py-1 rounded-md text-[14px] text-[#f8f8f8] font-bold">End Enrollment Period</button>
                </div>
            </div>
        </div>

        <div class="bg-[#f8f8f8] flex-1 border border-[#1e1e1e]/20 rounded-md px-[16px] py-4 space-y-3">
            <span class="font-bold">Application Overview</span>
            <div class="flex flex-row space-x-3">

                <div class="w-1/3 flex flex-col space-y-5">
                    <span class="flex flex-col items-center justify-center py-8 bg-[#E3ECFF]/30 rounded-md">
                        <span id="total-application" class="text-[40px] font-bold">{{ $applicationCount }}<span class="text-[20px] opacity-60">/50</span></span>
                        <span class="font-medium opacity-60">Total Applications</span>
                    </span>

                    <span class="w-full space-y-2">
                        <div class="w-full">
                            <div class="bg-[#d9d9d9] h-1 rounded-full w-full">
                                <div class="text-[#f8f8f8]/0 bg-blue-500 rounded-full overflow-hidden h-full w-1/3">.</div>
                            </div>
                        </div>
                        <div class="text-[14px]">0% of Max applications</div>
                    </span>
                </div>

                <div class="w-2/3 grid grid-cols-2 gap-2">
                    <div class="flex flex-col flex-1 bg-[#E3ECFF]/30 gap-1 px-4 py-4 rounded-md">
                        <div class="flex flex-row items-center gap-3">
                            <div class="bg-[#FFF4E5] border border-[#FBBC04]/60 text-[#FBBC04] rounded-full text-[20px] font-bold size-10 flex items-center justify-center">{{ $pending_applications }}</div>
                            <p class="font-medium text-[16px]">Pending</p>
                        </div>
                        <span class="self-center text-[14px] opacity-60"><a href="">View All</a></span>
                    </div>
                    <div class="flex flex-col flex-1 bg-[#E3ECFF]/30 gap-1 px-4 py-4 rounded-md">
                        <div class="flex flex-row items-center gap-3">
                            <div class="bg-[#E6F4EA] border border-[#34A853]/60 text-[#34A853] rounded-full text-[20px] font-bold size-10 flex items-center justify-center">{{ $selected_applications }}</div>
                            <p class="font-medium text-[16px]">Selected
                        </div>
                        <span class="self-center text-[14px] opacity-60"><a href="">View All</a></span>
                    </div>
                    
                    <div class="flex flex-col flex-1 bg-[#E3ECFF]/30 gap-1 px-4 py-4 rounded-md">
                        <div class="flex flex-row items-center gap-2">
                            <div class="bg-[#F3E5F5] border border-[#9C27B0]/60 text-[#9C27B0] rounded-full text-[20px] font-bold size-10 flex items-center justify-center">0</div>
                            <p class="font-medium text-[15px]">Pending Docs</p>
                        </div>
                        <span class="self-center text-[14px] opacity-60"><a href="">View All</a></span>
                    </div>
                    <div class="flex flex-col flex-1 bg-[#E3ECFF]/30 gap-1 px-4 py-4 rounded-md">
                        <div class="flex flex-row items-center gap-3">
                            <div class="bg-[#E7F0FD] border border-[#1A73E8]/60 rounded-full text-[20px] text-[#1A73E8] font-bold size-10 flex items-center justify-center">0</div>
                            <p class="font-medium text-[16px]">Enrolled</p>
                        </div>
                        <span class="self-center text-[14px] opacity-60"><a href="">View All</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('content')
    <div class="flex flex-col">
        <div class="text-start border-b border-[#1e1e1e]/10 pl-[14px] py-[10px]">
            <p class="text-[16px] md:text-[18px] font-bold">Recent Applications</p>
        </div>

        <div class="flex flex-col items-center flex-grow px-[14px] py-[10px] space-y-2">
            <div class="border border-[#1e1e1e]/15 self-start my-custom-search">
                <i class="fi fi-rs-search text-[#0f111c]"></i>
                <input type="search" name="" id="myCustomSearch" class="bg-transparent" placeholder="Search...">
            </div>

            <div class="w-full">
                <table id="myTable" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">
                                <span class="mr-2">LRN</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Full Name</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Age</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Birthdate</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Program</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Grade Level</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2">Created at</span>
                                <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                              </th>
                              <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                        <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->applicationForm->lrn }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->applicationForm->full_name }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->applicationForm->age }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->applicationForm->birthdate }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->applicationForm->desired_program }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->applicationForm->grade_level }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ \Carbon\Carbon::parse($application->applicationForm->created_at)->timezone('Asia/Manila')->format('M. d - g:i A') }}</td>

                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate"><a href="/pending-application/form-details/{{ $application->id }}">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="module">
    let table;
    let totalApplications = document.querySelector('#total-application');

    document.addEventListener("DOMContentLoaded", function () {
        table = new DataTable('#myTable', {
            paging: false,
            pageLength: 10,
            searching: true,
            autoWidth: false,
            order: [[6, 'desc']],
            columnDefs: [
                { width: '16.66%', targets: '_all' }
            ],
        });

        table.on('draw', function () {
            let newRow = document.querySelector('#myTable tbody tr:first-child');

            // Select all td elements within the new row
            let cells = newRow.querySelectorAll('td');

            cells.forEach(function(cell) {
                cell.classList.add(
                    'px-4',        // Horizontal padding
                    'py-2',        // Vertical padding
                    'text-start',  // Align text to the start (left)
                    'font-regular',
                    'text-[14px]',
                    'opacity-80',
                    'truncate'
                );
            });

        });

        //Overriding default search input
        const customSearch = document.getElementById("myCustomSearch");
        const defaultSearch = document.querySelector(".dt-search");

        defaultSearch.remove();
        customSearch.addEventListener("input", function(e) {
            table.search(this.value).draw();
        });

        console.log(window.Echo);

        window.Echo.channel('fetching-recent-applications').listen('RecentApplicationTableUpdated', (event) => {
            console.log(event.total_applications);
            totalApplications.innerHTML = event.total_applications;

            let formattedDate = moment(event.application.created_at)
                        .tz('Asia/Manila')
                        .format('MMM. D - h:mm A');

            let row = table.row.add([
                event.application.lrn,
                event.application.full_name,
                event.application.age,
                event.application.birthdate,
                event.application.desired_program,
                event.application.grade_level,
                formattedDate,
                `<a href="/pending-application/form-details/${event.application.id}">View</a>`
            ]).order([6, 'desc']).draw();

            // Retrieve the node of the added row:
            var newRow = row.node();

            // Apply your classes for highlighting
            newRow.classList.add(
                'duration-300',
                'ease-in-out',
                'bg-[#FBBC04]/30'
            );

            // Remove highlight after 4000ms
            setTimeout(() => {
                newRow.classList.remove('bg-[#FBBC04]/30');
                newRow.classList.add(
                    'border-t-[1px]',
                    'border-[#1e1e1e]/15',
                    'duration-300',
                    'ease-in-out'
                );
            }, 4000);

        });

    });
</script>
@endpush