@extends('layouts.admin', ['title' => 'Edit Campus Tour Section'])

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
                <span class="block text-gray-900">Edit Campus Tour</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <h2 class="text-3xl font-bold text-gray-900">Edit Virtual Campus Tour</h2>
    <p class="text-gray-600 mt-1">Manage the campus tour carousel displayed on the homepage</p>
@endsection

@section('modal')
    <!-- Delete Confirmation Modal -->
    <x-modal modal_id="delete-item-modal" modal_name="Confirm Deletion" close_btn_id="delete-item-modal-close-btn"
        modal_container_id="delete-item-modal-container">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-600'></i>
        </x-slot>
        
        <div class="p-6">
            <p class="text-gray-700">Are you sure you want to remove this tour slide? This action cannot be undone.</p>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-item-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="delete-item-confirm-btn"
                class="bg-red-600 text-white text-[14px] px-3 py-2 rounded-xl font-bold shadow-sm hover:bg-red-700 hover:ring hover:ring-red-200 transition duration-200">
                Delete Slide
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

        <form action="{{ route('admin.homepage.campus-tour.update') }}" method="POST" enctype="multipart/form-data" id="campusTourForm">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                                   required>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Section Description *</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"
                                      required>{{ old('description', $section->description) }}</textarea>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Display this section on the homepage
                            </label>
                        </div>
                    </div>

                    <!-- Tour Items Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Tour Slides</h2>
                            <button type="button" 
                                    onclick="addNewItem()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Slide
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            @foreach($section->items as $index => $item)
                                @include('user-admin.homepage.partials.campus-tour-item', ['item' => $item, 'index' => $index])
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            <strong>Images:</strong> Upload landscape photos (recommended: 800x600px)
                            <br>
                            <strong>Icons:</strong> Use Flaticon class names (e.g., fi-rr-building, fi-rr-book)
                            <br>
                            <strong>Highlight:</strong> Short text shown on the slide badge (e.g., "Main Campus")
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
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
                            <div id="preview" class="relative bg-white p-4">
                                <div class="text-center mb-4">
                                    <h2 id="preview-heading" class="font-bold text-lg text-[#1A3165] mb-1">{{ $section->heading }}</h2>
                                    <div class="bg-[#C8A165] w-[60px] h-[2px] mx-auto mb-2"></div>
                                    <p id="preview-description" class="text-xs text-gray-600">{{ $section->description }}</p>
                                </div>
                                
                                <!-- Carousel Preview -->
                                <div id="preview-carousel" class="relative bg-gray-50 rounded-xl overflow-hidden">
                                    <div id="preview-slides" class="relative">
                                        @php
                                            $defaultImages = [
                                                'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=400&q=80',
                                                'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=400&q=80',
                                                'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=400&q=80',
                                            ];
                                        @endphp
                                        @foreach($section->items as $index => $item)
                                            @php
                                                $imageUrl = $item->image ? asset('storage/' . $item->image) : ($defaultImages[$index % count($defaultImages)]);
                                            @endphp
                                            <div class="preview-slide {{ $index === 0 ? 'block' : 'hidden' }}"
                                                 data-item-id="{{ $item->id }}">
                                                <div class="flex flex-col md:flex-row gap-3 p-3">
                                                    <div class="md:w-1/2">
                                                        <img src="{{ $imageUrl }}" class="w-full h-32 object-cover rounded-lg shadow" alt="{{ $item->title }}">
                                                    </div>
                                                    <div class="md:w-1/2 flex flex-col justify-center">
                                                        <h3 class="font-bold text-sm text-[#1A3165] mb-1">{{ $item->title }}</h3>
                                                        <p class="text-xs text-gray-600 mb-2 line-clamp-3">{{ $item->description }}</p>
                                                        <div class="flex items-center text-[10px] text-[#C8A165]">
                                                            <i class="fi {{ $item->icon ?? 'fi-rr-marker' }} mr-1"></i>
                                                            <span>{{ $item->highlight }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Preview Indicators -->
                                    <div id="preview-indicators" class="flex justify-center gap-1 pb-3">
                                        @foreach($section->items as $index => $item)
                                            <button type="button" 
                                                    class="preview-indicator w-2 h-2 rounded-full {{ $index === 0 ? 'bg-[#1A3165]' : 'bg-gray-300' }} transition-all"
                                                    data-slide="{{ $index }}"
                                                    onclick="showPreviewSlide({{ $index }})"></button>
                                        @endforeach
                                    </div>
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
            <h3 class="text-sm font-semibold text-gray-700">Slide #<span class="item-number">INDEX</span></h3>
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
            <!-- Title -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" 
                       name="items[INDEX][title]" 
                       class="item-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                       placeholder="e.g., Main Building"
                       required>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
                <textarea name="items[INDEX][description]" 
                          class="item-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                          rows="2"
                          placeholder="Brief description of this area..."
                          required></textarea>
            </div>

            <!-- Icon & Highlight -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Icon Class</label>
                    <input type="text" 
                           name="items[INDEX][icon]" 
                           class="item-icon w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="fi-rr-building">
                    <p class="text-[10px] text-gray-400 mt-0.5">Flaticon class name</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Highlight *</label>
                    <input type="text" 
                           name="items[INDEX][highlight]" 
                           class="item-highlight w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="e.g., Main Campus"
                           required>
                </div>
            </div>

            <!-- Image & Order -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" 
                           name="items[INDEX][image]" 
                           accept="image/*"
                           class="item-image w-full text-xs border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-teal-50 file:text-teal-700">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                    <input type="number" 
                           name="items[INDEX][order]" 
                           class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-teal-500"
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
let currentPreviewSlide = 0;

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
    if (e.target.classList.contains('item-title') || 
        e.target.classList.contains('item-description') ||
        e.target.classList.contains('item-icon') ||
        e.target.classList.contains('item-highlight')) {
        updateItemPreviews();
    }
});

// Show preview slide
window.showPreviewSlide = function(index) {
    currentPreviewSlide = index;
    const slides = document.querySelectorAll('.preview-slide');
    const indicators = document.querySelectorAll('.preview-indicator');
    
    slides.forEach((slide, i) => {
        slide.classList.toggle('hidden', i !== index);
        slide.classList.toggle('block', i === index);
    });
    
    indicators.forEach((indicator, i) => {
        indicator.classList.toggle('bg-[#1A3165]', i === index);
        indicator.classList.toggle('bg-gray-300', i !== index);
    });
}

function updateItemPreviews() {
    const previewSlides = document.getElementById('preview-slides');
    const previewIndicators = document.getElementById('preview-indicators');
    const items = document.querySelectorAll('.item-card');
    
    const defaultImages = [
        'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=400&q=80',
        'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=400&q=80',
    ];
    
    let slidesHTML = '';
    let indicatorsHTML = '';
    
    items.forEach((item, index) => {
        const title = item.querySelector('.item-title').value || 'Untitled';
        const description = item.querySelector('.item-description').value || 'No description';
        const icon = item.querySelector('.item-icon').value || 'fi-rr-marker';
        const highlight = item.querySelector('.item-highlight').value || 'Campus';
        const imageInput = item.querySelector('.item-image');
        const existingImageInput = item.querySelector('.existing-image');
        
        let imageUrl = defaultImages[index % defaultImages.length];
        
        // Check for existing image
        if (existingImageInput && existingImageInput.value) {
            imageUrl = '{{ asset("storage") }}/' + existingImageInput.value;
        }
        
        // Check for new file upload
        if (imageInput && imageInput.files && imageInput.files[0]) {
            imageUrl = URL.createObjectURL(imageInput.files[0]);
        }
        
        const isActive = index === currentPreviewSlide;
        
        slidesHTML += `
            <div class="preview-slide ${isActive ? 'block' : 'hidden'}">
                <div class="flex flex-col md:flex-row gap-3 p-3">
                    <div class="md:w-1/2">
                        <img src="${imageUrl}" class="w-full h-32 object-cover rounded-lg shadow" alt="${title}">
                    </div>
                    <div class="md:w-1/2 flex flex-col justify-center">
                        <h3 class="font-bold text-sm text-[#1A3165] mb-1">${title}</h3>
                        <p class="text-xs text-gray-600 mb-2 line-clamp-3">${description}</p>
                        <div class="flex items-center text-[10px] text-[#C8A165]">
                            <i class="fi ${icon} mr-1"></i>
                            <span>${highlight}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        indicatorsHTML += `
            <button type="button" 
                    class="preview-indicator w-2 h-2 rounded-full ${isActive ? 'bg-[#1A3165]' : 'bg-gray-300'} transition-all"
                    data-slide="${index}"
                    onclick="showPreviewSlide(${index})"></button>
        `;
    });
    
    previewSlides.innerHTML = slidesHTML;
    previewIndicators.innerHTML = indicatorsHTML;
}

// Handle image preview
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('item-image')) {
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
        currentPreviewSlide = 0;
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
