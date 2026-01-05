<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 item-card" data-item-index="{{ $index }}">
    <div class="flex items-start justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700">Program #<span class="item-number">{{ $index + 1 }}</span></h3>
        <button type="button" 
                onclick="removeItem(this)"
                class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id ?? '' }}">
    
    <div class="space-y-3">
        <!-- Title -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Program Title *</label>
            <input type="text" 
                   name="items[{{ $index }}][title]" 
                   value="{{ old('items.'.$index.'.title', $item->title ?? '') }}"
                   class="item-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
            <textarea name="items[{{ $index }}][description]" 
                      class="item-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                      rows="2"
                      required>{{ old('items.'.$index.'.description', $item->description ?? '') }}</textarea>
        </div>

        <!-- Track Name & Status -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Track Name</label>
                <input type="text" 
                       name="items[{{ $index }}][track_name]" 
                       value="{{ old('items.'.$index.'.track_name', $item->track_name ?? '') }}"
                       class="item-track-name w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., STEM, ABM">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                <select name="items[{{ $index }}][status]" 
                        class="item-status w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <option value="active" {{ old('items.'.$index.'.status', $item->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="coming_soon" {{ old('items.'.$index.'.status', $item->status ?? '') === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                </select>
            </div>
        </div>

        <!-- Gradient Colors -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Gradient From *</label>
                <input type="color" 
                       name="items[{{ $index }}][gradient_from]" 
                       value="{{ old('items.'.$index.'.gradient_from', $item->gradient_from ?? '#1A3165') }}"
                       class="item-gradient-from w-full h-9 border border-gray-300 rounded cursor-pointer"
                       required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Gradient To *</label>
                <input type="color" 
                       name="items[{{ $index }}][gradient_to]" 
                       value="{{ old('items.'.$index.'.gradient_to', $item->gradient_to ?? '#2A4A7A') }}"
                       class="item-gradient-to w-full h-9 border border-gray-300 rounded cursor-pointer"
                       required>
            </div>
        </div>

        <!-- Link URL & Order -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Link URL</label>
                <input type="url" 
                       name="items[{{ $index }}][link_url]" 
                       value="{{ old('items.'.$index.'.link_url', $item->link_url ?? '') }}"
                       class="item-link-url w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="https://...">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                <input type="number" 
                       name="items[{{ $index }}][order]" 
                       value="{{ old('items.'.$index.'.order', $item->order ?? 1) }}"
                       class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       min="1"
                       required>
            </div>
        </div>
    </div>
</div>
