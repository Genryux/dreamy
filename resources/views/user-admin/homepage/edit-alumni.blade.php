@extends('layouts.admin', ['title' => 'Edit Alumni Section'])

@section('breadcrumbs')
    <nav aria-label="Breadcrumb" class="flex flex-row justify-between items-center mb-2 mt-2">
        <ol class="flex items-center gap-1 text-sm text-gray-700">
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-180" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="/admin" class="block transition-colors hover:text-gray-900">Admin</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <a href="/admin/homepage" class="block transition-colors hover:text-gray-900">Homepage Manager</a>
            </li>
            <li class="rtl:rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </li>
            <li>
                <span class="block text-gray-900">Edit Alumni Section</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <h2 class="text-3xl font-bold text-gray-900">Edit Alumni Success Stories</h2>
    <p class="text-gray-600 mt-1">Manage alumni testimonials displayed on the homepage</p>
@endsection

@section('modal')
    <!-- Delete Confirmation Modal -->
    <x-modal modal_id="delete-item-modal" modal_name="Confirm Deletion" close_btn_id="delete-item-modal-close-btn"
        modal_container_id="delete-item-modal-container">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-600'></i>
        </x-slot>
        
        <div class="p-6">
            <p class="text-gray-700">Are you sure you want to remove this alumni? This action cannot be undone.</p>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-item-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="delete-item-confirm-btn"
                class="bg-red-600 text-white text-[14px] px-3 py-2 rounded-xl font-bold shadow-sm hover:bg-red-700 hover:ring hover:ring-red-200 transition duration-200">
                Delete Alumni
            </button>
        </x-slot>
    </x-modal>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.homepage.alumni.update') }}" method="POST" enctype="multipart/form-data" id="alumniForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Edit Form -->
                <div class="space-y-6">
                    <!-- Section Settings Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Section Settings</h2>
                        
                        <!-- Heading -->
                        <div class="mb-4">
                            <label for="heading" class="block text-sm font-medium text-gray-700 mb-2">Section Heading *</label>
                            <input type="text" 
                                   name="heading" 
                                   id="heading" 
                                   value="{{ old('heading', $section->heading) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                   required>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Section Description *</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                      required>{{ old('description', $section->description) }}</textarea>
                        </div>

                        <!-- Background Image -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Background Image</label>
                            @if($section->background_image)
                                <div class="mb-2 p-2 bg-gray-100 rounded flex items-center space-x-2">
                                    <img src="{{ asset('storage/' . $section->background_image) }}" alt="Current background" class="w-20 h-12 object-cover rounded">
                                    <span class="text-xs text-gray-600">Current background</span>
                                </div>
                            @endif
                            <input type="file" 
                                   name="background_image" 
                                   accept="image/*"
                                   class="w-full text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 file:mr-2 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:bg-cyan-50 file:text-cyan-700">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 1920x1080px. Leave empty to keep current image.</p>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Display this section on the homepage
                            </label>
                        </div>
                    </div>

                    <!-- Alumni Items Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Alumni Profiles</h2>
                            <button type="button" 
                                    onclick="addNewItem()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Alumni
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            @foreach($section->items as $index => $item)
                                @include('user-admin.homepage.partials.alumni-item', ['item' => $item, 'index' => $index])
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            <strong>Photos:</strong> Upload circular photos (recommended: 200x200px)
                            <br>
                            <strong>Quote:</strong> Include their current achievement and a testimonial
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                <!-- Right Column: Live Preview -->
                <div class="lg:sticky lg:top-8 h-fit">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h2>
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div id="preview" class="relative bg-[#C8A165] p-6">
                                <div class="text-center mb-6">
                                    <h2 id="preview-heading" class="font-bold text-xl text-white mb-2">{{ $section->heading }}</h2>
                                    <div class="bg-white w-[60px] h-[3px] mx-auto mb-3"></div>
                                    <p id="preview-description" class="text-sm text-white/90">{{ $section->description }}</p>
                                </div>
                                <div id="preview-items" class="grid grid-cols-1 gap-3">
                                    @php
                                        $fallbackPhotos = ['images/alumni1.jpg', 'images/alumni2.jpg', 'images/alumni3.jpg'];
                                    @endphp
                                    @foreach($section->items as $index => $item)
                                        @php
                                            $photoUrl = $item->photo ? asset('storage/' . $item->photo) : asset($fallbackPhotos[$index % count($fallbackPhotos)]);
                                        @endphp
                                        <div class="bg-white rounded-lg shadow p-3 flex items-center text-center flex-col"
                                             data-item-id="{{ $item->id }}">
                                            <img src="{{ $photoUrl }}" class="w-12 h-12 rounded-full mb-2 object-cover" alt="{{ $item->name }}">
                                            <h3 class="text-sm font-bold text-[#1A3165]">{{ $item->name }}</h3>
                                            <p class="text-[#C8A165] text-[10px] mb-1">{{ $item->getClassInfo() }}</p>
                                            <p class="text-gray-600 text-[10px] line-clamp-2">{{ Str::limit($item->quote, 60) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Item Template (hidden) -->
<template id="itemTemplate">
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 item-card" data-item-index="INDEX">
        <div class="flex items-start justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Alumni #<span class="item-number">INDEX</span></h3>
            <button type="button" 
                    onclick="removeItem(this)"
                    class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <input type="hidden" name="items[INDEX][id]" value="">
        
        <div class="space-y-3">
            <!-- Name -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
                <input type="text" 
                       name="items[INDEX][name]" 
                       class="item-name w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                       placeholder="e.g., John Doe"
                       required>
            </div>

            <!-- Class Year & Track -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Class Year</label>
                    <input type="text" 
                           name="items[INDEX][class_year]" 
                           class="item-class-year w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                           placeholder="Class of 2024">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Track/Strand</label>
                    <input type="text" 
                           name="items[INDEX][track]" 
                           class="item-track w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                           placeholder="e.g., STEM, ABM">
                </div>
            </div>

            <!-- Quote -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Quote/Testimonial *</label>
                <textarea name="items[INDEX][quote]" 
                          class="item-quote w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                          rows="3"
                          placeholder="Their achievement and testimonial..."
                          required></textarea>
            </div>

            <!-- Photo & Order -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Photo</label>
                    <input type="file" 
                           name="items[INDEX][photo]" 
                           accept="image/*"
                           class="item-photo w-full text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-cyan-50 file:text-cyan-700">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                    <input type="number" 
                           name="items[INDEX][order]" 
                           class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-cyan-500"
                           value="1"
                           min="1"
                           required>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="module">
import { initModal } from "/js/modal.js";

let itemIndex = {{ count($section->items) }};
let itemToRemove = null;

// Initialize delete modal
document.addEventListener('DOMContentLoaded', function() {
    initModal('delete-item-modal', null, 'delete-item-modal-close-btn', 'delete-item-modal-container', 'delete-item-cancel-btn');
});

// Update preview when inputs change
document.getElementById('heading').addEventListener('input', function() {
    document.getElementById('preview-heading').textContent = this.value;
});

document.getElementById('description').addEventListener('input', function() {
    document.getElementById('preview-description').textContent = this.value;
});

// Update preview for items
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('item-name') || 
        e.target.classList.contains('item-class-year') ||
        e.target.classList.contains('item-track') ||
        e.target.classList.contains('item-quote')) {
        updateItemPreviews();
    }
});

function updateItemPreviews() {
    const previewItems = document.getElementById('preview-items');
    const items = document.querySelectorAll('.item-card');
    
    const fallbackPhotos = [
        '{{ asset("images/alumni1.jpg") }}',
        '{{ asset("images/alumni2.jpg") }}',
        '{{ asset("images/alumni3.jpg") }}'
    ];
    
    let previewHTML = '';
    items.forEach((item, index) => {
        const name = item.querySelector('.item-name').value;
        const classYear = item.querySelector('.item-class-year').value;
        const track = item.querySelector('.item-track').value;
        const quote = item.querySelector('.item-quote').value;
        const photoInput = item.querySelector('.item-photo');
        const existingPhotoInput = item.querySelector('.existing-photo');
        
        let photoUrl = fallbackPhotos[index % fallbackPhotos.length];
        
        // Check for existing photo
        if (existingPhotoInput && existingPhotoInput.value) {
            photoUrl = '{{ asset("storage") }}/' + existingPhotoInput.value;
        }
        
        // Check for new file upload
        if (photoInput && photoInput.files && photoInput.files[0]) {
            photoUrl = URL.createObjectURL(photoInput.files[0]);
        }
        
        // Build class info
        let classInfo = '';
        if (classYear) classInfo += classYear;
        if (classYear && track) classInfo += ' · ';
        if (track) classInfo += track;
        
        if (name || quote) {
            const truncatedQuote = quote.length > 60 ? quote.substring(0, 60) + '...' : quote;
            
            previewHTML += `
                <div class="bg-white rounded-lg shadow p-3 flex items-center text-center flex-col">
                    <img src="${photoUrl}" class="w-12 h-12 rounded-full mb-2 object-cover" alt="${name}">
                    <h3 class="text-sm font-bold text-[#1A3165]">${name}</h3>
                    <p class="text-[#C8A165] text-[10px] mb-1">${classInfo}</p>
                    <p class="text-gray-600 text-[10px] line-clamp-2">${truncatedQuote}</p>
                </div>
            `;
        }
    });
    
    previewItems.innerHTML = previewHTML;
}

// Handle photo preview
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('item-photo')) {
        updateItemPreviews();
    }
});

window.addNewItem = function() {
    itemIndex++;
    const template = document.getElementById('itemTemplate').innerHTML;
    const newItemHTML = template.replace(/INDEX/g, itemIndex).replace(/#<span class="item-number">.*?<\/span>/, `#${itemIndex}`);
    
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', newItemHTML);
    updateItemPreviews();
    
    // Scroll to the newly added item
    const container = document.getElementById('itemsContainer');
    const newItem = container.lastElementChild;
    if (newItem) {
        newItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

window.removeItem = function(button) {
    itemToRemove = button.closest('.item-card');
    // Open modal
    const modalContainer = document.getElementById('delete-item-modal-container');
    const modal = document.getElementById('delete-item-modal');
    
    modalContainer.classList.remove('opacity-0', 'pointer-events-none');
    modalContainer.classList.add('opacity-100', 'pointer-events-auto');
    
    modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
    modal.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
}

// Confirm deletion
document.getElementById('delete-item-confirm-btn').addEventListener('click', function() {
    if (itemToRemove) {
        itemToRemove.remove();
        updateItemPreviews();
        renumberItems();
        itemToRemove = null;
    }
    
    // Close modal
    const modalContainer = document.getElementById('delete-item-modal-container');
    const modal = document.getElementById('delete-item-modal');
    
    modalContainer.classList.add('opacity-0', 'pointer-events-none');
    modalContainer.classList.remove('opacity-100', 'pointer-events-auto');
    
    modal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
    modal.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
});

function renumberItems() {
    const items = document.querySelectorAll('.item-card');
    items.forEach((item, index) => {
        const number = index + 1;
        const numberSpan = item.querySelector('.item-number');
        if (numberSpan) {
            numberSpan.textContent = number;
        }
    });
}
</script>
@endsection
