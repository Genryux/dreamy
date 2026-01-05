<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 item-card" data-item-index="{{ $index }}">
    <div class="flex items-start justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700">Statistic #<span class="item-number">{{ $index + 1 }}</span></h3>
        <button type="button" 
                onclick="removeItem(this)"
                class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
    
    <div class="space-y-3">
        <!-- Value & Order -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Value *</label>
                <input type="text" 
                       name="items[{{ $index }}][value]" 
                       value="{{ old('items.' . $index . '.value', $item->value) }}"
                       class="item-value w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="500+"
                       required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Order *</label>
                <input type="number" 
                       name="items[{{ $index }}][order]" 
                       value="{{ old('items.' . $index . '.order', $item->order) }}"
                       class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       min="1"
                       required>
            </div>
        </div>

        <!-- Label -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Label *</label>
            <input type="text" 
                   name="items[{{ $index }}][label]" 
                   value="{{ old('items.' . $index . '.label', $item->label) }}"
                   class="item-label w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Active Students"
                   required>
        </div>

        <!-- Colors -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Background Color *</label>
                <input type="color" 
                       name="items[{{ $index }}][bg_color]" 
                       value="{{ old('items.' . $index . '.bg_color', $item->bg_color) }}"
                       class="item-bg-color w-full h-9 border border-gray-300 rounded cursor-pointer"
                       required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Text Color *</label>
                <input type="color" 
                       name="items[{{ $index }}][text_color]" 
                       value="{{ old('items.' . $index . '.text_color', $item->text_color) }}"
                       class="item-text-color w-full h-9 border border-gray-300 rounded cursor-pointer"
                       required>
            </div>
        </div>
    </div>
</div>
