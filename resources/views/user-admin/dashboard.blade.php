@extends('layouts.admin')

@section('header')
    <div class="flex flex-row items-center space-x-2 px-[14px] py-[10px]">
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
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">LRN</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">Name</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">Age</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">Birthdate</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">Program</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">Grade Level</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">Created at</th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $application)
                        <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->lrn }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->full_name }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->age }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->birthdate }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->desired_program }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ $application->grade_level }}</td>
                            <td class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">{{ \Carbon\Carbon::parse($application->created_at)->timezone('Asia/Manila')->format('M. d - g:i A') }}</td>

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
    let pendingApplications = document.querySelector('#pending-application');

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
            console.log(event.pendingCount);
            pendingApplications.innerHTML = event.pendingCount;

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