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
                <span class="block text-gray-900">Teaching History</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[24px] font-black text-gray-900">Teaching History</h1>
            <p class="text-[14px] text-gray-600 mt-1">Review past classes, student evaluations, and remedial outcomes.</p>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />
    <div class="flex flex-col lg:flex-row gap-4 w-full" id="history-layout">
        <div class="flex-1 bg-white border border-[#1e1e1e]/10 rounded-xl p-4 shadow-sm" id="history-panel">
            <div class="flex flex-row justify-between items-center mb-3">
                <h2 class="text-[18px] font-bold text-gray-900">Subjects & Students</h2>
                <button id="refresh-history"
                    class="flex items-center gap-2 text-sm text-[#1A3165] hover:text-[#199BCF] font-semibold">
                    <i class="fi fi-rr-rotate-right"></i>
                    Refresh
                </button>
            </div>
            <div id="history-list" class="space-y-3">
                <div class="flex flex-row items-center gap-2 text-sm text-gray-600">
                    <span class="animate-spin size-4 border-2 border-gray-300 border-t-transparent rounded-full"></span>
                    Loading teaching history...
                </div>
            </div>
        </div>

        <div id="history-drawer" class="hidden w-full lg:w-[34%] bg-white rounded-xl shadow-md border border-[#1e1e1e]/10 p-6 lg:sticky lg:top-28 h-fit">
            <div class="flex flex-row justify-between items-start mb-4">
                <div>
                    <h3 class="text-[18px] font-bold" id="drawer-student-name">Select a record</h3>
                    <p class="text-[13px] text-gray-500" id="drawer-student-meta">Choose a student to view details</p>
                </div>
                <button id="drawer-close" class="text-gray-500 hover:text-gray-800">
                    <i class="fi fi-rr-cross-small text-xl"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-800 mb-2">Student</p>
                    <div class="space-y-1 text-sm text-gray-700" id="drawer-student-personal">
                        <p>Name: —</p>
                        <p>LRN: —</p>
                    </div>
                </div>

                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50">
                    <p class="text-sm font-semibold text-gray-800 mb-2">Subject & Term</p>
                    <div class="space-y-2 text-sm text-gray-700" id="drawer-student-academic">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Subject</span>
                            <span id="drawer-subject">—</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Section</span>
                            <span id="drawer-section">—</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Academic Term</span>
                            <span id="drawer-term">—</span>
                        </div>
                    </div>
                </div>

                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50 space-y-2">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold text-gray-800">Evaluation</p>
                        <span id="drawer-eval-chip" class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-700">Pending</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold text-gray-800">Remedial</p>
                        <span id="drawer-remedial-chip" class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-700">Pending</span>
                    </div>
                    <div class="text-xs text-gray-600" id="drawer-remedial-deadline">Deadline: —</div>
                    <div class="text-xs font-semibold text-green-600 hidden" id="drawer-finalized-flag">Remedial finalized</div>
                </div>

                <div class="border border-[#1e1e1e]/10 rounded-lg p-4 bg-white space-y-2" id="remedial-actions-card">
                    <p class="text-sm font-semibold text-gray-800">Remedial Evaluation</p>
                    <p class="text-xs text-gray-500" id="remedial-hint">Available only for failed evaluations that are not finalized.</p>
                    <div class="flex gap-2" id="remedial-btns">
                        <button type="button" class="remedial-btn px-3 py-2 rounded-lg border border-green-200 text-sm font-medium text-green-700 hover:bg-green-50" data-status="cleared">Mark Remedial Cleared</button>
                        <button type="button" class="remedial-btn px-3 py-2 rounded-lg border border-red-200 text-sm font-medium text-red-700 hover:bg-red-50" data-status="failed">Mark Remedial Failed</button>
                    </div>
                    <div class="text-xs text-gray-600 hidden" id="remedial-locked-msg">Remedial update not available for this record.</div>
                    <div class="text-xs font-semibold text-green-600 hidden" id="remedial-finalized-msg">Evaluation is finalized and could no longer be changed.</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <x-modal modal_id="remedial-confirm-modal" modal_name="Remedial Status Confirmation" close_btn_id="remedial-confirm-close" modal_container_id="modal-container-remedial">
        <div class="p-6 space-y-3">
            <p class="text-base font-semibold text-gray-900">Confirm Remedial Status</p>
            <p class="text-sm text-gray-600" id="remedial-confirm-text">This action will finalize the remedial status and cannot be undone.</p>
        </div>
        <x-slot name="modal_buttons">
            <button id="remedial-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="remedial-confirm-btn"
                class="self-end flex flex-row justify-center items-center bg-[#199BCF] py-2 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Confirm
            </button>
        </x-slot>
    </x-modal>
@endsection

@push('scripts')
    <script type="module">
        import { showAlert } from "/js/alert.js";

        const historyList = document.getElementById('history-list');
        const refreshBtn = document.getElementById('refresh-history');
        const drawer = document.getElementById('history-drawer');
        const drawerClose = document.getElementById('drawer-close');
        const studentName = document.getElementById('drawer-student-name');
        const studentMeta = document.getElementById('drawer-student-meta');
        const studentPersonal = document.getElementById('drawer-student-personal');
        const subjectEl = document.getElementById('drawer-subject');
        const sectionEl = document.getElementById('drawer-section');
        const termEl = document.getElementById('drawer-term');
        const evalChip = document.getElementById('drawer-eval-chip');
        const remedialChip = document.getElementById('drawer-remedial-chip');
        const remedialDeadline = document.getElementById('drawer-remedial-deadline');
        const finalizedFlag = document.getElementById('drawer-finalized-flag');
        const remedialBtns = document.getElementById('remedial-btns');
        const remedialLocked = document.getElementById('remedial-locked-msg');
        const remedialHint = document.getElementById('remedial-hint');
        const remedialFinalized = document.getElementById('remedial-finalized-msg');
        const remedialActionsCard = document.getElementById('remedial-actions-card');
        const confirmModal = document.getElementById('remedial-confirm-modal');
        const confirmModalContainer = document.getElementById('modal-container-remedial');
        const confirmText = document.getElementById('remedial-confirm-text');
        const confirmBtn = document.getElementById('remedial-confirm-btn');
        const cancelBtn = document.getElementById('remedial-cancel-btn');
        const closeBtn = document.getElementById('remedial-confirm-close');
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let historyData = [];
        let activeRecord = null;

        const statusLabel = (val, fallback = 'Pending') => {
            if (!val) return fallback;
            return val.charAt(0).toUpperCase() + val.slice(1);
        };

        const chipColor = (status) => {
            switch (status) {
                case 'passed':
                case 'cleared':
                    return ['bg-green-100 text-green-700'];
                case 'failed':
                    return ['bg-red-100 text-red-700'];
                default:
                    return ['bg-gray-100 text-gray-700'];
            }
        };

        const setChip = (el, status, fallback = 'Pending') => {
            el.textContent = statusLabel(status, fallback);
            el.className = `text-xs font-semibold px-2 py-1 rounded-full ${chipColor(status).join(' ')}`;
        };

        const renderHistory = () => {
            if (!historyData.length) {
                historyList.innerHTML = '<div class="text-sm text-gray-600">No teaching history yet.</div>';
                return;
            }

            historyList.innerHTML = '';
            historyData.forEach((group, idx) => {
                const card = document.createElement('div');
                card.className = 'border border-[#1e1e1e]/10 rounded-lg p-4 bg-gray-50 shadow-sm';
                const studentsRows = group.students.map((s, i) => {
                    const evalText = statusLabel(s.evaluation_status);
                    const remedialText = statusLabel(s.remedial_status);
                    return `
                        <tr class="border-t border-gray-200" data-row-id="${s.student_subject_id}">
                            <td class="px-2 py-2 text-xs text-gray-500">${i + 1}</td>
                            <td class="px-2 py-2 text-sm font-medium text-gray-900">${s.name}</td>
                            <td class="px-2 py-2 text-sm text-gray-700">${s.lrn || '—'}</td>
                            <td class="px-2 py-2 text-sm">${evalText}</td>
                            <td class="px-2 py-2 text-sm">${remedialText}</td>
                            <td class="px-2 py-2 text-right">
                                <button class="view-history px-2 py-1 text-xs font-semibold text-[#1A3165] hover:text-[#199BCF]"
                                    data-student-subject-id="${s.student_subject_id}"
                                    data-name="${s.name}"
                                    data-lrn="${s.lrn || ''}"
                                    data-eval="${s.evaluation_status || ''}"
                                    data-remedial="${s.remedial_status || ''}"
                                    data-deadline="${s.remedial_deadline || ''}"
                                    data-finalized="${s.is_remedial_status_finalized ? '1' : '0'}"
                                    data-subject="${group.subject_name || ''}"
                                    data-section="${group.section_name || ''}"
                                    data-term="${group.academic_term || ''}">
                                    View
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');

                card.innerHTML = `
                    <div class="flex flex-row justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500">${group.academic_term || 'Term not set'}</p>
                            <h3 class="text-lg font-semibold text-gray-900">${group.subject_name}</h3>
                            <p class="text-sm text-gray-600">${group.section_name || 'No section'}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 bg-white px-3 py-1 rounded-full border border-gray-200">${group.students.length} students</span>
                    </div>
                    <div class="mt-3 border border-gray-200 rounded-lg overflow-hidden bg-white">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100 text-xs text-gray-600 uppercase">
                                <tr>
                                    <th class="px-2 py-2 w-10">#</th>
                                    <th class="px-2 py-2">Name</th>
                                    <th class="px-2 py-2">LRN</th>
                                    <th class="px-2 py-2">Evaluation</th>
                                    <th class="px-2 py-2">Remedial</th>
                                    <th class="px-2 py-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>${studentsRows}</tbody>
                        </table>
                    </div>
                `;

                historyList.appendChild(card);
            });
        };

        const fetchHistory = async () => {
            historyList.innerHTML = '<div class="flex flex-row items-center gap-2 text-sm text-gray-600"><span class="animate-spin size-4 border-2 border-gray-300 border-t-transparent rounded-full"></span>Loading teaching history...</div>';
            try {
                const res = await fetch('/teacher/history/data');
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Failed to load history');
                historyData = data.data || [];
                renderHistory();
            } catch (err) {
                historyList.innerHTML = '<div class="text-sm text-red-600">Unable to load teaching history.</div>';
                showAlert('error', err.message || 'Unable to load teaching history');
            }
        };

        const closeDrawer = () => {
            activeRecord = null;
            drawer.classList.add('hidden');
        };

        const populateDrawer = (record) => {
            studentName.textContent = record.student_name;
            studentMeta.textContent = `LRN: ${record.lrn || '—'}`;
            studentPersonal.innerHTML = `<p>Name: ${record.student_name}</p><p>LRN: ${record.lrn || '—'}</p>`;
            subjectEl.textContent = record.subject_name || '—';
            sectionEl.textContent = record.section_name || '—';
            termEl.textContent = record.academic_term || '—';

            setChip(evalChip, record.evaluation_status);
            setChip(remedialChip, record.remedial_status);
            const deadlineLabel = record.remedial_deadline_display || record.remedial_deadline || '—';
            remedialDeadline.textContent = `Deadline: ${deadlineLabel}`;

            const showButtons = record.evaluation_status === 'failed' && !record.is_remedial_status_finalized;
            remedialBtns.classList.toggle('hidden', !showButtons);
            remedialHint.classList.toggle('hidden', !showButtons);
            remedialLocked.classList.toggle('hidden', showButtons);
            remedialFinalized.classList.toggle('hidden', !record.is_remedial_status_finalized);
            finalizedFlag.classList.toggle('hidden', !record.is_remedial_status_finalized);

            drawer.classList.remove('hidden');
        };

        const fetchStudentSubject = async (id) => {
            try {
                const res = await fetch(`/teacher/student-subject/${id}`);
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Failed to load student record');
                activeRecord = data.data;
                populateDrawer(activeRecord);
            } catch (err) {
                showAlert('error', err.message || 'Unable to load student record');
            }
        };

        const toggleModal = (show) => {
            if (show) {
                confirmModal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                confirmModal.classList.add('opacity-100', 'scale-100');
                confirmModalContainer.classList.remove('opacity-0', 'pointer-events-none');
                confirmModalContainer.classList.add('opacity-100');
            } else {
                confirmModal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                confirmModal.classList.remove('opacity-100', 'scale-100');
                confirmModalContainer.classList.add('opacity-0', 'pointer-events-none');
                confirmModalContainer.classList.remove('opacity-100');
            }
        };

        let pendingRemedialStatus = null;

        const updateRemedial = async (status) => {
            if (!activeRecord) return;
            try {
                const res = await fetch(`/teacher/student-subject/${activeRecord.student_subject_id}/remedial`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });
                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Failed to update remedial status');

                activeRecord = data.data;
                populateDrawer(activeRecord);
                syncRow(activeRecord);
                showAlert('success', 'Remedial status updated.');
                toggleModal(false);
            } catch (err) {
                showAlert('error', err.message || 'Unable to update remedial status');
                toggleModal(false);
            }
        };

        const syncRow = (record) => {
            // Update local cache
            historyData.forEach((group) => {
                const match = group.students.find((s) => s.student_subject_id === record.student_subject_id);
                if (match) {
                    match.remedial_status = record.remedial_status;
                    match.evaluation_status = record.evaluation_status;
                    match.remedial_deadline = record.remedial_deadline;
                    match.is_remedial_status_finalized = record.is_remedial_status_finalized;
                }
            });
            renderHistory();
        };

        historyList.addEventListener('click', (e) => {
            const btn = e.target.closest('.view-history');
            if (!btn) return;
            const studentSubjectId = btn.dataset.studentSubjectId;
            fetchStudentSubject(studentSubjectId);
        });

        remedialBtns.addEventListener('click', (e) => {
            const btn = e.target.closest('.remedial-btn');
            if (!btn || !activeRecord) return;
            pendingRemedialStatus = btn.dataset.status;
            const statusText = pendingRemedialStatus === 'cleared' ? 'CLEARED' : 'FAILED';
            confirmText.textContent = `This will mark the remedial status as ${statusText} and finalize the evaluation. This action cannot be undone.`;
            toggleModal(true);
        });

        confirmBtn.addEventListener('click', () => {
            if (pendingRemedialStatus) {
                updateRemedial(pendingRemedialStatus);
                pendingRemedialStatus = null;
            }
        });

        const closeModal = () => {
            toggleModal(false);
            pendingRemedialStatus = null;
        };

        cancelBtn.addEventListener('click', closeModal);
        closeBtn.addEventListener('click', closeModal);
        confirmModalContainer.addEventListener('click', (e) => {
            if (e.target === confirmModalContainer) closeModal();
        });

        drawerClose.addEventListener('click', closeDrawer);
        refreshBtn.addEventListener('click', fetchHistory);

        fetchHistory();
    </script>
@endpush
