@extends('layouts.admin')
@section('modal')
    <x-modal modal_id="record-payment-modal" modal_name="Record Payment" close_btn_id="record-payment-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-credit-card flex justify-center items-center '></i>
        </x-slot>

        <form method="POST" action="{{ route('invoice.payments.store', ['invoice' => $invoice->id]) }}" id="record-payment-modal-form" class="p-6">
                @csrf
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col">
                        <label for="amount" class="text-sm font-medium">Amount</label>
                    <input type="number" 
                           step="0.01" 
                           min="{{ $invoice->payment_mode === 'full' && $invoice->paid_amount == 0 ? $invoice->total_amount : '0.01' }}" 
                           max="{{ $invoice->balance }}" 
                           name="amount" 
                           id="amount" 
                           class="border border-gray-300 rounded-lg px-3 py-2" 
                           placeholder="Enter payment amount"
                           required />
                    <p class="text-xs text-gray-500 mt-1">
                        @if($invoice->payment_mode === 'full')
                            @if($invoice->paid_amount == 0)
                                Minimum amount: ₱{{ number_format($invoice->total_amount, 2) }} (full payment required)
                            @else
                                Maximum amount: ₱{{ number_format($invoice->balance, 2) }} (remaining balance)
                            @endif
                        @else
                            Enter the payment amount
                        @endif
                    </p>
                </div>
                
                @if($invoice->has_payment_plan && $paymentPlanSummary)
                <div class="flex flex-col">
                    <label for="payment_schedule_id" class="text-sm font-medium">Payment Schedule</label>
                    <select name="payment_schedule_id" id="payment_schedule_id" class="border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select payment schedule...</option>
                        @foreach($paymentPlanSummary['schedules'] as $schedule)
                            @php
                                $isPaid = $schedule->status === 'paid';
                                $isFirstPayment = $invoice->paid_amount == 0;
                                $isSelectable = !$isPaid && (!$isFirstPayment || $schedule->installment_number === 0);
                            @endphp
                            <option value="{{ $schedule->id }}" 
                                    {{ !$isSelectable ? 'disabled' : '' }}
                                    data-balance="{{ $schedule->amount_due - $schedule->amount_paid }}"
                                    data-description="{{ $schedule->description }}"
                                    data-installment="{{ $schedule->installment_number }}">
                                {{ $schedule->description }} 
                                (₱{{ number_format($schedule->amount_due - $schedule->amount_paid, 2) }} remaining)
                                @if($isPaid)
                                    - PAID
                                @elseif($isFirstPayment && $schedule->installment_number > 0)
                                    - Pay down payment first
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Select which payment schedule this payment applies to.
                    </p>
                    </div>
                @endif
                
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
        </form>

        <x-slot name="modal_buttons">
            <button id="record-payment-modal-cancel-btn"
                class="bg-gray-100 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-md text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-200 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="record-payment-modal-form"
                class="bg-green-600 text-[14px] px-3 py-2 rounded-md text-white font-bold hover:ring hover:ring-green-200 hover:bg-green-500 transition duration-150 shadow-sm">
                Record Payment
            </button>
        </x-slot>

    </x-modal>
@endsection

@section('content')
	<x-alert />
	<div class="bg-[#1A3165] absolute inset-0 flex flex-col justify-center items-center">
		<div class="bg-white w-11/12 md:w-4/5 max-h-[95%] rounded-xl shadow-lg overflow-auto">
			<div class="p-8">
				<!-- Header Section -->
				<div class="flex items-center justify-between mb-8">
					<div class="flex items-center gap-4">
						<img src="{{ asset('images/Dreamy_logo.png') }}" alt="Logo" class="w-12 h-12 object-contain">
						<div>
							<h1 class="text-2xl font-bold text-[#1A3165]">Invoice Details</h1>
							<p class="text-sm text-gray-600">Invoice #{{ $invoice->invoice_number }}</p>
						</div>
					</div>
					<div class="text-right">
						<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : ($invoice->status === 'unpaid' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
							<i class="fi fi-sr-circle-dot"></i>
							{{ ucfirst($invoice->status) }}
						</div>
						<p class="text-xs text-gray-500 mt-1">{{ $invoice->created_at?->format('M d, Y') }}</p>
					</div>
				</div>

				<!-- Student Information Card -->
				<div class="bg-gradient-to-r from-[#1A3165] to-[#199BCF] rounded-xl p-6 mb-8 text-white">
					<div class="flex items-center justify-between">
						<div>
							<h2 class="text-xl font-bold mb-2">{{ $invoice->student?->full_name ?? 'Unknown Student' }}</h2>
							<div class="grid grid-cols-2 gap-4 text-sm">
								<div>
									<p class="text-blue-100">Student ID</p>
									<p class="font-semibold">{{ $invoice->student?->lrn ?? '-' }}</p>
								</div>
								<div>
									<p class="text-blue-100">Program</p>
									<p class="font-semibold">{{ $invoice->student?->program ?? '-' }}</p>
								</div>
								<div>
									<p class="text-blue-100">Year Level</p>
									<p class="font-semibold">{{ $invoice->student?->grade_level ?? '-' }}</p>
								</div>
								<div>
									<p class="text-blue-100">Academic Term</p>
									<p class="font-semibold">{{ $invoice->academicTerm?->getFullNameAttribute() ?? '-' }}</p>
								</div>
							</div>
						</div>
						<div class="text-right">
							<div class="bg-white/20 rounded-lg p-4">
								<p class="text-sm text-blue-100">Total Amount</p>
								<p class="text-2xl font-bold">₱{{ number_format($invoice->total_amount, 2) }}</p>
							</div>
						</div>
					</div>
				</div>

				<!-- Invoice Items Section -->
				<div class="mb-8">
					<h3 class="text-lg font-semibold text-[#1A3165] mb-4">Invoice Items</h3>
					<div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-[#1A3165]">
								<tr>
									<th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
									<th class="px-6 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Amount</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200">
								@forelse ($invoice->items as $item)
									<tr class="hover:bg-gray-50">
										<td class="px-6 py-4 whitespace-nowrap">
											<div class="text-sm font-medium text-gray-900">{{ $item->fee?->name ?? 'School Fee' }}</div>
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
											₱{{ number_format($item->amount, 2) }}
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="2" class="px-6 py-8 text-center text-gray-500">
											<i class="fi fi-sr-document text-4xl mb-2 block"></i>
											No items found.
										</td>
									</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>

				<!-- Payment Plan Section -->
				@include('user-admin.invoice.partials.payment-plan')

				<!-- Financial Summary & Actions -->
				<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
					<!-- Financial Summary Card -->
					<div class="lg:col-span-1">
						<div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
							<h3 class="text-lg font-semibold text-[#1A3165] mb-4">Financial Summary</h3>
							<div class="space-y-3">
								<div class="flex justify-between items-center py-2 border-b border-gray-200">
									<span class="text-sm text-gray-600">Subtotal</span>
									<span class="font-semibold text-gray-900">₱{{ number_format($invoice->total_amount, 2) }}</span>
								</div>
								<div class="flex justify-between items-center py-2 border-b border-gray-200">
									<span class="text-sm text-gray-600">Paid</span>
									<span class="font-semibold text-green-600">₱{{ number_format($invoice->paid_amount, 2) }}</span>
								</div>
								<div class="flex justify-between items-center py-3 bg-[#1A3165] rounded-lg px-4 -mx-4">
									<span class="text-sm font-medium text-white">Balance</span>
									<span class="text-lg font-bold text-white">₱{{ number_format($invoice->balance, 2) }}</span>
								</div>
							</div>
						</div>
					</div>

					<!-- Payments History -->
					<div class="lg:col-span-2">
						<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
							<div class="px-6 py-4 border-b border-gray-200">
								<h3 class="text-lg font-semibold text-[#1A3165]">Payment History</h3>
							</div>
							<div class="max-h-80 overflow-y-auto">
								@if($invoice->payments->count() > 0)
									<table class="min-w-full divide-y divide-gray-200">
										<thead class="bg-gray-50 sticky top-0 ">
											<tr>
												<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
												<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
												<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
												<th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
											</tr>
										</thead>
										<tbody class="bg-white divide-y divide-gray-200">
											@foreach ($invoice->payments as $payment)
												<tr class="hover:bg-gray-50">
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ \Illuminate\Support\Carbon::parse($payment->payment_date)->format('M d, Y') }}
													</td>
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ $payment->reference_no ?? '-' }}
													</td>
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
															{{ ucfirst($payment->method ?? 'Cash') }}
														</span>
													</td>
													<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
														₱{{ number_format($payment->amount, 2) }}
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								@else
									<div class="px-6 py-8 text-center text-gray-500">
										<i class="fi fi-sr-credit-card text-4xl mb-2 block"></i>
										<p class="text-sm">No payments recorded yet.</p>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
					<a href="/school-fees/invoices" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition">
						<i class="fi fi-rr-arrow-small-left text-[16px]"></i>
						Back to Invoices
					</a>
					@if (!request()->has('from') && ($invoice->payment_mode && $invoice->payment_mode !== 'flexible') && $invoice->balance > 0)
					<button id="record-payment-open" class="inline-flex items-center gap-2 bg-[#199BCF] text-white font-semibold px-4 py-2 rounded-lg hover:bg-[#1A3165] transition">
						<i class="fi fi-rr-credit-card text-[16px]"></i>
						Record Payment
					</button>
					@endif
				</div>
                </div>
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
            // Initialize Record Payment modal using existing modal system
            initModal('record-payment-modal', 'record-payment-open', 'record-payment-modal-close-btn',
                'record-payment-modal-cancel-btn',
                'modal-container-1');

            // Amount validation for one-time payments
            const amountInput = document.getElementById('amount');
            if (amountInput) {
                amountInput.addEventListener('input', function() {
                    const amount = parseFloat(this.value);
                    const maxAmount = parseFloat(this.getAttribute('max'));
                    const minAmount = parseFloat(this.getAttribute('min'));
                    
                    // Remove any existing validation classes
                    this.classList.remove('border-red-500', 'border-green-500');
                    
                    if (this.value === '') {
                        return; // Don't validate empty input
                    }
                    
                    // Check if this is a one-time payment with no previous payments
                    const isOneTimeFirstPayment = {{ $invoice->payment_mode === 'full' && $invoice->paid_amount == 0 ? 'true' : 'false' }};
                    const totalAmount = {{ $invoice->total_amount }};
                    
                    if (isOneTimeFirstPayment && amount < totalAmount) {
                        this.classList.add('border-red-500');
                        this.setCustomValidity('For one-time payments, the first payment must be the full amount of ₱' + totalAmount.toFixed(2));
                    } else if (isNaN(amount) || amount < minAmount) {
                        this.classList.add('border-red-500');
                        this.setCustomValidity('Amount must be at least ₱' + minAmount.toFixed(2));
                    } else if (amount > maxAmount) {
                        this.classList.add('border-red-500');
                        this.setCustomValidity('Amount cannot exceed ₱' + maxAmount.toFixed(2) + ' (remaining balance)');
                    } else {
                        this.classList.add('border-green-500');
                        this.setCustomValidity('');
                    }
                });
            }

            // Auto-fill and lock amount based on selected schedule
            const scheduleSelect = document.getElementById('payment_schedule_id');
            if (scheduleSelect && amountInput) {
                const applyAmountLock = (remaining) => {
                    const value = parseFloat(remaining || 0).toFixed(2);
                    amountInput.value = value;
                    amountInput.min = value;
                    amountInput.max = value;
                    amountInput.step = '0.01';
                    amountInput.readOnly = true;
                    amountInput.classList.remove('border-red-500');
                    amountInput.classList.add('border-green-500');
                    amountInput.setCustomValidity('');
                };

                const clearAmountLock = () => {
                    amountInput.readOnly = false;
                    amountInput.min = '0.01';
                    amountInput.max = '{{ $invoice->balance }}';
                    amountInput.classList.remove('border-green-500');
                };

                // Initialize on load if a value is preselected (unlikely but safe)
                if (scheduleSelect.value) {
                    const selected = scheduleSelect.options[scheduleSelect.selectedIndex];
                    const remaining = selected ? selected.getAttribute('data-balance') : null;
                    if (remaining) applyAmountLock(remaining);
                }

                scheduleSelect.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    const remaining = opt ? opt.getAttribute('data-balance') : null;
                    const installment = opt ? opt.getAttribute('data-installment') : null;
                    // Only lock for months 1..9; allow flexible DP (installment 0)
                    if (remaining && installment !== null && parseInt(installment) > 0) {
                        applyAmountLock(remaining);
                    } else {
                        clearAmountLock();
                    }
                });
            }

            let studentSeach = document.getElementById('studentSearch');
            let studentCount = document.querySelector('#studentCount');
            let sectionName = document.querySelector('#section_name');
            let sectionRoom = document.querySelector('#section_room');

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
