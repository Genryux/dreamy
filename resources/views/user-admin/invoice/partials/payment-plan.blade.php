{{-- Payment Plan Section --}}
@if($invoice->has_payment_plan && $paymentPlanSummary)
<div class="mt-8">
	<div class="flex items-center justify-between mb-4">
		<p class="font-semibold text-lg">Payment Plan</p>
		<span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Installment Plan</span>
	</div>

	{{-- Payment Plan Summary --}}
	<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
		<div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
			<p class="text-xs text-blue-600 font-medium mb-1">Down Payment</p>
			<p class="text-2xl font-bold text-blue-700">₱{{ number_format($paymentPlanSummary['down_payment'], 2) }}</p>
		</div>
		<div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
			<p class="text-xs text-green-600 font-medium mb-1">Monthly Amount</p>
			<p class="text-2xl font-bold text-green-700">₱{{ number_format($paymentPlanSummary['monthly_amount'], 2) }}</p>
		</div>
		<div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
			<p class="text-xs text-purple-600 font-medium mb-1">First Month</p>
			<p class="text-2xl font-bold text-purple-700">₱{{ number_format($paymentPlanSummary['first_month_amount'], 2) }}</p>
		</div>
		<div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
			<p class="text-xs text-orange-600 font-medium mb-1">Total Months</p>
			<p class="text-2xl font-bold text-orange-700">{{ $paymentPlanSummary['installment_months'] }}</p>
		</div>
	</div>

	{{-- Payment Schedule Table --}}
	<div class="rounded-lg border border-gray-200 overflow-hidden">
		<table class="min-w-full divide-y divide-gray-200 text-sm">
			<thead class="bg-gray-50">
				<tr>
					<th class="px-4 py-3 text-left font-semibold text-gray-700">Installment</th>
					<th class="px-4 py-3 text-left font-semibold text-gray-700">Description</th>
					<th class="px-4 py-3 text-left font-semibold text-gray-700">Due Date</th>
					<th class="px-4 py-3 text-right font-semibold text-gray-700">Amount Due</th>
					<th class="px-4 py-3 text-right font-semibold text-gray-700">Amount Paid</th>
					<th class="px-4 py-3 text-center font-semibold text-gray-700">Status</th>
					<th class="px-4 py-3 text-center font-semibold text-gray-700">Actions</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-100">
				@foreach($paymentPlanSummary['schedules'] as $schedule)
				<tr class="{{ $schedule->status === 'overdue' ? 'bg-red-50' : '' }}">
					<td class="px-4 py-3">
						{{ $schedule->installment_number === 0 ? 'Down' : '#' . $schedule->installment_number }}
					</td>
					<td class="px-4 py-3">{{ $schedule->description }}</td>
					<td class="px-4 py-3">
						{{ $schedule->due_date ? \Carbon\Carbon::parse($schedule->due_date)->format('M d, Y') : '-' }}
					</td>
					<td class="px-4 py-3 text-right font-medium">₱{{ number_format($schedule->amount_due, 2) }}</td>
					<td class="px-4 py-3 text-right font-medium">₱{{ number_format($schedule->amount_paid, 2) }}</td>
					<td class="px-4 py-3 text-center">
						@if($schedule->status === 'paid')
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-check-circle"></i> Paid
							</span>
						@elseif($schedule->status === 'partial')
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-time-half-past"></i> Partial
							</span>
						@elseif($schedule->status === 'overdue')
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-exclamation"></i> Overdue
							</span>
						@else
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-clock"></i> Pending
							</span>
						@endif
					</td>
					<td class="px-4 py-3 text-center">
						<div class="flex items-center justify-center gap-2">
							{{-- Invoice button - Always available for all schedules --}}
							<a href="{{ route('invoice.schedule.download', ['invoice' => $invoice->id, 'schedule' => $schedule->id]) }}" 
							   class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium hover:bg-blue-200 transition">
								<i class="fi fi-sr-document"></i>
								Invoice
							</a>
							
							@if($schedule->status === 'paid' || $schedule->status === 'partial')
								{{-- Receipt button - Only for paid/partial schedules --}}
								<a href="{{ route('invoice.schedule.receipt', ['invoice' => $invoice->id, 'schedule' => $schedule->id]) }}" 
								   class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium hover:bg-green-200 transition">
									<i class="fi fi-sr-receipt"></i>
									Receipt
								</a>
							@endif
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot class="bg-gray-50 font-semibold">
				<tr>
					<td colspan="3" class="px-4 py-3 text-right">Total:</td>
					<td class="px-4 py-3 text-right">₱{{ number_format($paymentPlanSummary['total_amount'], 2) }}</td>
					<td class="px-4 py-3 text-right">₱{{ number_format($invoice->paid_amount, 2) }}</td>
					<td class="px-4 py-3"></td>
					<td class="px-4 py-3"></td>
				</tr>
			</tfoot>
		</table>
	</div>

	{{-- Next Due Info --}}
	@if($paymentPlanSummary['next_due'])
	<div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
		<div class="flex items-start gap-3">
			<i class="fi fi-sr-calendar text-blue-600 text-xl flex items-center"></i>
			<div>
				<p class="font-semibold text-blue-900">Next Payment Due</p>
				<p class="text-sm text-blue-700 mt-1">
					{{ $paymentPlanSummary['next_due']->description }} - 
					₱{{ number_format($paymentPlanSummary['next_due']->remaining, 2) }} 
					due on {{ \Carbon\Carbon::parse($paymentPlanSummary['next_due']->due_date)->format('M d, Y') }}
				</p>
			</div>
		</div>
	</div>
	@endif
</div>
@else
{{-- Payment Option Not Selected --}}
@if(!$invoice->payment_mode || $invoice->payment_mode === 'flexible')
<div class="mt-8">
	<div class="bg-gradient-to-r from-gray-50 to-slate-50 border border-gray-200 rounded-xl p-6">
		<div class="flex items-start gap-4">
			<div class="flex-shrink-0">
				<div class="w-12 h-12 bg-gray-500 rounded-full flex items-center justify-center">
					<i class="fi fi-sr-clock text-white text-xl"></i>
				</div>
			</div>
			<div class="flex-1">
				<h3 class="text-lg font-bold text-gray-900 mb-2">Payment Option Required</h3>
				<p class="text-sm text-gray-600 mb-4">
					This student has not yet selected a payment option. They need to choose between one-time payment or monthly installment plan through the mobile app.
				</p>
				<div class="flex items-center gap-2 text-sm text-gray-600">
					<i class="fi fi-sr-info"></i>
					<span>Waiting for student to select payment option...</span>
				</div>
			</div>
		</div>
	</div>
</div>
@elseif($invoice->payment_mode === 'full')
{{-- One-Time Payment Selected --}}
<div class="mt-8">
	<div class="flex items-center justify-between mb-4">
		<p class="font-semibold text-lg">Payment Plan</p>
		<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">One-Time Payment</span>
	</div>

	{{-- One-Time Payment Summary --}}
	<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
		<div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
			<p class="text-xs text-green-600 font-medium mb-1">Total Amount</p>
			<p class="text-2xl font-bold text-green-700">₱{{ number_format($invoice->total_amount, 2) }}</p>
		</div>
		<div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
			<p class="text-xs text-blue-600 font-medium mb-1">Amount Paid</p>
			<p class="text-2xl font-bold text-blue-700">₱{{ number_format($invoice->paid_amount, 2) }}</p>
		</div>
		<div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
			<p class="text-xs text-orange-600 font-medium mb-1">Remaining Balance</p>
			<p class="text-2xl font-bold text-orange-700">₱{{ number_format($invoice->balance, 2) }}</p>
		</div>
	</div>

	{{-- One-Time Payment Status --}}
	<div class="rounded-lg border border-gray-200 overflow-hidden">
		<table class="min-w-full divide-y divide-gray-200 text-sm">
			<thead class="bg-gray-50">
				<tr>
					<th class="px-4 py-3 text-left font-semibold text-gray-700">Payment Type</th>
					<th class="px-4 py-3 text-left font-semibold text-gray-700">Description</th>
					<th class="px-4 py-3 text-right font-semibold text-gray-700">Amount Due</th>
					<th class="px-4 py-3 text-right font-semibold text-gray-700">Amount Paid</th>
					<th class="px-4 py-3 text-center font-semibold text-gray-700">Status</th>
					<th class="px-4 py-3 text-center font-semibold text-gray-700">Actions</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-100">
				<tr>
					<td class="px-4 py-3">Full Payment</td>
					<td class="px-4 py-3">Complete invoice payment</td>
					<td class="px-4 py-3 text-right font-medium">₱{{ number_format($invoice->total_amount, 2) }}</td>
					<td class="px-4 py-3 text-right font-medium">₱{{ number_format($invoice->paid_amount, 2) }}</td>
					<td class="px-4 py-3 text-center">
						@if($invoice->balance == 0)
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-check-circle"></i> Paid
							</span>
						@elseif($invoice->paid_amount > 0)
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-time-half-past"></i> Partial
							</span>
						@else
							<span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
								<i class="fi fi-sr-clock"></i> Pending
							</span>
						@endif
					</td>
					<td class="px-4 py-3 text-center">
						<div class="flex items-center justify-center gap-2">
							{{-- Invoice button - Always available --}}
							<a href="{{ route('invoice.onetime.download', ['invoice' => $invoice->id]) }}" 
							   class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium hover:bg-blue-200 transition">
								<i class="fi fi-sr-document"></i>
								Invoice
							</a>
							
							@if($invoice->paid_amount > 0)
								{{-- Receipt button - Only for paid/partial payments --}}
								<a href="{{ route('invoice.onetime.receipt', ['invoice' => $invoice->id]) }}" 
								   class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium hover:bg-green-200 transition">
									<i class="fi fi-sr-receipt"></i>
									Receipt
								</a>
							@endif
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	{{-- Payment Status Info --}}
	@if($invoice->balance > 0)
	<div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
		<div class="flex items-start gap-3">
			<i class="fi fi-sr-info text-blue-600 text-xl flex items-center"></i>
			<div>
				<p class="font-semibold text-blue-900">Payment Required</p>
				<p class="text-sm text-blue-700 mt-1">
					Student has chosen one-time payment. Remaining balance: ₱{{ number_format($invoice->balance, 2) }}
				</p>
			</div>
		</div>
	</div>
	@else
	<div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
		<div class="flex items-start gap-3">
			<i class="fi fi-sr-check-circle text-green-600 text-xl flex items-center"></i>
			<div>
				<p class="font-semibold text-green-900">Payment Complete</p>
				<p class="text-sm text-green-700 mt-1">
					Student has completed their one-time payment in full.
				</p>
			</div>
		</div>
	</div>
	@endif
</div>
@elseif($invoice->payment_mode === 'installment' && !$invoice->has_payment_plan)
{{-- Installment Selected but No Plan Created Yet --}}
<div class="mt-8">
	<div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
		<div class="flex items-start gap-4">
			<div class="flex-shrink-0">
				<div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
					<i class="fi fi-sr-calendar-clock text-white text-xl"></i>
				</div>
			</div>
			<div class="flex-1">
				<h3 class="text-lg font-bold text-gray-900 mb-2">Monthly Installment Selected</h3>
				<p class="text-sm text-gray-600 mb-4">
					This student has chosen monthly installment payment. The payment plan will be automatically created when they confirm their choice in the mobile app.
				</p>
				<div class="flex items-center gap-2 text-sm text-blue-700">
					<i class="fi fi-sr-clock"></i>
					<span>Waiting for student to confirm installment plan...</span>
				</div>
			</div>
		</div>
	</div>
</div>
@endif

{{-- Create Payment Plan Modal --}}
<div id="create-payment-plan-modal" class="fixed inset-0 bg-black/30 flex items-center justify-center opacity-0 pointer-events-none transition z-50">
	<div class="bg-white w-11/12 md:w-[600px] rounded-xl shadow-lg">
		<div class="px-6 py-4 border-b">
			<p class="font-semibold text-lg">Create Payment Plan</p>
		</div>
		<form id="create-payment-plan-form" method="POST" action="{{ route('invoice.payment-plan.store', ['invoice' => $invoice->id]) }}" class="px-6 py-4">
			@csrf
			<div class="flex flex-col gap-4">
				<div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
					<p class="text-sm font-semibold text-blue-900 mb-2">Invoice Total</p>
					<p class="text-3xl font-bold text-blue-600">₱{{ number_format($invoice->total_amount, 2) }}</p>
				</div>

				<div class="flex flex-col">
					<label for="down_payment" class="text-sm font-semibold mb-1">Down Payment Amount</label>
					<input type="number" step="0.01" min="0" max="{{ $invoice->total_amount }}" name="down_payment" id="down_payment" class="border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter down payment" required />
					<p class="text-xs text-gray-500 mt-1">Amount paid during enrollment</p>
				</div>

				<div class="flex flex-col">
					<label for="installment_months" class="text-sm font-semibold mb-1">Number of Months</label>
					<input type="number" min="1" max="12" name="installment_months" id="installment_months" value="9" class="border border-gray-300 rounded-lg px-3 py-2" required />
					<p class="text-xs text-gray-500 mt-1">Fixed installment period (default: 9 months)</p>
				</div>

				{{-- Calculation Preview --}}
				<div id="calculation-preview" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
					<p class="text-sm font-semibold mb-3">Payment Schedule Preview</p>
					<div class="space-y-2 text-sm">
						<div class="flex justify-between">
							<span class="text-gray-600">Down Payment:</span>
							<span class="font-semibold" id="preview-down-payment">-</span>
						</div>
						<div class="flex justify-between">
							<span class="text-gray-600">Remaining Amount:</span>
							<span class="font-semibold" id="preview-remaining">-</span>
						</div>
						<div class="flex justify-between">
							<span class="text-gray-600">First Month Payment:</span>
							<span class="font-semibold text-blue-600" id="preview-first-month">-</span>
						</div>
						<div class="flex justify-between">
							<span class="text-gray-600">Monthly Payment (2-9):</span>
							<span class="font-semibold text-green-600" id="preview-monthly">-</span>
						</div>
					</div>
				</div>

				<button type="button" id="calculate-plan-btn" class="w-full bg-gray-100 text-gray-700 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition">
					Calculate Preview
				</button>
			</div>
			<div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t">
				<button type="button" id="create-payment-plan-cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</button>
				<button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-500">Create Plan</button>
			</div>
		</form>
	</div>
</div>

<script>
// Modal handlers
document.getElementById('create-payment-plan-btn')?.addEventListener('click', function() {
	const modal = document.getElementById('create-payment-plan-modal');
	modal.classList.remove('opacity-0', 'pointer-events-none');
});

document.getElementById('create-payment-plan-cancel')?.addEventListener('click', function() {
	const modal = document.getElementById('create-payment-plan-modal');
	modal.classList.add('opacity-0', 'pointer-events-none');
});

// Calculate preview
document.getElementById('calculate-plan-btn')?.addEventListener('click', async function() {
	const totalAmount = {{ $invoice->total_amount }};
	const downPayment = parseFloat(document.getElementById('down_payment').value);
	const installmentMonths = parseInt(document.getElementById('installment_months').value);

	if (!downPayment || downPayment < 0 || downPayment > totalAmount) {
		alert('Please enter a valid down payment amount.');
		return;
	}

	try {
		const response = await fetch('/payment-plan/calculate', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
			},
			body: JSON.stringify({
				total_amount: totalAmount,
				down_payment: downPayment,
				installment_months: installmentMonths
			})
		});

		const data = await response.json();
		
		document.getElementById('preview-down-payment').textContent = '₱' + Number(data.plan.down_payment_amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		document.getElementById('preview-remaining').textContent = '₱' + Number(data.plan.remaining_amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		document.getElementById('preview-first-month').textContent = '₱' + Number(data.plan.first_month_amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		document.getElementById('preview-monthly').textContent = '₱' + Number(data.plan.monthly_amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2});
		
		document.getElementById('calculation-preview').classList.remove('hidden');
	} catch (error) {
		console.error('Error calculating payment plan:', error);
		alert('Error calculating payment plan. Please try again.');
	}
});
</script>
@endif

