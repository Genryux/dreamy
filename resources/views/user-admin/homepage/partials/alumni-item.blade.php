<div class="bg-gray-50 border border-gray-200 rounded-lg p-4 item-card" data-item-index="{{ $index }}">
    <div class="flex items-start justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-700">Alumni #<span class="item-number">{{ $index + 1 }}</span></h3>
        <button type="button" 
                onclick="removeItem(this)"
                class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id ?? '' }}">
    <input type="hidden" name="items[{{ $index }}][existing_photo]" value="{{ $item->photo ?? '' }}" class="existing-photo">
    
    <div class="space-y-3">
        <!-- Name -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
            <input type="text" 
                   name="items[{{ $index }}][name]" 
                   value="{{ old('items.'.$index.'.name', $item->name ?? '') }}"
                   class="item-name w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                   placeholder="e.g., John Doe"
                   required>
        </div>

        <!-- Class Year & Track -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Class Year</label>
                <input type="text" 
                       name="items[{{ $index }}][class_year]" 
                       value="{{ old('items.'.$index.'.class_year', $item->class_year ?? '') }}"
                       class="item-class-year w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                       placeholder="Class of 2024">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Track/Strand</label>
                <input type="text" 
                       name="items[{{ $index }}][track]" 
                       value="{{ old('items.'.$index.'.track', $item->track ?? '') }}"
                       class="item-track w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                       placeholder="e.g., STEM, ABM">
            </div>
        </div>

        <!-- Quote -->
        <div>
            <label class="block text-xs font-medium text-gray-700 mb-1">Quote/Testimonial *</label>
            <textarea name="items[{{ $index }}][quote]" 
                      class="item-quote w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                      rows="3"
                      placeholder="Their achievement and testimonial..."
                      required>{{ old('items.'.$index.'.quote', $item->quote ?? '') }}</textarea>
        </div>

        <!-- Current Photo Preview (if exists) -->
        @if($item->photo)
            <div class="flex items-center space-x-2 p-2 bg-gray-100 rounded">
                <img src="{{ asset('storage/' . $item->photo) }}" alt="Current photo" class="w-12 h-12 object-cover rounded-full">
                <span class="text-xs text-gray-600">Current photo</span>
            </div>
        @endif

        <!-- Photo & Order -->
        <div class="grid grid-cols-2 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">{{ $item->photo ? 'Replace Photo' : 'Photo' }}</label>
                <input type="file" 
                       name="items[{{ $index }}][photo]" 
                       accept="image/*"
                       class="item-photo w-full text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-cyan-50 file:text-cyan-700">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                <input type="number" 
                       name="items[{{ $index }}][order]" 
                       value="{{ old('items.'.$index.'.order', $item->order ?? 1) }}"
                       class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                       min="1"
                       required>
            </div>
        </div>
    </div>
</div>
