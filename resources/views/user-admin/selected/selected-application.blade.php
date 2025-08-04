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
                <a href="/selected-applications" class="block transition-colors hover:text-gray-900"> Selected Applications
                </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

        </ol>
    </nav>
@endsection

@section('modal')
    {{-- Schedule Interview Modal --}}
    <x-modal modal_id="interview-details-modal" modal_name="Interview Details" close_btn_id="interview-details-close-btn">

        <div class="flex flex-col gap-2 p-4 ">
            <div class="flex flex-col border border-[#1e1e1e]/10 bg-[#E3ECFF]/20 rounded-xl p-4 gap-4 ">
                <div class="flex flex-row gap-4 justify-start items-center">
                    <div class="rounded-full overflow-hidden bg-gray-200 ">
                        <img src="{{ asset('images/business-man.png') }}" alt="user-icon" class="size-14 user-select-none">
                    </div>
                    <div>
                        <p id="full_name" class="font-bold text-[16px]">Juan Dela Cruz</p>
                        <div class="flex flex-row items-center justify-start gap-1">
                            <p class="text-[14px] opacity-70 font-medium">Applicant ID: </p>
                            <span id="applicant_id" class="text-[14px] font-black">DAP-2031-001</span>
                        </div>

                    </div>
                </div>

            </div>
            <div class="flex flex-col border border-[#1e1e1e]/10 bg-[#E3ECFF]/20 rounded-xl p-4 py-6 gap-6">
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row justify-between items-center gap-4">
                        <div>
                            <i class="fi fi-sr-calendar-day flex justify-between items-center text-gray-600"></i>
                        </div>
                        <span class="font-medium text-gray-600">Interview Date:</span>
                    </div>
                    <span id="date" class="font-bold opacity-90">January 1, 2025</span>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row justify-between items-center gap-4">
                        <div>
                            <i class="fi fi-sr-calendar-clock flex justify-between items-center text-gray-600"></i>
                        </div>
                        <span class="font-medium text-gray-600">Interview Time:</span>
                    </div>
                    <span id="time" class="font-bold opacity-90">12:30 PM</span>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row justify-between items-center gap-4">
                        <div>
                            <i class="fi fi-ss-land-layer-location flex justify-between items-center text-gray-600"></i>
                        </div>
                        <span class="font-medium text-gray-600">Location:</span>
                    </div>
                    <span id="location" class="font-bold opacity-90">Room 1, Floor 3, Ewan Building</span>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-row justify-between items-center gap-4">
                        <div>
                            <i class="fi fi-sr-user flex justify-between items-center text-gray-600"></i>
                        </div>
                        <span class="font-medium text-gray-600">Interviewer:</span>
                    </div>
                    <span id="interviewer" class="font-bold opacity-90">Juan Dela Cruz</span>
                </div>
                <div class="flex flex-col justify-center items-center gap-2">
                    <span class="font-medium text-gray-600">Status:</span>
                    <div
                        class="flex justify-center items-center w-full bg-[#E6F4EA] border border-[#34A853]/40 p-2 rounded-xl gap-2 shadow-lg">

                        <div class="size-3 bg-[#34A853] rounded-full"></div>
                        <span id="status" class="font-bold text-[#34A853]">Ongoing</span>


                    </div>
                </div>

            </div>
        </div>

        <button id="cancel-btn" class="hidden">

        </button>
    </x-modal>
@endsection

@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Approved Applications</h1>
        <p class="text-[14px]  text-gray-900/60">List of approved applicants scheduled, awaiting, or ongoing interviews
        </p>
    </div>
@endsection

@section('content')
    <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10">
        <div class="flex flex-col items-center flex-grow p-5 space-y-2">
            <label for="myCustomSearch"
                class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 self-start rounded-xl py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150">
                <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                <input type="search" name="" id="myCustomSearch"
                    class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                    placeholder="Search by applicant id, name, interviewer">
                <button id="clear-btn"
                    class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                    <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                </button>
            </label>

            <div class="w-full">
                <table id="selectedTable" class="w-full table-fixed">
                    <thead class="text-[14px]">
                        <tr>
                            <th
                                class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">
                                <span class="mr-2 font-medium opacity-70">Applicant Id</span>
                                <i class="fi fi-sr-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2 font-medium opacity-70">Full Name</span>
                                <i class="fi fi-sr-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2 font-medium opacity-70">Program</span>
                                <i class="fi fi-sr-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2 font-medium opacity-70">Grade Level</span>
                                <i class="fi fi-sr-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2 font-medium opacity-70">Status</span>
                                <i class="fi fi-sr-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                <span class="mr-2 font-medium opacity-70">Created at</span>
                                <i class="fi fi-sr-sort text-[12px] cursor-pointer opacity-60"></i>
                            </th>
                            <th
                                class="w-1/7 text-center font-medium bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tr-[9px] px-4 py-2">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selected_applicants as $selected_applicant)
                            @if ($selected_applicant->interview->status == 'Scheduled' || $selected_applicant->interview->status == 'Pending')
                                <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                    <td
                                        class="w-1/8 text-start font-regular py-[8px] font-semibold text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ $selected_applicant->applicant_id }}</td>
                                    <td
                                        class="w-1/8 text-start font-regular py-[8px] font-semibold text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ $selected_applicant->applicationForm->fullName() }}</td>

                                    <td
                                        class="w-1/8 text-start font-regular py-[8px] font-semibold text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ $selected_applicant->applicationForm->primary_track }}</td>
                                    <td
                                        class="w-1/8 text-start font-regular py-[8px] font-semibold text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ $selected_applicant->applicationForm->grade_level }}</td>
                                    <td
                                        class="w-1/8 text-start font-regular py-[8px] font-semibold text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ $selected_applicant->interview->status }}</td>
                                    <td
                                        class="w-1/8 text-start font-regular py-[8px] font-semibold text-[14px] opacity-80 px-4 py-2 truncate">
                                        {{ \Carbon\Carbon::parse($selected_applicant->applicationForm->created_at)->timezone('Asia/Manila')->format('M. d - g:i A') }}
                                    </td>

                                    <td
                                        class="w-1/8 text-center flex justify-center items-center font-regular py-[8px] text-[14px] px-4 py-2 truncate">
                                        <x-nav-link
                                            href="/selected-application/interview-details/{{ $selected_applicant->id }}"
                                            class="flex flex-row justify-center items-center gap-2 py-1 px-4 bg-blue-500 text-white rounded-xl font-bold hover:bg-blue-400 hover:ring hover:ring-blue-200 transition duration-200">
                                            <i class="fi fi-rs-eye flex justify-center items-center"></i>
                                            View
                                        </x-nav-link>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('ongoing-interviews')
    <div class="flex flex-col justify-center items-start text-start px-[14px]">
        <h1 class="text-[18px] font-semibold">Ongoing Interviews</h1>
        </p>
    </div>
    <div class="flex flex-col bg-[#f8f8f8] rounded-xl shadow-sm border border-[#1e1e1e]/10 p-2 gap-2">
        <div class="flex flex-col">
            <div class="flex flex-col items-center flex-grow px-[14px] py-[10px] space-y-2">

                <label for="myCustomSearch1"
                    class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 self-start rounded-xl py-1 px-2 gap-2 w-[40%] hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150">
                    <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                    <input type="search" name="" id="myCustomSearch1"
                        class="my-custom-search bg-transparent outline-none text-[14px] w-full peer"
                        placeholder="Search by applicant id, name, interviewer">
                    <button id="clear-btn-1"
                        class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                        <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                    </button>
                </label>

                <div class="w-full">
                    <table id="ongoingTable" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th
                                    class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 rounded-tl-[9px] px-4 py-2">
                                    <span class="mr-2 font-medium">Applicant Id</span>
                                    <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                                </th>
                                <th class="w-1/7 text-start bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium">Full Name</span>
                                    <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium">Interviewer</span>
                                    <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium">Status</span>
                                    <i class="fi fi-ss-sort text-[12px] cursor-pointer opacity-60"></i>
                                </th>
                                <th class="w-1/7 text-center bg-[#E3ECFF] border-b border-[#1e1e1e]/15 px-4 py-2">
                                    <span class="mr-2 font-medium">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applicants as $applicant)
                                @isset($applicant->interview)
                                    @if ($applicant->interview->status === 'Ongoing')
                                        <tr class="border-t-[1px] border-[#1e1e1e]/15 w-full rounded-md">
                                            <td
                                                class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                {{ $applicant->applicant_id }}</td>
                                            <td
                                                class="w-1/8 text-start font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                {{ $applicant->applicationForm->full_name }}</td>
                                            <td
                                                class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                {{ $applicant->applicationForm->age }}</td>
                                            <td
                                                class="w-1/8 text-center font-regular py-[8px] text-[14px] opacity-80 px-4 py-2 truncate">
                                                {{ $applicant->applicationForm->birthdate }}</td>
                                            <td class="w-1/8 text-center font-regular py-[8px] text-[14px] px-4 py-2 truncate">
                                                <button data-applicant-id="{{ $applicant->id }}"
                                                    id="open-interview-details-btn-{{ $applicant->id }}"
                                                    class="open-interview-details-btn py-1 px-4 bg-blue-500 text-white rounded-xl font-bold hover:bg-blue-400 hover:ring hover:ring-blue-200 transition duration-200">Details
                                                </button>
                                            </td>

                                        </tr>
                                    @endif
                                @endisset
                            @endforeach
                        </tbody>
                    </table>
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
            clearSearch
        } from "/js/clearSearch.js"

        let table1;
        let table2;
        let pendingApplications = document.querySelector('#pending-application');

        let applicants = @json($applicants);

        document.addEventListener("DOMContentLoaded", function() {

            let buttons = document.querySelectorAll('.open-interview-details-btn');

            buttons.forEach((button, index) => {



                let id = button.getAttribute('data-applicant-id');
                // console.log(id)
                initModal('interview-details-modal', `open-interview-details-btn-${id}`,
                    'interview-details-close-btn', 'cancel-btn');

                button.addEventListener('click', () => {

                    const full_name = document.querySelector('#full_name');
                    const applicant_id = document.querySelector('#applicant_id');
                    const date = document.querySelector('#date');
                    const time = document.querySelector('#time');
                    const location = document.querySelector('#location');
                    const interviewer = document.querySelector('#interviewer');
                    const status = document.querySelector('#status');
                    let id = button.getAttribute('data-applicant-id');

                    let rawDate = applicants[index].interview.date
                    const formattedDate = new Date(rawDate).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                    });

                    const rawTime = applicants[index].interview.time;
                    const [hours, minutes] = rawTime.split(':');
                    const times = new Date();
                    times.setHours(parseInt(hours), parseInt(minutes));

                    const formattedTime = times.toLocaleTimeString([], {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true,
                    });

                    full_name.innerHTML = applicants[index].application_form.full_name;
                    applicant_id.innerHTML = applicants[index].applicant_id;
                    date.innerHTML = formattedDate;
                    time.innerHTML = formattedTime;
                    location.innerHTML = applicants[index].interview.location
                    status.innerHTML = applicants[index].interview.status

                    console.log(applicants[index])
                    console.log(id)
                })


            })

            // applicants.forEach((applicants, index) => {
            //     console.log(applicants.interview);





            // })

            //Overriding default search input
            const customSearch1 = document.getElementById("myCustomSearch");
            const customSearch2 = document.getElementById("myCustomSearch1");


            table1 = new DataTable('#selectedTable', {
                paging: true,
                pageLength: 10,
                searching: true,
                autoWidth: false,
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                    width: '16.66%',
                    targets: '_all'
                }],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                }
            });


            customSearch1.addEventListener("input", function() {
                table1.search(this.value).draw();
            });


            table1.on('draw', function() {
                let newRow = document.querySelector('#selectedTable tbody tr:first-child');

                // Select all td elements within the new row
                let cells = newRow.querySelectorAll('td');

                cells.forEach(function(cell) {
                    cell.classList.add(
                        'px-4', // Horizontal padding
                        'py-2', // Vertical padding
                        'text-center', // Align text to the start (left)
                        'font-regular',
                        'text-[14px]',
                        'opacity-80',
                        'truncate'
                    );
                });

            });

            table1.on("init", function() {
                const defaultSearch = document.querySelector("#dt-search-0");
                if (defaultSearch) {
                    defaultSearch.remove();
                }

                customSearch1.addEventListener("input", function() {
                    table1.search(this.value).draw();
                });
            });

            table2 = new DataTable('#ongoingTable', {
                paging: false,
                pageLength: 20,
                searching: true,
                autoWidth: false,
                order: [
                    [6, 'desc']
                ],
                columnDefs: [{
                    width: '16.66%',
                    targets: '_all'
                }],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                }
            });


            customSearch2.addEventListener("input", function() {
                table2.search(this.value).draw();
            });


            table2.on('draw', function() {
                let newRow = document.querySelector('#ongoingTable tbody tr:first-child');

                // Select all td elements within the new row
                let cells = newRow.querySelectorAll('td');

                cells.forEach(function(cell) {
                    cell.classList.add(
                        'px-4', // Horizontal padding
                        'py-2', // Vertical padding
                        'text-center', // Align text to the start (left)
                        'font-regular',
                        'text-[14px]',
                        'opacity-80',
                        'truncate'
                    );
                });

            });

            clearSearch('clear-btn', 'myCustomSearch', table1)
            clearSearch('clear-btn-1', 'myCustomSearch1', table2)


            // const form = document.getElementById('uploadForm')

            // form.addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     const fileInput = document.getElementById('fileInput');
            //     const files = fileInput.files;

            //     const formData = new FormData();

            //     console.log(files)

            //     uploadedFiles.forEach((item, i) => {
            //         // Add the file
            //         formData.append(`documents[${i}]`, item.file);

            //         // Add the assigned option (or use files_assigned[i] = ...)
            //         formData.append(`documents_id[${i}]`, item.assignedTo);
            //     });

            //     console.log(formData)

            //     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
            //         'content');

            //     axios.post('/submit-document', formData, {
            //             headers: {
            //                 'X-CSRF-TOKEN': csrfToken
            //                 // Do NOT set Content-Type manually — Axios handles it
            //             }
            //         })
            //         .then(response => {
            //             console.log('Upload successful:', response.data);
            //             window.location.reload();
            //         })
            //         .catch(error => {
            //             console.error('Upload failed:', error.response?.data || error.message);
            //             window.location.reload();
            //         });

            // });




            // defaultSearch0.remove();
            // customSearch1.addEventListener("input", function(e) {
            //     table1.search(this.value).draw();
            // });
            // defaultSearch1.remove();
            // customSearch2.addEventListener("input", function(e) {
            //     table2.search(this.value).draw();
            // });


        });
    </script>
@endpush
