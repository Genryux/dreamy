@extends('layouts.admin')
@section('modal')
    <x-modal modal_id="create-school-fee-modal" modal_name="Create School Fee" close_btn_id="create-school-fee-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-progress-upload flex justify-center items-center '></i>

        </x-slot>

        <form method="POST" action="/school-fees" id="create-school-fee-modal-form" class="p-6">
            @csrf

            <div class="flex flex-col justify-center items-center">

                <label for="name">Name</label>
                <input type="text" name="name" id="name">
                <label for="program_id">Applied to (program)</label>
                <select name="program_id" id="program_id">
                    <option>Program</option>

                    <option value="">All</option>
                </select>
                <label for="grade_level">Applied to (Year Level)</label>
                <select name="grade_level" id="grade_level">
                    <option>Year Level</option>
                    <option value="Grade 11">Grade 11</option>
                    <option value="Grade 12">Grade 12</option>
                    <option value="">All</option>
                </select>
                <label for="amount">Enter Amount</label>
                <input type="number" name="amount" id="amount">
            </div>

        </form>

        <x-slot name="modal_info">
            <i class="fi fi-bs-download flex justify-center items-center"></i>
            <a href="{{ asset('templates/Officially_Enrolled_Students_Import_Template.xlsx') }}" download>Click here to
                download the
                template</a>
        </x-slot>

        <x-slot name="modal_buttons">
            <button id="create-school-fee-modal-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            {{-- This button will acts as the submit button --}}
            <button type="submit" form="create-school-fee-modal-form" name="action" value="verify"
                class="bg-blue-500 text-[14px] px-3 py-2 rounded-md text-[#f8f8f8] font-bold hover:ring hover:ring-blue-200 hover:bg-blue-400 transition duration-150 shadow-sm">
                Import
            </button>
        </x-slot>

    </x-modal>
@endsection

@section('content')
	<x-alert />
	<div class="bg-[#1A3165] absolute inset-0 flex flex-col justify-center items-center">
		<div class="bg-white w-11/12 md:w-2/3 max-h-[95%] rounded-xl shadow-lg overflow-auto">
			<div class="p-8">
				<div class="flex items-start justify-between">
					<div class="flex items-center gap-4">
						<img src="{{ asset('images/Dreamy_logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
						<div class="text-sm">
							<p class="font-bold text-[#1A3165]">Dreamy School Philippines</p>
							<p class="text-gray-600">Lot 23 Block PSD 56216 Sitio Tanag, Brgy. San Isidro, Rodriguez, Rizal, Philippines</p>
							<p class="text-gray-600">0917 630 0777 | ph@dreamyedu.net</p>
						</div>
					</div>
					<div class="text-right">
						<p class="text-xs text-gray-500">Invoice No.</p>
						<p class="text-lg font-bold">{{ $invoice->invoice_number }}</p>
						<p class="mt-2 inline-flex items-center gap-2 px-2 py-1 rounded-full text-xs font-semibold {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : ($invoice->status === 'unpaid' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($invoice->status) }}</p>
					</div>
				</div>

				<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
					<div class="space-y-1">
						<p class="text-xs text-gray-500">Billed To</p>
						<p class="font-semibold">{{ $invoice->student?->full_name ?? '-' }}</p>
						<p class="text-gray-600">Program: {{ $invoice->student?->program ?? '-' }}</p>
						<p class="text-gray-600">Year Level: {{ $invoice->student?->grade_level ?? '-' }}</p>
					</div>
					<div class="space-y-1 md:text-right">
						<p class="text-xs text-gray-500">Created</p>
						<p class="font-medium">{{ $invoice->created_at?->format('M d, Y') }}</p>
					</div>
				</div>

				<div class="mt-8">
					<p class="font-semibold mb-3">Items</p>
					<div class="overflow-hidden rounded-lg border border-gray-200">
						<table class="min-w-full divide-y divide-gray-200 text-sm">
							<thead class="bg-gray-50">
								<tr>
									<th class="px-4 py-2 text-left font-semibold text-gray-700">Description</th>
									<th class="px-4 py-2 text-right font-semibold text-gray-700">Amount (₱)</th>
								</tr>
							</thead>
							<tbody class="divide-y divide-gray-100">
								@forelse ($invoice->items as $item)
									<tr>
										<td class="px-4 py-2">{{ $item->fee?->name ?? 'School Fee' }}</td>
										<td class="px-4 py-2 text-right">{{ number_format($item->amount, 2) }}</td>
									</tr>
								@empty
									<tr>
										<td colspan="2" class="px-4 py-4 text-center text-gray-500">No items found.</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>

				<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
					<div>
						<p class="font-semibold mb-3">Payments</p>
						<div class="rounded-lg border border-gray-200 overflow-hidden">
							<table class="min-w-full divide-y divide-gray-200 text-sm">
								<thead class="bg-gray-50">
									<tr>
										<th class="px-4 py-2 text-left font-semibold text-gray-700">Date</th>
										<th class="px-4 py-2 text-left font-semibold text-gray-700">Reference</th>
										<th class="px-4 py-2 text-left font-semibold text-gray-700">Method</th>
										<th class="px-4 py-2 text-right font-semibold text-gray-700">Amount (₱)</th>
									</tr>
								</thead>
								<tbody class="divide-y divide-gray-100">
									@forelse ($invoice->payments as $payment)
										<tr>
											<td class="px-4 py-2">{{ \Illuminate\Support\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
											<td class="px-4 py-2">{{ $payment->reference_no }}</td>
											<td class="px-4 py-2">{{ ucfirst($payment->method ?? '-') }}</td>
											<td class="px-4 py-2 text-right">{{ number_format($payment->amount, 2) }}</td>
										</tr>
									@empty
										<tr>
											<td colspan="4" class="px-4 py-4 text-center text-gray-500">No payments recorded.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
					<div class="md:ml-auto md:w-3/4">
						<div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
							<div class="flex justify-between py-1 text-sm">
								<span class="text-gray-600">Subtotal</span>
								<span class="font-medium">₱ {{ number_format($invoice->total_amount, 2) }}</span>
							</div>
							<div class="flex justify-between py-1 text-sm">
								<span class="text-gray-600">Paid</span>
								<span class="font-medium">₱ {{ number_format($invoice->paid_amount, 2) }}</span>
							</div>
							<hr class="my-2">
							<div class="flex justify-between py-1 text-base font-bold">
								<span class="text-[#1A3165]">Balance</span>
								<span class="text-[#1A3165]">₱ {{ number_format($invoice->balance, 2) }}</span>
							</div>
						</div>
						<div class="mt-4 flex items-center justify-between">
							<a href="/school-fees/invoices" class="inline-flex items-center gap-2 bg-blue-100 text-blue-600 font-semibold px-3 py-2 rounded-lg hover:bg-blue-500 hover:text-white transition">
								<i class="fi fi-rr-arrow-small-left text-[16px] flex justify-center items-center"></i>
								Back to Invoices
							</a>
							@if (!request()->has('from'))
							<button id="record-payment-open" class="inline-flex items-center gap-2 bg-green-600 text-white font-semibold px-3 py-2 rounded-lg hover:bg-green-500 transition">
								<i class="fi fi-rr-credit-card text-[16px] flex justify-center items-center"></i>
								Record Payment
							</button>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    {{-- Record Payment Modal --}}
    <div id="record-payment-modal" class="fixed inset-0 bg-black/30 flex items-center justify-center opacity-0 pointer-events-none transition">
        <div class="bg-white w-11/12 md:w-[480px] rounded-xl shadow-lg">
            <div class="px-6 py-4 border-b">
                <p class="font-semibold text-lg">Record Payment</p>
            </div>
            <form id="record-payment-form" method="POST" action="{{ route('invoice.payments.store', ['invoice' => $invoice->id]) }}" class="px-6 py-4">
                @csrf
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <label for="amount" class="text-sm font-medium">Amount</label>
                        <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="border border-gray-300 rounded-lg px-3 py-2" required />
                    </div>
                    <div class="flex flex-col">
                        <label for="payment_date" class="text-sm font-medium">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date" class="border border-gray-300 rounded-lg px-3 py-2" value="{{ now()->toDateString() }}" required />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex flex-col">
                            <label for="method" class="text-sm font-medium">Method</label>
                            <input type="text" name="method" id="method" class="border border-gray-300 rounded-lg px-3 py-2" placeholder="Cash, Bank, GCASH" />
                        </div>
                        <div class="flex flex-col">
                            <label for="type" class="text-sm font-medium">Type</label>
                            <input type="text" name="type" id="type" class="border border-gray-300 rounded-lg px-3 py-2" placeholder="Partial / Full" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label for="reference_no" class="text-sm font-medium">Reference No.</label>
                        <input type="text" name="reference_no" id="reference_no" class="border border-gray-300 rounded-lg px-3 py-2" placeholder="Optional" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 mt-6">
                    <button type="button" id="record-payment-cancel" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-3 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-500">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "/js/dropDown.js";
        import {
            clearSearch
        } from "/js/clearSearch.js"
        import { initModal } from "/js/modal.js";
        import {
            showAlert
        } from "/js/alert.js";
        import {
            initCustomDataTable
        } from "/js/initTable.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';




        document.addEventListener("DOMContentLoaded", function() {
            // Record Payment modal
            const openBtn = document.getElementById('record-payment-open');
            const modal = document.getElementById('record-payment-modal');
            const cancelBtn = document.getElementById('record-payment-cancel');
            function openModal() {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
            }
            function closeModal() {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100');
            }
            if (openBtn && modal) {
                openBtn.addEventListener('click', openModal);
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', closeModal);
            }
            initModal('create-school-fee-modal', 'create-school-fee-modal-btn', 'create-school-fee-modal-close-btn',
                'create-school-fee-modal-cancel-btn',
                'modal-container-1');


            let studentSeach = document.getElementById('studentSearch');
            let studentCount = document.querySelector('#studentCount');
            let sectionName = document.querySelector('#section_name');
            let sectionRoom = document.querySelector('#section_room');

            // const fileInput = document.getElementById('fileInput');
            // const fileName = document.getElementById('fileName');

            // fileInput.addEventListener('change', function() {
            //     fileName.textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen';
            // });

            //Overriding default search input
            const customSearch1 = document.getElementById("myCustomSearch");

            let invoiceTable = initCustomDataTable(
                'invoices',
                `/getInvoices`,
                [{
                        data: 'index',
                        width: '3%',
                        searchable: true
                    },
                    {
                        data: 'invoice_number',
                        width: '15%'
                    },
                    {
                        data: 'student',
                        width: '15%'
                    },
                    {
                        data: 'status',
                        width: '15%'
                    },
                    {
                        data: 'total',
                        width: '15%'
                    },
                    {
                        data: 'balance',
                        width: '15%'
                    },
                    {
                        data: 'id',
                        className: 'text-center',
                        width: '15%',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center opacity-100'>

                                <a href="/invoice/${data}" class="group relative inline-flex items-center gap-2 bg-blue-100 text-blue-500 font-semibold px-3 py-1 rounded-xl hover:bg-blue-500 hover:ring hover:ring-blue-200 hover:text-white transition duration-150 ">

                                    <span class="relative w-4 h-4">
                                        <i class="fi fi-rs-eye flex justify-center items-center absolute inset-0 group-hover:opacity-0 transition-opacity text-[16px]"></i>
                                        <i class="fi fi-ss-eye flex justify-center items-center absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity text-[16px]"></i>
                                    </span>

                                    View
                                </a>

                            </div>
                            
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }

                ],

                [
                    [0, 'desc']
                ],
                'myCustomSearch', {
                    grade_filter: selectedGrade,
                    program_filter: selectedProgram,
                    pageLength: selectedPageLength
                }

            )





            dropDown('dropdown_2', 'dropdown_selection2');
            dropDown('dropdown_btn', 'dropdown_selection');

            studentSeach.addEventListener('input', function(e) {
                e.preventDefault();

                let lrn = document.querySelector('#lrn');
                let program = document.querySelector('#program');
                let level = document.querySelector('#level');
                let feesContainer = document.getElementById('fees-container');
                let feesmsg = document.getElementById('fees-msg');
                feesContainer.innerHTML = ''; // clear old fees
                let studentId = document.getElementById('student_id')

                let searchTerm = e.target.value.trim();
                if (searchTerm.length < 2) {
                    studentSeach.classList.remove('ring', 'ring-red-500', 'ring-green-500');

                    lrn.innerHTML = '-';
                    program.innerHTML = '-';
                    level.innerHTML = '-';

                    feesmsg.innerHTML =
                        'Applicable fees will show up, once the student is found';
                    return; // wait until user types at least 2 chars
                }

                fetch('/getStudent', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            search: searchTerm
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data)
                            if (data.data === null) {
                                studentSeach.classList.remove('ring-green-500');
                                studentSeach.classList.add('ring', 'ring-red-500');
                                lrn.innerHTML = '-';
                                program.innerHTML = '-';
                                level.innerHTML = '-';

                                feesmsg.innerHTML =
                                    'Applicable fees will show up, once the student is found';

                            } else if (data.data !== null && data.hasInvoice === false) {

                                lrn.innerHTML = data.data.lrn || '-';
                                program.innerHTML = data.data.program || '-';
                                level.innerHTML = data.data.grade_level || '-';

                                studentSeach.classList.remove('ring-red-500');
                                studentSeach.classList.add('ring', 'ring-green-500');

                                studentId.value = data.data.id;

                                // Render school fees checkboxe
                                feesmsg.innerHTML = '';
                                if (data.fees && data.fees.length > 0) {
                                    data.fees.forEach(fee => {
                                        let div = document.createElement('div');
                                        div.classList.add('flex', 'items-center', 'gap-2',
                                            'mb-2');

                                        let checkbox = document.createElement('input');
                                        checkbox.type = 'checkbox';
                                        checkbox.name = 'school_fees[]';
                                        checkbox.value = fee.id;

                                        let hidden = document.createElement('input');
                                        hidden.type = 'hidden';
                                        hidden.name =
                                            `school_fee_amounts[${fee.id}]`; // key by fee id
                                        hidden.value = fee.amount;

                                        let label = document.createElement('label');
                                        label.textContent = `${fee.name} - ₱${fee.amount}`;

                                        div.appendChild(checkbox);
                                        div.appendChild(label);
                                        div.appendChild(hidden);
                                        feesContainer.appendChild(div);
                                    });
                                } else {
                                    feesmsg.innerHTML = 'No applicable fees found';
                                }

                            } else {
                                feesmsg.innerHTML =
                                    'Student has already have an invoice assigned.';
                            }
                        } else {
                            studentSeach.classList.remove('ring-green-500');
                            studentSeach.classList.add('ring', 'ring-red-500');
                            lrn.innerHTML = '-';
                            program.innerHTML = '-';
                            level.innerHTML = '-';

                            feesmsg.innerHTML =
                                'Applicable fees will show up, once the student is found';
                        }
                    })
                    .catch(err => {
                        studentSeach.classList.remove('ring-green-500');
                        studentSeach.classList.add('ring', 'ring-red-500');

                        lrn.innerHTML = '-';
                        program.innerHTML = '-';
                        level.innerHTML = '-';
                        feesmsg.innerHTML =
                            'Applicable fees will show up, once the student is found';
                        console.error(err);
                    });
            });


            // document.getElementById('add-student-form').addEventListener('submit', function(e) {
            //     e.preventDefault();

            //     closeModal();

            //     let form = e.target;
            //     let formData = new FormData(form);

            //     // Show loader
            //     showLoader("Adding...");

            //     fetch(`/assign-section/${sectionId}`, {
            //             method: "POST",
            //             body: formData,
            //             headers: {
            //                 "X-CSRF-TOKEN": "{{ csrf_token() }}"
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             hideLoader();

            //             console.log(data)

            //             if (data.success) {

            //                 studentCount.innerHTML = data.count;
            //                 closeModal('add-student-modal', 'modal-container-2');
            //                 showAlert('success', data.success);
            //                 table1.draw();

            //             } else if (data.error) {

            //                 closeModal('add-student-modal', 'modal-container-2');
            //                 showAlert('error', data.error);
            //             }
            //         })
            //         .catch(err => {
            //             hideLoader();

            //             closeModal('add-student-modal', 'modal-container-2');
            //             showAlert('error', 'Something went wrong');
            //         });
            // });


            function closeModal(modalId, modalContainerId) {

                let modal = document.querySelector(`#${modalId}`)
                let body = document.querySelector(`#${modalContainerId}`);

                if (modal && body) {
                    modal.classList.remove('opacity-100', 'scale-100');
                    modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                    body.classList.remove('opacity-100');
                    body.classList.add('opacity-0', 'pointer-events-none');
                }

            }

            function openAlert() {
                const alertContainer = document.querySelector('#alert-container');
                alertContainer.classList.toggle('opacity-100');
                alertContainer.classList.toggle('scale-95');
                alertContainer.classList.toggle('pointer-events-none');
                alertContainer.classList.toggle('translate-y-5');
            }




        });
    </script>
@endpush
