@extends('layouts.admin', ['title' => 'Invoice'])
@section('modal')
    <x-modal modal_id="record-payment-modal" modal_name="Record Payment" close_btn_id="record-payment-modal-close-btn"
        modal_container_id="modal-container-1">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-credit-card flex justify-center items-center '></i>
        </x-slot>

        <form method="POST" action="{{ route('invoice.payments.store', ['invoice' => $invoice->id]) }}"
            id="record-payment-modal-form" class="p-6">
            @csrf
            <div class="flex flex-col gap-3">


                @if ($invoice->has_payment_plan && $paymentPlanSummary)
                    <div class="flex flex-col">
                        <label for="payment_schedule_id" class="text-sm font-medium mb-1">Payment Schedule</label>
                        <select name="payment_schedule_id" id="payment_schedule_id"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            required>
                            <option value="">Select payment schedule...</option>
                            @foreach ($paymentPlanSummary['schedules'] as $schedule)
                                @php
                                    $isPaid = $schedule->status === 'paid';
                                    $isFirstPayment = $invoice->paid_amount == 0;
                                    $isSelectable =
                                        !$isPaid && (!$isFirstPayment || $schedule->installment_number === 0);
                                @endphp
                                <option value="{{ $schedule->id }}" {{ !$isSelectable ? 'disabled' : '' }}
                                    data-balance="{{ $schedule->amount_due - $schedule->amount_paid }}"
                                    data-description="{{ $schedule->description }}"
                                    data-installment="{{ $schedule->installment_number }}">
                                    {{ $schedule->description }}
                                    (₱{{ number_format($schedule->amount_due - $schedule->amount_paid, 2) }} remaining)
                                    @if ($isPaid)
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
                    <label for="amount" class="text-sm font-medium mb-1">Amount</label>
                    <input type="number" step="0.01"
                        min="{{ $invoice->payment_mode === 'full' && $invoice->paid_amount == 0 ? $invoice->total_amount : '0.01' }}"
                        max="{{ $invoice->balance }}" name="amount" id="amount"
                        value="{{ !$invoice->has_payment_plan ? $invoice->total_amount : '' }}"
                        {{ !$invoice->has_payment_plan ? 'readonly' : '' }}
                        class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 {{ !$invoice->has_payment_plan ? 'bg-gray-200 cursor-not-allowed' : 'bg-gray-100' }} self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                        placeholder="Enter payment amount" required />
                    <p class="text-xs text-gray-500 mt-1">
                        @if ($invoice->payment_mode === 'full')
                            @if ($invoice->paid_amount == 0)
                                Minimum amount: ₱{{ number_format($invoice->total_amount, 2) }} (full payment required)
                            @else
                                Maximum amount: ₱{{ number_format($invoice->balance, 2) }} (remaining balance)
                            @endif
                        @elseif (!$invoice->has_payment_plan)
                            One-time payment amount is fixed at the total invoice amount
                        @else
                            Enter the payment amount
                        @endif
                    </p>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col">
                        <label for="method" class="text-sm font-medium mb-1">Method</label>
                        <input type="text" name="method" id="method"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            placeholder="Cash, Bank, GCASH" />
                    </div>
                    <div class="flex flex-col">
                        <label for="type" class="text-sm font-medium mb-1">Type</label>
                        <input type="text" name="type" id="type"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            placeholder="Partial / Full" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col">
                        <label for="payment_date" class="text-sm font-medium mb-1">Payment Date</label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            value="{{ now()->toDateString() }}" required />
                    </div>
                    <div class="flex flex-col">
                        <label for="reference_no" class="text-sm font-medium mb-1">Reference No.</label>
                        <input type="text" name="reference_no" id="reference_no"
                            class="flex flex-row justify-start items-center border border-[#1e1e1e]/10 bg-gray-100 self-start rounded-lg py-2 px-3 gap-2 w-full outline-none hover:ring hover:ring-[#199BCF]/20 focus-within:ring focus-within:ring-[#199BCF]/10 focus-within:border-[#199BCF] transition duration-150 shadow-sm text-[14px]"
                            placeholder="Optional" />
                    </div>
                </div>

                {{-- Discount Section (conditional display) --}}
                <div id="discount-section" class="space-y-2 w-full" style="display: {{ $invoice->has_payment_plan ? 'none' : 'block' }};">
                    <div class="flex flex-row justify-between items-center">
                        <h4 class="italic text-sm font-medium text-gray-600 flex flex-row gap-1">
                            @if($isEarlyEnrollee && $earlyDiscountPercentage > 0)
                                Early enrollee discount is taking effect
                                <i class="fi fi-bs-check text-sm flex justify-center items-center text-green-600"></i>
                            @else
                                No early enrollee discount
                                <i class="fi fi-rr-cross-small text-[18px] flex justify-center items-center"></i>
                            @endif
                        </h4>
                        <label for="custom-discount" class="text-sm font-medium flex gap-2">
                            <input type="checkbox" name="custom_discount_enabled" id="custom-discount">Eligible for custom
                            discount?
                        </label>
                    </div>
                    
                    {{-- Custom discount options (hidden by default) --}}
                    <div id="custom-discount-options" class="flex flex-col justify-start items-center px-4 space-y-2 max-h-[150px] overflow-y-scroll" style="display: none;">
                        @foreach($availableDiscounts as $discount)
                            <label for="discount-{{ $discount->id }}" class="flex flex-row justify-between items-center gap-2 w-full text-sm">
                            <div class="flex flex-row justify-center items-center gap-2">
                                    <input type="checkbox" name="selected_discounts[]" value="{{ $discount->id }}" id="discount-{{ $discount->id }}" class="discount-checkbox">
                                    {{ $discount->name }}
                            </div>
                                <h4>Amount: {{ $discount->getFormattedValue() }}</h4>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Hidden input for final amount --}}
                <input type="hidden" name="final_amount" id="final_amount" value="0">

            </div>
        </form>
        <x-slot name="modal_info">
            <p data-total class="font-semibold text-[#1A3165] text-[14px]">Total: ₱0.00</p>
        </x-slot>
        <x-slot name="modal_buttons">
            <button id="record-payment-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="record-payment-modal-form"
                class="bg-green-600 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-green-200 hover:bg-green-500 transition duration-150 shadow-sm">
                Record Payment
            </button>
        </x-slot>

    </x-modal>
    {{-- delete item --}}
    <x-modal modal_id="delete-invoice-item-modal" modal_name="Delete Invoice Item"
        close_btn_id="delete-invoice-item-close-btn" modal_container_id="modal-container-2">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600">Are you sure you want to remove this item? If the invoice becomes empty, it
                        will be permanently deleted and need to be reassigned.
                    </p>
                    <p class="font-semibold mt-2" id="invoice_item_text"></p>
                </div>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-invoice-item-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <form id="delete-invoice-item-form" class="inline">
                @csrf
                <button type="submit" id="delete-invoice-item-submit-btn"
                    class="bg-red-500 text-[14px] px-3 py-2 rounded-xl text-white font-bold hover:ring hover:ring-red-200 hover:bg-red-400 transition duration-150 shadow-sm hover:scale-95">
                    Delete Item
                </button>
            </form>
        </x-slot>

    </x-modal>

    {{-- PIN Verification Modal for Payment Recording --}}
    <x-modal modal_id="pin-verification-modal" modal_name="Security Verification"
        close_btn_id="pin-verification-modal-close-btn" modal_container_id="modal-container-3">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-lock flex justify-center items-center text-blue-500'></i>
        </x-slot>

        <div class="p-6">
            <div class="flex flex-col items-center space-y-4">
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Enter Security PIN</h3>
                    <p class="text-gray-600">Please enter your 6-digit security PIN to record this payment.</p>
                </div>

                <form id="pin-verification-form" class="w-full">
                    @csrf
                    <div class="flex flex-col items-center space-y-4">
                        <div class="relative">
                            <input type="password" id="payment_pin" name="pin" maxlength="6" pattern="[0-9]{6}"
                                class="w-32 text-center text-2xl font-mono tracking-widest border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-[#199BCF] focus:ring-2 focus:ring-[#199BCF] outline-none transition duration-200"
                                placeholder="••••••" autocomplete="off" inputmode="numeric" required>
                        </div>
                        <p class="text-xs text-gray-500 text-center">
                            Enter your 6-digit security PIN to authorize this payment
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <x-slot name="modal_buttons">
            <button id="pin-verification-modal-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-150">
                Cancel
            </button>
            <button type="submit" form="pin-verification-form" id="pin-verification-submit-btn"
                class="bg-[#199BCF] py-2 px-3 rounded-xl text-[14px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                Verify & Record Payment
            </button>
        </x-slot>
    </x-modal>
@endsection

@section('content')
    <x-alert />
    <div class="bg-[#1A3165] absolute inset-0 flex flex-col justify-center items-center">
        <div class="bg-white w-11/12 md:w-4/5 max-h-[95%] rounded-xl shadow-lg overflow-auto">
            <div class="relative px-8 pt-8 pb-6">
                <!-- Header Section -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/Dreamy_logo.png') }}" alt="Logo"
                            class="w-12 h-12 object-contain">
                        <div>
                            <h1 class="text-2xl font-bold text-[#1A3165]">Invoice Details</h1>
                            <p class="text-sm text-gray-600">Invoice #{{ $invoice->invoice_number }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : ($invoice->status === 'unpaid' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
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
                            <h2 class="text-xl font-bold mb-2">{{ $invoice->student?->full_name ?? 'Unknown Student' }}
                            </h2>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-100">Student ID</p>
                                    <p class="font-semibold">{{ $invoice->student?->lrn ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-100">Program</p>
                                    <p class="font-semibold">{{ $invoice->student?->program->code ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-100">Year Level</p>
                                    <p class="font-semibold">{{ $invoice->student?->grade_level ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-100">Academic Term</p>
                                    <p class="font-semibold">{{ $invoice->academicTerm?->getFullNameAttribute() ?? '-' }}
                                    </p>
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
                <div class="mb-8 bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-[#1A3165]">Invoice Items</h3>
                    </div>
                    <div class="overflow-hidden border border-gray-200 rounded-xl">
                        <table id="invoice-items" class="w-full" style="width: 100% !important; table-layout: fixed;">
                            <thead class="bg-[#1A3165]">
                                <tr>
                                    <th style="width: 50%;"
                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Description</th>
                                    <th style="width: 30%;"
                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                        Amount</th>
                                    <th style="width: 20%;"
                                        class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

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
                                    <span
                                        class="font-semibold text-gray-900">₱{{ number_format($invoice->total_amount, 2) }}</span>
                                </div>
                                @if($invoice->has_payment_plan && $invoice->paymentPlan && $invoice->paymentPlan->total_discount > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Total Discount</span>
                                    <span
                                        class="font-semibold text-red-600">-₱{{ number_format($invoice->paymentPlan->total_discount, 2) }}</span>
                                </div>
                                @elseif(!$invoice->has_payment_plan && $invoice->payments->sum('total_discount') > 0)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Total Discount</span>
                                    <span
                                        class="font-semibold text-red-600">-₱{{ number_format($invoice->payments->sum('total_discount'), 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm text-gray-600">Paid</span>
                                    <span
                                        class="font-semibold text-green-600">₱{{ number_format($invoice->paid_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-[#1A3165] rounded-lg px-4 -mx-4">
                                    <span class="text-sm font-medium text-white">Balance</span>
                                    <span
                                        class="text-lg font-bold text-white">₱{{ number_format($invoice->balance, 2) }}</span>
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
                                @if ($invoice->payments->count() > 0)
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0 ">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Date</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Reference</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Method</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Schedule</th>
                                                <th
                                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Amount</th>
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
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ ucfirst($payment->method ?? 'Cash') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($payment->paymentSchedule)
                                                            @if($payment->paymentSchedule->installment_number === 0)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    Down Payment
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                    Installment #{{ $payment->paymentSchedule->installment_number }}
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                One-time Payment
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
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
                <div id="bottom-nav" class="sticky bottom-4 left-0 w-full flex items-center justify-between mt-8 pt-6 px-6 pb-6 rounded-xl transition-all duration-500 ease-in-out">
                    <a href="/school-fees/invoices"
                        class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        <i class="fi fi-rr-arrow-small-left text-[16px] flex justify-center items-center"></i>
                        Back to Invoices
                    </a>
                    @can('record payment')
                        @if (
                            !request()->has('from') &&
                                ($invoice->payment_mode && $invoice->payment_mode !== 'flexible') &&
                                $invoice->balance > 0)
                            <button id="record-payment-open"
                                class="flex flex-row bg-[#199BCF] py-2.5 px-3 rounded-xl text-[16px] font-semibold gap-2 text-white hover:ring hover:ring-[#C8A165]/20 hover:bg-[#C8A165] hover:scale-95 transition duration-200 shadow-[#199BCF]/20 hover:shadow-[#C8A165]/20 shadow-lg truncate">
                                <i class="fi fi-rr-credit-card flex justify-center items-center text-[16px]"></i>
                                Record Payment
                            </button>
                        @endif
                    @endcan

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="module">
        import {
            dropDown
        } from "/js/dropdown.js";
        import {
            clearSearch
        } from "/js/clearSearch.js"
        import {
            initModal
        } from "/js/modal.js";
        import {
            showAlert
        } from "/js/alert.js";
        import {
            showLoader,
            hideLoader
        } from "/js/loader.js";
        import {
            initCustomDataTable
        } from "/js/initTable.js";

        let table1;
        let selectedGrade = '';
        let selectedProgram = '';
        let selectedPageLength = '';

        let invoiceId = @json($invoice->id)

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Record Payment modal using existing modal system
            initModal('record-payment-modal', 'record-payment-open', 'record-payment-modal-close-btn',
                'record-payment-modal-cancel-btn',
                'modal-container-1');

            // Initialize PIN Verification modal
            initModal('pin-verification-modal', null, 'pin-verification-modal-close-btn',
                'pin-verification-modal-cancel-btn',
                'modal-container-3');

            let schoolfeeTable = initCustomDataTable(
                'invoice-items',
                `/getInvoiceItems/${invoiceId}`,
                [{
                        data: 'name',
                        render: function(data, type, row) {
                            return `<span class="text-sm font-medium text-gray-900">${data}</span>`;
                        }
                    },
                    {
                        data: 'amount',
                        render: function(data, type, row) {
                            return `<span class="text-sm font-semibold text-gray-900">₱${data}</span>`;
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                            <div class='flex flex-row justify-center items-center gap-2'>
                                <button type="button"
                                    id="open-delete-invoice-item-btn-${data}"
                                    data-invoice-item-id="${data}"
                                    data-item-name="${row.name}"
                                    data-invoice-id="${invoiceId}"
                                    data-school-fee-id="${row.school_fee_id || ''}"
                                    class="delete-invoice-item-btn group relative inline-flex items-center gap-1 bg-red-100 text-red-500 font-semibold p-1 rounded-xl hover:bg-red-500 hover:ring hover:ring-red-200 hover:text-white transition duration-150">
                                    <i class="fi fi-rr-trash text-[16px] flex justify-center items-center"></i>
                                    Remove
                                </button>
                            </div>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                [
                    [0, 'asc']
                ],
                null,
                [{
                        targets: 0,
                        width: '50%',
                        className: 'text-left'
                    },
                    {
                        targets: 1,
                        width: '30%',
                        className: 'text-left'
                    },
                    {
                        targets: 2,
                        width: '20%',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                false, // paging = false,
                null
            );

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
                    const isOneTimeFirstPayment =
                        {{ $invoice->payment_mode === 'full' && $invoice->paid_amount == 0 ? 'true' : 'false' }};
                    const totalAmount = {{ $invoice->total_amount }};

                    if (isOneTimeFirstPayment && amount < totalAmount) {
                        this.classList.add('border-red-500');
                        this.setCustomValidity(
                            'For one-time payments, the first payment must be the full amount of ₱' +
                            totalAmount.toFixed(2));
                    } else if (isNaN(amount) || amount < minAmount) {
                        this.classList.add('border-red-500');
                        this.setCustomValidity('Amount must be at least ₱' + minAmount.toFixed(2));
                    } else if (amount > maxAmount) {
                        this.classList.add('border-red-500');
                        this.setCustomValidity('Amount cannot exceed ₱' + maxAmount.toFixed(2) +
                            ' (remaining balance)');
                    } else {
                        this.classList.add('border-green-500');
                        this.setCustomValidity('');
                    }
                });
            }

            // Auto-fill and lock amount based on selected schedule
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
                    const isDownPayment = installment === '0' || installment === 'Down';
                    
                    // Only lock for months 1..9; allow flexible DP (installment 0)
                    if (remaining && installment !== null && parseInt(installment) > 0) {
                        applyAmountLock(remaining);
                    } else {
                        clearAmountLock();
                    }

                    // Show/hide discount section based on payment mode and schedule selection
                    if (discountSection) {
                        const hasPaymentPlan = {{ $invoice->has_payment_plan ? 'true' : 'false' }};
                        
                        if (hasPaymentPlan) {
                            // For installment plans: only show for down payment
                            if (isDownPayment) {
                                discountSection.style.display = 'block';
                                updateTotal(); // Update total when showing discount section
                            } else {
                                discountSection.style.display = 'none';
                                // Reset discount selections when hiding
                                document.getElementById('custom-discount').checked = false;
                                document.getElementById('custom-discount-options').style.display = 'none';
                                document.querySelectorAll('.discount-checkbox').forEach(checkbox => {
                                    checkbox.checked = false;
                                });
                                updateTotal();
                            }
                        } else {
                            // For one-time payments: always show (already visible by default)
                            discountSection.style.display = 'block';
                        }
                    }
                });
            }

            // Variables to track current item for deletion
            let currentItemId = null;
            let currentInvoiceId = null;
            let currentSchoolFeeId = null;

            // Function to initialize delete invoice item modals (following the roles pattern)
            function initializeDeleteInvoiceItemModals() {
                document.querySelectorAll('.delete-invoice-item-btn').forEach((button, index) => {
                    let itemId = button.getAttribute('data-invoice-item-id');
                    let buttonId = `open-delete-invoice-item-btn-${itemId}`;

                    // Initialize modal for this specific button
                    initModal('delete-invoice-item-modal', buttonId,
                        'delete-invoice-item-close-btn',
                        'delete-invoice-item-cancel-btn',
                        'modal-container-2');

                    // Add click event listener
                    button.addEventListener('click', () => {
                        const itemName = button.getAttribute('data-item-name');
                        const invoiceIdAttr = button.getAttribute('data-invoice-id');
                        const schoolFeeId = button.getAttribute('data-school-fee-id');

                        // Store current item info
                        currentItemId = itemId;
                        currentInvoiceId = invoiceIdAttr;
                        currentSchoolFeeId = schoolFeeId;

                        // Update modal text
                        const itemTextElement = document.querySelector('#invoice_item_text');
                        if (itemTextElement) {
                            itemTextElement.innerHTML = `Item: ${itemName}`;
                        }

                    });
                });
            }

            // Initialize delete invoice item modals after table draws
            schoolfeeTable.on('draw', function() {
                initializeDeleteInvoiceItemModals();
            });

            // Also initialize on page load (in case there's initial data)
            initializeDeleteInvoiceItemModals();

            // Store payment form data for later submission
            let pendingPaymentData = null;

            // Override the payment form submission to require PIN verification
            const paymentForm = document.getElementById('record-payment-modal-form');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Store the form data
                    const formData = new FormData(this);
                    pendingPaymentData = {
                        amount: formData.get('amount'),
                        remaining_balance: formData.get('final_amount'), // This now contains the remaining balance
                        payment_date: formData.get('payment_date'),
                        method: formData.get('method'),
                        type: formData.get('type'),
                        reference_no: formData.get('reference_no'),
                        payment_schedule_id: formData.get('payment_schedule_id'),
                        custom_discount_enabled: formData.get('custom_discount_enabled'),
                        selected_discounts: formData.getAll('selected_discounts[]')
                    };

                    // Close payment modal and open PIN verification
                    closeModal('record-payment-modal', 'modal-container-1');

                    // Open PIN verification modal
                    const pinModal = document.querySelector('#pin-verification-modal');
                    const pinContainer = document.querySelector('#modal-container-3');
                    if (pinModal && pinContainer) {
                        pinModal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                        pinModal.classList.add('opacity-100', 'scale-100');
                        pinContainer.classList.remove('opacity-0', 'pointer-events-none');
                        pinContainer.classList.add('opacity-100');
                    }

                    // Focus on PIN input and add enhanced input handling
                    setTimeout(() => {
                        const pinInput = document.getElementById('payment_pin');
                        pinInput.focus();

                        // Only allow numeric input
                        pinInput.addEventListener('input', function(e) {
                            // Remove any non-numeric characters
                            this.value = this.value.replace(/[^0-9]/g, '');

                            // Auto-submit when 6 digits are entered
                            if (this.value.length === 6) {
                                setTimeout(() => {
                                    document.getElementById(
                                        'pin-verification-submit-btn').click();
                                }, 100);
                            }
                        });

                        // Prevent non-numeric key presses
                        pinInput.addEventListener('keypress', function(e) {
                            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab',
                                    'Enter'
                                ].includes(e.key)) {
                                e.preventDefault();
                            }
                        });
                    }, 300);
                });
            }

            // Handle PIN verification form submission
            const pinVerificationForm = document.getElementById('pin-verification-form');
            if (pinVerificationForm) {
                pinVerificationForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!pendingPaymentData) {
                        showAlert('error', 'No payment data found. Please try again.');
                        return;
                    }

                    const pin = document.getElementById('payment_pin').value;

                    if (!pin || pin.length !== 6) {
                        showAlert('error', 'Please enter a valid 6-digit PIN.');
                        return;
                    }

                    // Add PIN to payment data
                    pendingPaymentData.pin = pin;

                    // Submit payment with PIN verification
                    submitPaymentWithPin(pendingPaymentData);
                });
            }

            // Delete invoice item form submission
            const deleteInvoiceItemForm = document.getElementById('delete-invoice-item-form');
            if (deleteInvoiceItemForm) {
                deleteInvoiceItemForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!currentItemId) {
                        alert('No item selected for deletion');
                        return;
                    }

                    const deleteUrl = `/invoice/${currentInvoiceId}/item/${currentItemId}`;

                    showLoader();

                    fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            return response.json();
                        })
                        .then(data => {

                            hideLoader();

                            if (data.success === false && data.is_paid === false) {

                                closeModal('delete-invoice-item-modal', 'modal_container_2')
                                showAlert('error', data.message);

                            } else if (data.success === false && data.has_payment_plans === true) {

                                showAlert('success', data.message);
                                closeModal('delete-invoice-item-modal', 'modal_container_2')

                            } else if (data.success === false && data.has_payments === true) {

                                showAlert('error', data.message);
                                closeModal('delete-invoice-item-modal', 'modal_container_2')

                            } else if (data.success === true && data.is_invoice_empty === false) {

                                showAlert('success', data.message);
                                closeModal('delete-invoice-item-modal', 'modal_container_2')
                                setTimeout(() => {
                                    window.location.reload()
                                }, 2000);

                            } else if (data.success === true && data.is_invoice_empty === true) {

                                showAlert('success', data.message || 'Unknown error occurred');
                                closeModal('delete-invoice-item-modal', 'modal_container_2')

                                setTimeout(() => {
                                    window.location.href = '/school-fees/invoices'
                                }, 2000);

                            } else {
                                showAlert('error', data.message || 'Unknown error occurred');
                            }
                        })
                        .catch(error => {
                            if (typeof hideLoader === 'function') {
                                hideLoader();
                            }
                            if (typeof showAlert === 'function') {
                                showAlert('error', 'An error occurred while deleting the invoice item');
                            } else {
                                alert('An error occurred while deleting the invoice item');
                            }
                        });
                });
            }


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

            // Function to submit payment with PIN verification
            function submitPaymentWithPin(paymentData) {
                showLoader();

                // Create FormData for the payment submission
                const formData = new FormData();
                formData.append('amount', paymentData.amount);
                formData.append('remaining_balance', paymentData.remaining_balance);
                formData.append('payment_date', paymentData.payment_date);
                formData.append('method', paymentData.method || '');
                formData.append('type', paymentData.type || '');
                formData.append('reference_no', paymentData.reference_no || '');
                formData.append('payment_schedule_id', paymentData.payment_schedule_id || '');
                formData.append('pin', paymentData.pin);
                formData.append('_token', '{{ csrf_token() }}');
                
                // Add discount data
                if (paymentData.custom_discount_enabled) {
                    formData.append('custom_discount_enabled', '1');
                }
                if (paymentData.selected_discounts && paymentData.selected_discounts.length > 0) {
                    paymentData.selected_discounts.forEach(discountId => {
                        formData.append('selected_discounts[]', discountId);
                    });
                }


                fetch(`/invoice/${invoiceId}/payments`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            return response.json().then(error => {
                                throw new Error(error.message || 'Payment failed');
                            });
                        }
                    })
                    .then(data => {
                        hideLoader();

                        if (data.success) {
                            // Close PIN verification modal
                            closeModal('pin-verification-modal', 'modal-container-3');

                            // Show success message
                            showAlert('success', data.message || 'Payment recorded successfully.');

                            // Clear PIN input
                            document.getElementById('payment_pin').value = '';

                            // Reset pending payment data
                            pendingPaymentData = null;

                            // Reload page to show updated payment information
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            showAlert('error', data.message || 'Payment failed. Please try again.');
                        }
                    })
                    .catch(error => {
                        hideLoader();
                        showAlert('error', error.message || 'An error occurred while recording the payment.');
                    });
            }

        });

        // Discount calculation functionality
        const amountInput = document.getElementById('amount');
        const totalDisplay = document.querySelector('[data-total]');
        const finalAmountInput = document.getElementById('final_amount');
        const customDiscountCheckbox = document.getElementById('custom-discount');
        const customDiscountOptions = document.getElementById('custom-discount-options');
        const discountCheckboxes = document.querySelectorAll('.discount-checkbox');
        const scheduleSelect = document.getElementById('payment_schedule_id');
        const discountSection = document.getElementById('discount-section');
        
        // Early enrollment data from backend
        const isEarlyEnrollee = @json($isEarlyEnrollee);
        const earlyDiscountPercentage = @json($earlyDiscountPercentage);
        
        // Update total when amount input changes
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                updateTotal();
            });
        }
        
        // Toggle custom discount options
        if (customDiscountCheckbox) {
            customDiscountCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    customDiscountOptions.style.display = 'flex';
                } else {
                    customDiscountOptions.style.display = 'none';
                    // Uncheck all discount checkboxes
                    discountCheckboxes.forEach(checkbox => checkbox.checked = false);
                    updateTotal();
                }
            });
        }
        
        // Update total when discount checkboxes change
        discountCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });
        
        function updateTotal() {
            let baseAmount = parseFloat(amountInput.value) || 0;
            
            // Check if this is a down payment schedule in an installment plan
            let isDownPayment = false;
            const hasPaymentPlan = {{ $invoice->has_payment_plan ? 'true' : 'false' }};
            
            if (hasPaymentPlan && scheduleSelect && scheduleSelect.selectedIndex >= 0) {
                const selectedSchedule = scheduleSelect.options[scheduleSelect.selectedIndex];
                const installment = selectedSchedule ? selectedSchedule.getAttribute('data-installment') : null;
                isDownPayment = installment === '0' || installment === 'Down';
            } else if (!hasPaymentPlan) {
                // For one-time payments, never do live calculation
                isDownPayment = false;
            }
            
            // Calculate discounts for both down payments and one-time payments
            let totalDiscount = 0;
            let earlyDiscount = 0;
            let customDiscounts = 0;
            
            // Calculate early enrollment discount (always applied to total invoice amount)
            if (isEarlyEnrollee && earlyDiscountPercentage > 0) {
                const totalInvoiceAmount = {{ $invoice->total_amount }};
                earlyDiscount = totalInvoiceAmount * (earlyDiscountPercentage / 100);
                totalDiscount += earlyDiscount;
            }
            
            // Calculate custom discounts (applied to total invoice amount)
            if (isDownPayment || !hasPaymentPlan) {
                const totalInvoiceAmount = {{ $invoice->total_amount }};
                discountCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const discountAmount = getDiscountAmount(checkbox.value, totalInvoiceAmount);
                        customDiscounts += discountAmount;
                        totalDiscount += discountAmount;
                    }
                });
            }
            
            // Calculate final amount after discounts
            const finalAmount = baseAmount - customDiscounts; // Only subtract custom discounts from payment amount
            
            if (isDownPayment) {
                // For down payments in installment plans: show live calculation following backend formula
                // Formula: Monthly = (Total Invoice Amount - Early Discount - Down Payment - Custom Discounts) / months
                const totalInvoiceAmount = {{ $invoice->total_amount }};
                const discountedTotal = totalInvoiceAmount - earlyDiscount; // Only subtract early discount from invoice total
                const remainingBalance = discountedTotal - baseAmount - customDiscounts; // Subtract down payment and custom discounts
                const monthlyAmount = remainingBalance / 9; // 9 months installment
                
                if (totalDisplay) {
                    totalDisplay.textContent = `Remaining: ₱${remainingBalance.toFixed(2)} / 9 months = ₱${monthlyAmount.toFixed(2)}`;
                }
                if (finalAmountInput) {
                    finalAmountInput.value = remainingBalance; // Store remaining balance for backend (preview only)
                }
            } else {
                // For monthly installments or one-time payments: show total with discounts applied
                if (totalDisplay) {
                    if (!hasPaymentPlan) {
                        // For one-time payments: show invoice total after all discounts
                        // Formula: Total Invoice - Early Discount - Custom Discounts
                        const totalInvoiceAmount = {{ $invoice->total_amount }};
                        const discountedTotal = totalInvoiceAmount - earlyDiscount - customDiscounts;
                        totalDisplay.textContent = `Total to Pay: ₱${Math.max(0, discountedTotal).toFixed(2)}`;
                    } else {
                        // For monthly installments (non-down payment): show payment amount only
                        totalDisplay.textContent = `Total: ₱${baseAmount.toFixed(2)}`;
                    }
                }
                if (finalAmountInput) {
                    finalAmountInput.value = baseAmount; // Send payment amount to backend
                }
            }
        }
        
        function getDiscountAmount(discountId, baseAmount = null) {
            // Get discount amount from the label
            const label = document.querySelector(`label[for="discount-${discountId}"]`);
            if (label) {
                const h4Element = label.querySelector('h4');
                if (h4Element) {
                    const amountText = h4Element.textContent.replace('Amount: ', '');
                    if (amountText.includes('%')) {
                        const percentage = parseFloat(amountText.replace('%', ''));
                        // Use baseAmount (invoice total) if provided, otherwise use payment amount
                        const calculationBase = baseAmount !== null ? baseAmount : (parseFloat(amountInput.value) || 0);
                        return calculationBase * (percentage / 100);
                    } else {
                        // Fixed amount discount
                        return parseFloat(amountText.replace('₱', '').replace(',', '')) || 0;
                    }
                }
            }
            return 0;
        }
        
        // Clear form fields on page load/refresh
        function clearForm() {
            const hasPaymentPlan = {{ $invoice->has_payment_plan ? 'true' : 'false' }};
            
            // For one-time payments, set the amount to total invoice amount and lock it
            if (!hasPaymentPlan && amountInput) {
                const totalAmount = {{ $invoice->total_amount }};
                amountInput.value = totalAmount;
                amountInput.readOnly = true;
            } else if (amountInput) {
                // For installment plans, clear the amount
                amountInput.value = '';
            }
            
            // Clear method and type inputs
            const methodInput = document.querySelector('input[name="method"]');
            const typeInput = document.querySelector('input[name="type"]');
            if (methodInput) methodInput.value = '';
            if (typeInput) typeInput.value = '';
            
            // Clear reference number
            const referenceInput = document.querySelector('input[name="reference_no"]');
            if (referenceInput) referenceInput.value = '';
            
            // Reset payment date to today
            const paymentDateInput = document.querySelector('input[name="payment_date"]');
            if (paymentDateInput) {
                const today = new Date().toISOString().split('T')[0];
                paymentDateInput.value = today;
            }
            
            // Reset schedule selection to first option
            if (scheduleSelect) {
                scheduleSelect.selectedIndex = 0;
            }
            
            // Clear discount selections
            if (customDiscountCheckbox) {
                customDiscountCheckbox.checked = false;
            }
            if (customDiscountOptions) {
                customDiscountOptions.style.display = 'none';
            }
            discountCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Clear final amount input
            if (finalAmountInput) {
                finalAmountInput.value = '0';
            }
            
            // Reset total display
            if (totalDisplay) {
                totalDisplay.textContent = 'Total: ₱0.00';
            }
        }
        
        // Initialize form clearing and total calculation on page load
        clearForm();
        updateTotal();
    </script>

    <style>
        /* Bottom navigation scroll animation styles */
        #bottom-nav {
            background: rgba(26, 49, 101, 0.9);
            backdrop-filter: blur(16px);
        }

        #bottom-nav.at-bottom {
            background: transparent;
            backdrop-filter: blur(0px);
        }
    </style>

    <script>
        // Bottom navigation scroll animation (reverse of header behavior)
        document.addEventListener('DOMContentLoaded', function() {
            const bottomNav = document.getElementById('bottom-nav');
            
            if (!bottomNav) return;

            // Find the scrollable container (the white modal with overflow-auto)
            const scrollContainer = bottomNav.closest('.overflow-auto');
            
            if (!scrollContainer) return;

            let ticking = false;

            function updateBottomNav() {
                const scrollTop = scrollContainer.scrollTop;
                const containerHeight = scrollContainer.clientHeight;
                const scrollHeight = scrollContainer.scrollHeight;
                
                // Calculate distance from bottom (reverse of scrollTop)
                const scrollBottom = scrollHeight - (scrollTop + containerHeight);

                if (scrollBottom < 100) {
                    // Near bottom - make transparent
                    bottomNav.classList.add('at-bottom');
                } else {
                    // Not at bottom - show background
                    bottomNav.classList.remove('at-bottom');
                }

                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateBottomNav);
                    ticking = true;
                }
            }

            // Listen for scroll events on the CONTAINER, not window
            scrollContainer.addEventListener('scroll', requestTick, {
                passive: true
            });

            // Initial check
            updateBottomNav();
        });
    </script>
@endpush
