@extends('layouts.admin')

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="flex flex-row justify-between items-center mb-2 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180 border border-gray-300 bg-gray-100 p-2 rounded-lg mr-1">
                <a href="/teacher/dashboard" class="block transition-colors hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </li>
            <li>
                <a href="/teacher/dashboard" class="block transition-colors hover:text-gray-900"> Dashboard </a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span class="block text-gray-900">{{ $sectionSubject->subject->name ?? 'Subject' }}</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">{{ $sectionSubject->subject->name ?? 'Subject' }}</h1>
            <p class="text-[14px] text-gray-600 mt-1">
                {{ $sectionSubject->section->name ?? 'Section' }} • {{ $sectionSubject->section->program->name ?? 'Program' }}
            </p>
        </div>
    </div>
@endsection

@section('stat')
    <div class="flex justify-center items-center">
        <div class="flex flex-col justify-center items-center flex-grow px-10 pb-10 pt-2 bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl shadow-[#199BCF]/30 shadow-xl gap-2 text-white">
            <div class="flex flex-row items-center justify-between w-full gap-4 py-2 rounded-lg">
                <div class="flex flex-col items-start justify-end gap-2 pt-4">
                    <h1 class="text-[40px] font-black">{{ $sectionSubject->subject->name ?? 'Subject' }}</h1>
                    <p class="text-[16px] text-white/60">{{ $sectionSubject->section->name ?? 'Section' }} • {{ $sectionSubject->section->program->name ?? 'Program' }}</p>
                </div>
                <div class="flex flex-col items-end justify-center">
                    <p class="text-[50px] font-bold">{{ $studentCount }}</p>
                    <div class="flex flex-row justify-center items-center opacity-70 gap-2 text-[14px]">
                        <p class="text-[16px]">Enrolled Students</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-row justify-center items-center w-full gap-4">
                <div class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-school flex flex-row justify-center items-center"></i>
                        <p class="text-[14px]">Section</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $sectionSubject->section->name ?? 'N/A' }}</p>
                    <p class="text-[12px] truncate text-gray-300">{{ $sectionSubject->section->program->code ?? 'Program' }}</p>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-clock flex justify-center items-center"></i>
                        <p class="text-[14px]">Schedule</p>
                    </div>
                    <p class="font-bold text-[18px] text-center">
                        @php
                            $days = $sectionSubject->days_of_week;
                            $daysText = is_array($days) ? implode(', ', $days) : ($days ?? 'Days TBA');
                        @endphp
                        {{ $daysText }}
                    </p>
                    <p class="text-[12px] truncate text-gray-300">
                        @if($sectionSubject->start_time && $sectionSubject->end_time)
                            {{ \Carbon\Carbon::parse($sectionSubject->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($sectionSubject->end_time)->format('g:i A') }}
                        @else
                            Time TBA
                        @endif
                    </p>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-home flex justify-center items-center"></i>
                        <p class="text-[14px]">Room</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $sectionSubject->room ?? ($sectionSubject->section->room ?? 'Not set') }}</p>
                    <p class="text-[12px] truncate text-gray-300">Classroom location</p>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center border border-white/20 bg-[#E3ECFF]/30 gap-2 p-8 py-6 rounded-lg">
                    <div class="opacity-80 flex flex-row justify-center items-center gap-2">
                        <i class="fi fi-rr-user-tie flex justify-center items-center"></i>
                        <p class="text-[14px]">Teacher</p>
                    </div>
                    <p class="font-bold text-[24px]">{{ $sectionSubject->teacher->user->first_name ?? $teacher->first_name }} {{ $sectionSubject->teacher->user->last_name ?? $teacher->last_name }}</p>
                    <p class="text-[12px] truncate text-gray-300">Subject teacher</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div id="students-layout" class="flex flex-col lg:flex-row gap-4 items-start">
        <div id="students-panel" class="flex-1 space-y-4 w-full lg:w-full">
            <div class="bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6">
                <div class="flex flex-row justify-between items-center mb-4">
                    <div>
                        <h2 class="text-[20px] font-bold text-gray-900">Students</h2>
                        <p class="text-[14px] text-gray-600">Students enrolled in this subject</p>
                    </div>
                </div>

                <div class="flex flex-row justify-between items-center mb-4 gap-4">
                    <label for="myCustomSearch"
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 rounded-lg py-2 px-3 gap-2 flex-1 hover:ring hover:ring-blue-200 focus-within:ring focus-within:ring-blue-100 focus-within:border-blue-500 transition duration-150 shadow-sm">
                        <i class="fi fi-rs-search flex justify-center items-center text-[#1e1e1e]/60 text-[16px]"></i>
                        <input type="search" id="myCustomSearch" class="my-custom-search bg-transparent outline-none text-[14px] w-full peer" placeholder="Search students...">
                        <button id="clear-btn" class="clear-btn flex justify-center items-center peer-placeholder-shown:hidden peer-not-placeholder-shown:block">
                            <i class="fi fi-rs-cross-small text-[18px] flex justify-center items-center"></i>
                        </button>
                    </label>
                    <div class="flex flex-row gap-2">
                        <div class="flex flex-row justify-between items-center rounded-lg border border-[#1e1e1e]/10 bg-gray-100 px-3 py-2 gap-2 hover:bg-gray-200 hover:border-[#1e1e1e]/15 transition-all ease-in-out duration-150 shadow-sm">
                            <select id="page-length-selection" class="appearance-none bg-transparent text-[14px] font-medium text-gray-700 h-full w-full cursor-pointer">
                                <option selected disabled>Entries</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <i class="fi fi-rr-caret-down text-gray-500 flex justify-center items-center"></i>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden rounded-lg border border-[#1e1e1e]/10">
                    <table id="subject-students" class="w-full table-fixed">
                        <thead class="text-[14px]">
                            <tr>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">#</th>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">LRN</th>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">Name</th>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">Evaluation</th>
                                <th class="text-start bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">Remedial</th>
                                <th class="text-center bg-[#E3ECFF]/50 border-b border-[#1e1e1e]/10 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Student Drawer -->
        <div id="student-drawer" class="hidden w-full lg:w-[32%] bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 transition-transform duration-300 lg:sticky lg:top-28 max-h-[78vh] overflow-y-auto">
            <div class="flex flex-row justify-between items-start mb-4">
                <div>
                    <h3 class="text-[18px] font-bold" id="drawer-student-name">Select a student</h3>
                    <p class="text-[13px] text-gray-500" id="drawer-student-meta">Open a student to view details</p>
                </div>
                <button id="drawer-close" class="text-gray-500 hover:text-gray-800">
                    <i class="fi fi-rr-cross-small text-xl"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-800 mb-2">Student personal info</p>
                    <div class="space-y-1 text-sm text-gray-700" id="drawer-student-personal">
                        <p>Name: —</p>
                        <p>Student ID: —</p>
                    </div>
                </div>

                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-800 mb-2">Student academic info</p>
                    <div class="space-y-3 text-sm text-gray-700" id="drawer-student-academic">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Section</span>
                            <span id="drawer-student-section">—</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Program / Year</span>
                            <span id="drawer-student-program">—</span>
                        </div>
                    </div>
                </div>

                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-800 mb-3">Evaluation</p>
                    <div id="eval-btn-container">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" class="eval-btn px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:border-red-300 hover:text-red-700" data-status="failed">Failed</button>
                            <button type="button" class="eval-btn px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 hover:border-green-300 hover:text-green-700" data-status="passed">Passed</button>
                        </div>
                        <span id="evaluation-done-msg" class="text-green-600 text-xs font-semibold hidden mt-2"></span>
                        <p class="text-xs text-gray-500 mt-2">Select status then confirm to update.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <x-modal modal_id="evaluation-confirm-modal" modal_name="Evaluation confirmation" close_btn_id="evaluation-confirm-close" modal_container_id="modal-container-evaluation">
        <div class="p-6 space-y-3">
            <p class="text-base font-semibold text-gray-900">Evaluation confirmation</p>
            <p class="text-sm text-gray-600" id="evaluation-confirm-text">This will set the student's evaluation status.</p>
        </div>
        <x-slot name="modal_buttons">
            <button id="evaluation-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="evaluation-confirm-btn"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Confirm
            </button>
        </x-slot>
    </x-modal>
@endsection

@push('scripts')
    <script type="module">
        import { initCustomDataTable } from "/js/initTable.js";
        import { clearSearch } from "/js/clearSearch.js";
        import { showAlert } from "/js/alert.js";

        let table;
        const subjectId = @json($sectionSubject->id);
        const sectionLabel = @json(($sectionSubject->section->name ?? 'Section'));
        const programLabel = @json(($sectionSubject->section->program->code ?? 'Program') . ' • ' . ($sectionSubject->section->year_level ?? ''));

        document.addEventListener('DOMContentLoaded', () => {
            table = initCustomDataTable(
                'subject-students',
                `/teacher/subject/${subjectId}/students`,
                [
                    { data: 'index', width: '8%' },
                    { data: 'lrn', width: '18%', render: (d) => d || '—' },
                    { data: 'name', width: '32%' },
                    { data: 'evaluation_status', width: '18%', render: (d) => d ? (d.charAt(0).toUpperCase() + d.slice(1)) : 'Pending' },
                    { data: 'remedial_status', width: '14%', render: (d) => d ? (d.charAt(0).toUpperCase() + d.slice(1)) : 'Pending' },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '10%',
                        render: function(data, type, row) {
                            return `
                                <button class="view-student px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition" 
                                    data-student-id="${row.id}" data-student-subject-id="${row.student_subject_id}" data-name="${row.name}" data-eval="${row.evaluation_status || ''}" data-remedial="${row.remedial_status || ''}" data-deadline="${row.remedial_deadline || ''}" data-lrn="${row.lrn || ''}">
                                    <i class="fi fi-rr-eye text-xs"></i>
                                </button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [ [0, 'asc'] ],
                'myCustomSearch'
            );

            clearSearch('clear-btn', 'myCustomSearch', table);

            const pageLengthSelection = document.getElementById('page-length-selection');
            pageLengthSelection.addEventListener('change', (e) => {
                const len = parseInt(e.target.value, 10);
                table.page.len(len).draw();
            });

            // Drawer logic
            const drawer = document.getElementById('student-drawer');
            const layout = document.getElementById('students-layout');
            const panel = document.getElementById('students-panel');
            const drawerClose = document.getElementById('drawer-close');
            const nameEl = document.getElementById('drawer-student-name');
            const metaEl = document.getElementById('drawer-student-meta');
            const personalEl = document.getElementById('drawer-student-personal');
            const sectionEl = document.getElementById('drawer-student-section');
            const programEl = document.getElementById('drawer-student-program');
            const evalButtons = document.querySelectorAll('.eval-btn');
            const modalContainer = document.getElementById('modal-container-evaluation');
            const modal = document.getElementById('evaluation-confirm-modal');
            const modalText = document.getElementById('evaluation-confirm-text');
            const modalClose = document.getElementById('evaluation-confirm-close');
            const modalCancel = document.getElementById('evaluation-cancel-btn');
            const modalConfirm = document.getElementById('evaluation-confirm-btn');

            let activeStudent = null;
            let pendingEvaluation = null;

            function setEvalButtonState(status) {
                evalButtons.forEach((btn) => {
                    const isActive = btn.dataset.status === status;
                    btn.classList.toggle('border-blue-400', isActive);
                    btn.classList.toggle('text-blue-700', isActive);
                    btn.classList.toggle('bg-blue-50', isActive);
                });
            }

            function openDrawer(row) {
                activeStudent = row;
                nameEl.textContent = row.name;
                metaEl.textContent = `LRN: ${row.lrn || '—'}`;
                personalEl.innerHTML = `<p>Name: ${row.name}</p><p>LRN: ${row.lrn || '—'}</p>`;
                sectionEl.textContent = sectionLabel;
                programEl.textContent = programLabel;
                pendingEvaluation = row.evaluation_status || '';
                setEvalButtonState(pendingEvaluation);
                drawer.classList.remove('hidden');
                panel.classList.remove('lg:w-full');
                panel.classList.add('lg:w-[68%]');
                // Show evaluation buttons only if status is null or pending
                const isEvaluated = !!row.evaluation_status && row.evaluation_status !== 'pending';
                evalButtons.forEach((btn) => {
                    btn.classList.toggle('hidden', isEvaluated);
                });
                // Show evaluation done message if already evaluated
                const evalMsg = document.getElementById('evaluation-done-msg');
                if (evalMsg) {
                    evalMsg.classList.toggle('hidden', !isEvaluated);
                    if (isEvaluated) {
                        evalMsg.textContent = 'Evaluation complete. Status cannot be changed.';
                    } else {
                        evalMsg.textContent = '';
                    }
                }
            }

            function closeDrawer() {
                drawer.classList.add('hidden');
                panel.classList.remove('lg:w-[68%]');
                panel.classList.add('lg:w-full');
                activeStudent = null;
                pendingEvaluation = null;
                setEvalButtonState('');
            }

            function toggleModal(show) {
                if (show) {
                    modalContainer.classList.remove('opacity-0', 'pointer-events-none');
                    modalContainer.classList.add('opacity-100');
                    modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                    modal.classList.add('opacity-100', 'scale-100');
                } else {
                    modalContainer.classList.add('opacity-0', 'pointer-events-none');
                    modalContainer.classList.remove('opacity-100');
                    modal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    modal.classList.remove('opacity-100', 'scale-100');
                }
            }

            document.body.addEventListener('click', (e) => {
                if (e.target.closest('.view-student')) {
                    const btn = e.target.closest('.view-student');
                    const row = {
                        id: btn.dataset.studentId,
                        student_subject_id: btn.dataset.studentSubjectId,
                        name: btn.dataset.name,
                        lrn: btn.dataset.lrn,
                        evaluation_status: btn.dataset.eval,
                        remedial_status: btn.dataset.remedial,
                        remedial_deadline: btn.dataset.deadline,
                    };
                    openDrawer(row);
                }
            });

            evalButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (!activeStudent) return;
                    pendingEvaluation = btn.dataset.status;
                    setEvalButtonState(pendingEvaluation);
                    modalText.textContent = `This will set the student's evaluation status as ${pendingEvaluation}.`;
                    toggleModal(true);
                });
            });

            const closeModal = () => toggleModal(false);
            modalClose.addEventListener('click', closeModal);
            modalCancel.addEventListener('click', closeModal);
            modalContainer.addEventListener('click', (e) => {
                if (e.target === modalContainer) closeModal();
            });

            modalConfirm.addEventListener('click', async () => {
                if (!activeStudent) return;
                const payload = {
                    evaluation_status: pendingEvaluation || null,
                    remedial_status: null,
                    remedial_deadline: null,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                };
                try {
                    const res = await fetch(`/teacher/subject/${subjectId}/student/${activeStudent.id}/evaluation`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': payload._token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'Failed to update');
                    // Hide evaluation buttons after update
                    evalButtons.forEach((btn) => btn.classList.add('hidden'));
                    // Show evaluation done message
                    const evalMsg = document.getElementById('evaluation-done-msg');
                    if (evalMsg) {
                        evalMsg.classList.remove('hidden');
                        evalMsg.textContent = 'Evaluation complete. Status cannot be changed.';
                    }
                    showAlert('success', 'Student evaluation updated.');
                    table.ajax.reload(null, false);
                    closeModal();
                } catch (err) {
                    showAlert('error', err.message || 'Unable to update');
                }
            });

            drawerClose.addEventListener('click', closeDrawer);
        });
    </script>
@endpush
