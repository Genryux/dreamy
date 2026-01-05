@extends('layouts.admin', ['title' => 'Edit Academic Programs Section'])

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
                <span class="block text-gray-900">Edit Academic Programs Section</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <h2 class="text-3xl font-bold text-gray-900">Edit Academic Programs Section</h2>
    <p class="text-gray-600 mt-1">Manage the academic programs displayed on the homepage</p>
@endsection

@section('modal')
    <!-- Delete Confirmation Modal -->
    <x-modal modal_id="delete-item-modal" modal_name="Confirm Deletion" close_btn_id="delete-item-modal-close-btn"
        modal_container_id="delete-item-modal-container">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-600'></i>
        </x-slot>
        
        <div class="p-6">
            <p class="text-gray-700">Are you sure you want to remove this program? This action cannot be undone.</p>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-item-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="delete-item-confirm-btn"
                class="bg-red-600 text-white text-[14px] px-3 py-2 rounded-xl font-bold shadow-sm hover:bg-red-700 hover:ring hover:ring-red-200 transition duration-200">
                Delete Program
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

        <form action="{{ route('admin.homepage.academic-programs.update') }}" method="POST" id="academicProgramsForm">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Section Description *</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      required>{{ old('description', $section->description) }}</textarea>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Display this section on the homepage
                            </label>
                        </div>
                    </div>

                    <!-- Program Items Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Program Items</h2>
                            <button type="button" 
                                    onclick="addNewItem()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Program
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            @foreach($section->items as $index => $item)
                                @include('user-admin.homepage.partials.academic-program-item', ['item' => $item, 'index' => $index])
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            <strong>Status:</strong> Set to "Coming Soon" to display programs that are not yet available
                            <br>
                            <strong>Gradients:</strong> Use hex color codes (e.g., #1A3165, #C8A165) for gradient colors
                            <br>
                            <strong>Link:</strong> Optional - leave blank if no specific page exists
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                            <div id="preview" class="bg-white p-8">
                                <div class="text-center mb-8">
                                    <h2 id="preview-heading" class="font-bold text-2xl text-[#1A3165] mb-2">{{ $section->heading }}</h2>
                                    <div class="bg-[#C8A165] w-[80px] h-[3px] mx-auto mb-4"></div>
                                    <p id="preview-description" class="text-sm text-gray-600">{{ $section->description }}</p>
                                </div>
                                <div id="preview-items" class="space-y-4">
                                    @foreach($section->items as $item)
                                        <div class="rounded-lg shadow p-4 text-white relative" 
                                             style="background: linear-gradient(to bottom right, {{ $item->gradient_from }}, {{ $item->gradient_to }});"
                                             data-item-id="{{ $item->id }}">
                                            @if($item->status === 'coming_soon')
                                                <div class="absolute top-2 right-2 bg-white/20 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                                    Coming Soon
                                                </div>
                                            @endif
                                            <h3 class="text-sm font-bold mb-1">{{ $item->title }}</h3>
                                            @if($item->track_name)
                                                <p class="text-xs text-white/80 mb-1">{{ $item->track_name }} Track</p>
                                            @endif
                                            <p class="text-xs text-white/80">{{ Str::limit($item->description, 50) }}</p>
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
            <h3 class="text-sm font-semibold text-gray-700">Program #<span class="item-number">INDEX</span></h3>
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
                <label class="block text-xs font-medium text-gray-700 mb-1">Program Title *</label>
                <input type="text" 
                       name="items[INDEX][title]" 
                       class="item-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
                <textarea name="items[INDEX][description]" 
                          class="item-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                          rows="2"
                          required></textarea>
            </div>

            <!-- Track Name & Status -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Track Name</label>
                    <input type="text" 
                           name="items[INDEX][track_name]" 
                           class="item-track-name w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g., STEM, ABM">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status *</label>
                    <select name="items[INDEX][status]" 
                            class="item-status w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="active">Active</option>
                        <option value="coming_soon">Coming Soon</option>
                    </select>
                </div>
            </div>

            <!-- Gradient Colors -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Gradient From *</label>
                    <input type="color" 
                           name="items[INDEX][gradient_from]" 
                           class="item-gradient-from w-full h-9 border border-gray-300 rounded cursor-pointer"
                           value="#1A3165"
                           required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Gradient To *</label>
                    <input type="color" 
                           name="items[INDEX][gradient_to]" 
                           class="item-gradient-to w-full h-9 border border-gray-300 rounded cursor-pointer"
                           value="#2A4A7A"
                           required>
                </div>
            </div>

            <!-- Link URL & Order -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Link URL</label>
                    <input type="url" 
                           name="items[INDEX][link_url]" 
                           class="item-link-url w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="https://...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                    <input type="number" 
                           name="items[INDEX][order]" 
                           class="item-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
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
    if (e.target.classList.contains('item-title') || 
        e.target.classList.contains('item-description') ||
        e.target.classList.contains('item-track-name') ||
        e.target.classList.contains('item-gradient-from') ||
        e.target.classList.contains('item-gradient-to') ||
        e.target.classList.contains('item-status')) {
        updateItemPreviews();
    }
});

function updateItemPreviews() {
    const previewItems = document.getElementById('preview-items');
    const items = document.querySelectorAll('.item-card');
    
    let previewHTML = '';
    items.forEach((item, index) => {
        const title = item.querySelector('.item-title').value;
        const description = item.querySelector('.item-description').value;
        const trackName = item.querySelector('.item-track-name').value;
        const gradientFrom = item.querySelector('.item-gradient-from').value;
        const gradientTo = item.querySelector('.item-gradient-to').value;
        const status = item.querySelector('.item-status').value;
        const itemId = item.querySelector('input[name$="[id]"]').value;
        
        if (title || description) {
            const truncatedDesc = description.length > 50 ? description.substring(0, 50) + '...' : description;
            const comingSoonBadge = status === 'coming_soon' ? '<div class="absolute top-2 right-2 bg-white/20 text-white text-xs font-semibold px-2 py-1 rounded-full">Coming Soon</div>' : '';
            const trackDisplay = trackName ? `<p class="text-xs text-white/80 mb-1">${trackName} Track</p>` : '';
            
            previewHTML += `
                <div class="rounded-lg shadow p-4 text-white relative" 
                     style="background: linear-gradient(to bottom right, ${gradientFrom}, ${gradientTo});"
                     data-item-id="${itemId}">
                    ${comingSoonBadge}
                    <h3 class="text-sm font-bold mb-1">${title}</h3>
                    ${trackDisplay}
                    <p class="text-xs text-white/80">${truncatedDesc}</p>
                </div>
            `;
        }
    });
    
    previewItems.innerHTML = previewHTML;
}

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
