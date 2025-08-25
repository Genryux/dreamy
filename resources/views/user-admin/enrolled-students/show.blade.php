@extends('layouts.admin')
@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="mb-4 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li>
                <a href="/enrolled-students" class="block transition-colors hover:text-gray-900"> Enrolled Students </a>
            </li>

            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>

            <li>
                <a href="/selected-applications" class="block transition-colors hover:text-gray-900"> Student Information
                </a>
            </li>

        </ol>
    </nav>
@endsection
@section('header')
    <div class="flex flex-col justify-center items-start text-start px-[14px] py-2">
        <h1 class="text-[20px] font-black">Student Information</h1>
        <p class="text-[14px]  text-gray-900/60">View and manage individual student information and records.
        </p>
    </div>
@endsection
@section('content')
    <div class="flex flex-row justify-center items-start gap-4">
        <div
            class="flex flex-row justify-center items-start flex-grow p-6  bg-[#f8f8f8] rounded-xl shadow-md border border-[#1e1e1e]/10 w-[40%]">
            {{-- info container --}}
            <div class="flex-1 flex flex-col gap-4 border-r border-[#1e1e1e]/10 pr-6">
                {{-- profile --}}
                <div class="flex flex-row gap-4">
                    <img src="{{ asset('images/business-man.png') }}" alt=""
                        class="size-20 rounded-md ring ring-gray-200">
                    <div class="pt-1">
                        <p class="text-lg font-bold">{{ $studentRecord->getFullName() ?? '-' }}</p>
                        <p class="text-sm font-medium opacity-60">#{{ $studentRecord->students->lrn ?? '-' }}</p>
                    </div>
                </div>
                {{-- About --}}
                <div class="flex flex-col justify-center items-start space-y-1 pb-4 border-b border-[#1e1e1e]/10">
                    <h2 class="font-bold opacity-90">About</h2>
                    <div class="flex flex-col justify-start items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start w-full gap-2">
                            {{-- icon here --}}
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-phone-flip flex justify-center items-center"></i>
                                <span>Phone:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->contact_number ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start w-full gap-2">
                            {{-- icon here --}}
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-at flex justify-center items-center"></i>
                                <span>Email:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                {{-- personal info --}}
                <div class="space-y-1 pb-4 border-b border-[#1e1e1e]/10">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Personal information</h2>
                        <button id="personal-info-edit-btn"
                            class="edit-btn opacity-0 pointer-events-none scale-90 text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-l flex justify-center items-center"></i>
                                <span>Last Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->last_name }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-f flex justify-center items-center"></i>
                                <span>First Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->first_name }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-m flex justify-center items-center"></i>
                                <span>Middle Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->middle_name ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-square-e flex justify-center items-center"></i>
                                <span>Extension Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->extension_name ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-party-horn flex justify-center items-center"></i>
                                <span>Birthdate:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->birthdate ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-age-restriction-sixteen flex justify-center items-center"></i>
                                <span>Age</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->age ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-land-layer-location flex justify-center items-center"></i>
                                <span>Place of Birth</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->place_of_birth ?? '-' }}</p>
                        </div>
                    </div>

                </div>
                {{-- academic info --}}
                <div class="space-y-1 pb-4 border-b border-[#1e1e1e]/10">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Academic information</h2>
                        <button id="academic-info-edit-btn"
                            class="edit-btn opacity-0 pointer-events-none text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-hastag flex justify-center items-center"></i>
                                <span>LRN:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->students->lrn ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-star flex justify-center items-center"></i>
                                <span>Grade Level:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->grade_level ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-graduation-cap flex justify-center items-center"></i>
                                <span>Program:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->program ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-calendar flex justify-center items-center"></i>
                                <span>Academic Year:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->acad_term_applied ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-hourglass-end flex justify-center items-center"></i>
                                <span>Semester</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->semester_applied ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                <i class="fi fi-rr-tags flex justify-center items-center"></i>
                                <span>Section</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->section ?? '-' }}</p>
                        </div>
                    </div>

                </div>
                {{-- addresss --}}
                <div class="space-y-2">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Address</h2>
                        <button id="address-info-edit-btn"
                            class="edit-btn opacity-0 pointer-events-none text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>House No:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->house_no ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Sitio/Street:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->street ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Barangay:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->barangay ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Municipality/City:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->city ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Country:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->country ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Zip Code:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->zip_code ?? '-' }}</p>
                        </div>
                    </div>

                </div>
                {{-- emergency contact --}}
                <div class="space-y-2">
                    <div class="flex flex-row justify-between items-center">
                        <h2 class="font-bold opacity-90">Emergency contact</h2>
                        <button id="emergency-info-edit-btn"
                            class="edit-btn opacity-0 pointer-events-none text-[14px] font-semibold text-blue-500 hover:text-blue-600 transition duration-150">
                            Edit
                        </button>
                    </div>
                    <div class="flex flex-col justify-center items-start w-full gap-1">
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Guardian Name:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->guardian_name ?? '-' }}</p>
                        </div>
                        <div class="flex flex-row justify-start items-start gap-2 w-full">
                            <div class="flex flex-row justify-center items-center gap-2 opacity-70">
                                {{-- icon here --}}
                                <span>Guardian Contact Number:</span>
                            </div>
                            <p class="font-semibold opacity-85">{{ $studentRecord->guardian_contact_number ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="w-2/3 flex flex-col justify-start items-center pl-6 gap-6">
                <div class="flex flex-col justify-start items-start w-full gap-4">
                    <p class="text-lg font-bold opacity-90">Post-Enrollment Management</p>
                    <div class="flex flex-wrap flex-row justify-start items-center gap-2">
                        <button id="edit-info-btn"
                            class="bg-blue-500 p-3 rounded-lg font-semibold text-white hover:ring hover:ring-blue-200 hover:bg-blue-400 translate duration-150 hover:scale-95 hover:shadow-lg">Edit
                            Student Info</button>
                        <button
                            class="bg-gray-100 p-3 rounded-lg font-semibold ring ring-gray-200 hover:ring-gray-300 hover:bg-gray-200 translate duration-150 hover:scale-95 hover:shadow-lg">Generate
                            COE</button>
                        <button
                            class="bg-gray-100 p-3 rounded-lg font-semibold ring ring-gray-200 hover:ring-gray-300 hover:bg-gray-200 translate duration-150 hover:scale-95 hover:shadow-lg">Generate
                            SIS</button>
                        <button
                            class="bg-gray-100 p-3 rounded-lg font-semibold ring ring-gray-200 hover:ring-gray-300 hover:bg-gray-200 translate duration-150 hover:scale-95 hover:shadow-lg">Download
                            All Documents</button>
                        <button
                            class="bg-red-500 p-3 rounded-lg font-semibold text-white hover:ring hover:ring-red-200 hover:bg-red-400 translate duration-150 hover:scale-95 hover:shadow-lg">Withdraw
                            Enrollment</button>
                    </div>
                </div>
                <div class="flex flex-col justify-start items-start border-t border-[#1e1e1e]/10 w-full gap-4 pt-4">
                    <p class="text-lg font-bold opacity-90">Documents & Requirements</p>
                    <table id="enrolledStudents" class="w-full table-fixed ">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="w-[5%] text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">#</span>
                                </th>
                                <th class="w-1/4 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Document Name</span>
                                </th>
                                <th class="w-1/4 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Status</span>
                                </th>
                                <th class="w-1/4 text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">
                                    <span class="mr-2 font-medium opacity-60 cursor-pointer">Action</span>
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignedDocuments as $doc)
                                <tr>
                                    {{-- Index --}}
                                    <td
                                        class="w-1/2 text-center font-medium py-2 ext-[14px] opacity-80 px-4 border-t border-[#1e1e1e]/10 truncate">
                                        {{ $loop->iteration }}

                                    </td>

                                    {{-- Document Name --}}
                                    <td
                                        class=" text-center font-medium py-2 ext-[14px] opacity-80 px-4 border-t border-[#1e1e1e]/10 truncate">
                                        {{ $doc->documents->type }}
                                    </td>

                                    {{-- Status --}}
                                    <td
                                        class=" text-center font-medium py-2 ext-[14px] opacity-80 px-4 border-t border-[#1e1e1e]/10 truncate">
                                        {{ ucfirst($doc->status) }}
                                    </td>

                                    {{-- File Path (latest submission if available) --}}
                                    <td
                                        class=" text-center font-medium py-1 ext-[14px] opacity-80 px-4 border-t border-[#1e1e1e]/10 truncate">
                                        @if ($doc->latest_submission)
                                            <a href="{{ asset('storage/' . $doc->latest_submission->file_path) }}"
                                                target="_blank"
                                                class="bg-blue-200 px-2 py-2 rounded-lg flex flex-row justify-center items-center gap-2 w-full text-blue-500 font-semibold hover:bg-blue-500 hover:text-white hover:ring hover:ring-blue-200 hover:scale-95 transition duration-150">
                                                <i class="fi fi-rr-eye flex justify-center items-center"></i>
                                                View PDF
                                            </a>
                                        @else
                                            <button
                                                class="bg-orange-200 px-2 py-2 rounded-lg flex flex-row justify-center items-center gap-2 w-full text-orange-500 font-semibold hover:bg-orange-500 hover:text-white hover:ring hover:ring-orange-200 hover:scale-95 transition duration-150">
                                                <i class="fi fi-rr-bell flex justify-center items-center "></i>
                                                <span>Notify student</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Index --}}
                                    <td
                                        class="w-[1%] text-center font-medium py-[8px] text-[14px] opacity-80 px-4 truncate">
                                        -

                                    </td>

                                    {{-- Document Name --}}
                                    <td
                                        class="w-1/4 text-center font-medium py-[8px] text-[14px] opacity-80 px-4 truncate">
                                        -
                                    </td>

                                    {{-- Status --}}
                                    <td
                                        class="w-[1%] text-center font-medium py-[8px] text-[14px] opacity-80 px-4 truncate border border-red-500">
                                        -
                                    </td>

                                    {{-- File Path (latest submission if available) --}}
                                    <td
                                        class="w-[1%] text-center font-medium py-[8px] text-[14px] opacity-80 px-4 truncate">
                                        -
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                    @if (!$assignedDocuments)
                        <button>
                            Click here to assign documents to this student
                        </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener("DOMContentLoaded", function() {

            let editStudentBtn = document.querySelector("#edit-info-btn");
            let editBtns = document.querySelectorAll(".edit-btn");

            editStudentBtn.addEventListener('click', () => {

                editBtns.forEach(element => {

                    element.classList.toggle('opacity-100');
                    element.classList.toggle('pointer-events-none');
                    element.classList.toggle('scale-90');

                    console.log(element)
                });


            })

        });
    </script>
@endpush
