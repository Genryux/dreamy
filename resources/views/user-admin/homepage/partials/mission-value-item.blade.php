<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 item-card" data-item-index="{{ $index }}">
    <div class="flex items-start justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700">Value Item #<span class="item-number">{{ $index + 1 }}</span></h3>
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
        <!-- Title -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Title *</label>
            <input type="text" 
                   name="items[{{ $index }}][title]" 
                   value="{{ old('items.' . $index . '.title', $item->title) }}"
                   class="item-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
            <textarea name="items[{{ $index }}][description]" 
                      class="item-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                      rows="2"
                      required>{{ old('items.' . $index . '.description', $item->description) }}</textarea>
        </div>

        <!-- Icon & Color -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Icon Class *</label>
                <input type="text" 
                       name="items[{{ $index }}][icon]" 
                       value="{{ old('items.' . $index . '.icon', $item->icon) }}"
                       class="item-icon w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="fi fi-rr-bulb"
                       required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Color *</label>
                <input type="color" 
                       name="items[{{ $index }}][color]" 
                       value="{{ old('items.' . $index . '.color', $item->color) }}"
                       class="item-color w-full h-9 border border-gray-300 rounded cursor-pointer"
                       required>
            </div>
        </div>

        <!-- Order -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
            <input type="number" 
                   name="items[{{ $index }}][order]" 
                   value="{{ old('items.' . $index . '.order', $item->order) }}"
                   class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   min="1"
                   required>
        </div>
    </div>
</div>
