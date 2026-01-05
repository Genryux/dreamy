<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 item-card" data-item-index="{{ $index }}">
    <div class="flex items-start justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700">Item #<span class="item-number">{{ $index + 1 }}</span></h3>
        <button type="button" 
                onclick="removeItem(this)"
                class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id ?? '' }}">
    <input type="hidden" name="items[{{ $index }}][existing_image]" value="{{ $item->image ?? '' }}" class="existing-image">
    
    <div class="space-y-3">
        <!-- Title -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Title *</label>
            <input type="text" 
                   name="items[{{ $index }}][title]" 
                   value="{{ old('items.'.$index.'.title', $item->title ?? '') }}"
                   class="item-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500"
                   required>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
            <textarea name="items[{{ $index }}][description]" 
                      class="item-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500"
                      rows="2"
                      required>{{ old('items.'.$index.'.description', $item->description ?? '') }}</textarea>
        </div>

        <!-- Current Image Preview (if exists) -->
        @if($item->image)
            <div class="flex items-center space-x-2 p-2 bg-gray-100 rounded">
                <img src="{{ asset('storage/' . $item->image) }}" alt="Current image" class="w-16 h-12 object-cover rounded">
                <span class="text-xs text-gray-600">Current image</span>
            </div>
        @endif

        <!-- Image & Order -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">{{ $item->image ? 'Replace Image' : 'Image' }}</label>
                <input type="file" 
                       name="items[{{ $index }}][image]" 
                       accept="image/*"
                       class="item-image w-full text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-pink-50 file:text-pink-700">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                <input type="number" 
                       name="items[{{ $index }}][order]" 
                       value="{{ old('items.'.$index.'.order', $item->order ?? 1) }}"
                       class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500"
                       min="1"
                       required>
            </div>
        </div>
    </div>
</div>
