<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 step-card" data-step-index="{{ $index }}">
    <div class="flex items-start justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700">Step #<span class="step-number">{{ $index + 1 }}</span></h3>
        <button type="button" 
                onclick="removeStep(this)"
                class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <input type="hidden" name="steps[{{ $index }}][id]" value="{{ $step->id ?? '' }}">
    
    <div class="space-y-3">
        <!-- Step Number & Title -->
        <div class="grid grid-cols-3 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Step # *</label>
                <input type="number" 
                       name="steps[{{ $index }}][step_number]" 
                       value="{{ old('steps.'.$index.'.step_number', $step->step_number ?? $index + 1) }}"
                       class="step-num w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                       min="1"
                       required>
            </div>
            <div class="col-span-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" 
                       name="steps[{{ $index }}][title]" 
                       value="{{ old('steps.'.$index.'.title', $step->title ?? '') }}"
                       class="step-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="e.g., Submit Application"
                       required>
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
            <textarea name="steps[{{ $index }}][description]" 
                      class="step-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                      rows="2"
                      placeholder="Brief description of this step..."
                      required>{{ old('steps.'.$index.'.description', $step->description ?? '') }}</textarea>
        </div>

        <!-- Icon & Order -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Icon Class</label>
                <input type="text" 
                       name="steps[{{ $index }}][icon]" 
                       value="{{ old('steps.'.$index.'.icon', $step->icon ?? '') }}"
                       class="step-icon w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                       placeholder="fi-rr-form">
                <p class="text-[10px] text-gray-400 mt-0.5">Optional Flaticon class</p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                <input type="number" 
                       name="steps[{{ $index }}][order]" 
                       value="{{ old('steps.'.$index.'.order', $step->order ?? 1) }}"
                       class="step-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                       min="1"
                       required>
            </div>
        </div>
    </div>
</div>
