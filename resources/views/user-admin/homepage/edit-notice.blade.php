@extends('layouts.admin', ['title' => 'Manage Homepage Notices'])

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
                <span class="block text-gray-900">Manage Notices</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <h2 class="text-3xl font-bold text-gray-900">Manage Homepage Notices</h2>
    <p class="text-gray-600 mt-1">Configure the announcement bar displayed at the top of the website</p>
@endsection

@section('modal')
    <!-- Delete Confirmation Modal -->
    <x-modal modal_id="delete-notice-modal" modal_name="Confirm Deletion" close_btn_id="delete-notice-modal-close-btn"
        modal_container_id="delete-notice-modal-container">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-600'></i>
        </x-slot>
        
        <div class="p-6">
            <p class="text-gray-700">Are you sure you want to remove this notice? This action cannot be undone.</p>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-notice-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="delete-notice-confirm-btn"
                class="bg-red-600 text-white text-[14px] px-3 py-2 rounded-xl font-bold shadow-sm hover:bg-red-700 hover:ring hover:ring-red-200 transition duration-200">
                Delete Notice
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

        <!-- Live Preview -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Live Preview</h3>
            <div id="notice-preview" class="h-[30px] flex justify-center items-center rounded-lg overflow-hidden" style="background-color: #C8A165;">
                <div id="preview-slider" class="w-full h-full flex items-center">
                    <div id="preview-content" class="scrolling whitespace-nowrap font-semibold text-sm md:text-base" style="color: #FFFFFF;">
                        Your notice message will appear here...
                    </div>
                </div>
                <button id="preview-close-btn" class="absolute right-4 hover:opacity-80 transition-colors duration-200 text-xl font-bold" style="color: #FFFFFF;">
                    ×
                </button>
            </div>
        </div>

        <form action="{{ route('admin.homepage.notice.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Notices Container -->
            <div id="notices-container" class="space-y-6">
                @forelse($notices as $index => $notice)
                <div class="notice-card bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-index="{{ $index }}">
                    <div class="h-2 bg-gradient-to-r from-[#C8A165] to-[#d4af37]"></div>
                    <div class="p-6">
                        <input type="hidden" name="notices[{{ $index }}][id]" value="{{ $notice->id }}">

                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-bold text-gray-900">Notice #<span class="notice-number">{{ $index + 1 }}</span></h4>
                            <button type="button" class="delete-notice-btn text-red-500 hover:text-red-700 transition-colors" title="Delete Notice">
                                <i class="fi fi-rr-trash text-xl"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Message -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                                <textarea name="notices[{{ $index }}][message]" rows="2" required
                                    class="notice-message w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent"
                                    placeholder="Enter your announcement message...">{{ old("notices.{$index}.message", $notice->message) }}</textarea>
                            </div>

                            <!-- Background Color -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="notices[{{ $index }}][bg_color]" value="{{ old("notices.{$index}.bg_color", $notice->bg_color) }}"
                                        class="notice-bg-color w-12 h-10 rounded cursor-pointer border border-gray-300">
                                    <input type="text" value="{{ old("notices.{$index}.bg_color", $notice->bg_color) }}"
                                        class="notice-bg-color-text flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent uppercase" readonly>
                                </div>
                            </div>

                            <!-- Text Color -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" name="notices[{{ $index }}][text_color]" value="{{ old("notices.{$index}.text_color", $notice->text_color) }}"
                                        class="notice-text-color w-12 h-10 rounded cursor-pointer border border-gray-300">
                                    <input type="text" value="{{ old("notices.{$index}.text_color", $notice->text_color) }}"
                                        class="notice-text-color-text flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent uppercase" readonly>
                                </div>
                            </div>

                            <!-- Link URL -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Link URL (optional)</label>
                                <input type="url" name="notices[{{ $index }}][link_url]" value="{{ old("notices.{$index}.link_url", $notice->link_url) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent"
                                    placeholder="https://example.com/apply">
                            </div>

                            <!-- Starts At -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date (optional)</label>
                                <input type="datetime-local" name="notices[{{ $index }}][starts_at]" value="{{ old("notices.{$index}.starts_at", $notice->starts_at ? $notice->starts_at->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent">
                            </div>

                            <!-- Ends At -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date (optional)</label>
                                <input type="datetime-local" name="notices[{{ $index }}][ends_at]" value="{{ old("notices.{$index}.ends_at", $notice->ends_at ? $notice->ends_at->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent">
                            </div>
                        </div>

                        <!-- Toggles -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                            <!-- Is Active -->
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="notices[{{ $index }}][is_active]" value="1" {{ old("notices.{$index}.is_active", $notice->is_active) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#C8A165] border-gray-300 rounded focus:ring-[#C8A165]">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>

                            <!-- Is Scrolling -->
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="notices[{{ $index }}][is_scrolling]" value="1" {{ old("notices.{$index}.is_scrolling", $notice->is_scrolling) ? 'checked' : '' }}
                                    class="notice-scrolling w-5 h-5 text-[#C8A165] border-gray-300 rounded focus:ring-[#C8A165]">
                                <span class="text-sm text-gray-700">Scrolling Animation</span>
                            </label>

                            <!-- Is Dismissible -->
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="notices[{{ $index }}][is_dismissible]" value="1" {{ old("notices.{$index}.is_dismissible", $notice->is_dismissible) ? 'checked' : '' }}
                                    class="notice-dismissible w-5 h-5 text-[#C8A165] border-gray-300 rounded focus:ring-[#C8A165]">
                                <span class="text-sm text-gray-700">Can be Dismissed</span>
                            </label>

                            <!-- Order -->
                            <div>
                                <label class="block text-sm text-gray-700">Priority Order</label>
                                <input type="number" name="notices[{{ $index }}][order]" value="{{ old("notices.{$index}.order", $notice->order) }}" min="1" required
                                    class="w-20 px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div id="no-notices-message" class="bg-gray-50 rounded-xl p-8 text-center border border-gray-200">
                    <i class="fi fi-rr-megaphone text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500">No notices yet. Click "Add Notice" to create one.</p>
                </div>
                @endforelse
            </div>

            <!-- Add Notice Button -->
            <div class="mt-6">
                <button type="button" id="add-notice-btn"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors duration-200 border border-gray-300">
                    <i class="fi fi-rr-plus"></i>
                    Add Notice
                </button>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.homepage.index') }}" 
                    class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                    <i class="fi fi-rr-arrow-left"></i>
                    Back to Homepage Manager
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-[#C8A165] hover:bg-[#b8914f] text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 shadow-lg">
                    <i class="fi fi-rr-disk"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Notice Template (hidden) -->
<template id="notice-template">
    <div class="notice-card bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" data-index="__INDEX__">
        <div class="h-2 bg-gradient-to-r from-[#C8A165] to-[#d4af37]"></div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-gray-900">Notice #<span class="notice-number">__NUMBER__</span></h4>
                <button type="button" class="delete-notice-btn text-red-500 hover:text-red-700 transition-colors" title="Delete Notice">
                    <i class="fi fi-rr-trash text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Message -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea name="notices[__INDEX__][message]" rows="2" required
                        class="notice-message w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent"
                        placeholder="Enter your announcement message..."></textarea>
                </div>

                <!-- Background Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="notices[__INDEX__][bg_color]" value="#C8A165"
                            class="notice-bg-color w-12 h-10 rounded cursor-pointer border border-gray-300">
                        <input type="text" value="#C8A165"
                            class="notice-bg-color-text flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent uppercase" readonly>
                    </div>
                </div>

                <!-- Text Color -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="notices[__INDEX__][text_color]" value="#FFFFFF"
                            class="notice-text-color w-12 h-10 rounded cursor-pointer border border-gray-300">
                        <input type="text" value="#FFFFFF"
                            class="notice-text-color-text flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent uppercase" readonly>
                    </div>
                </div>

                <!-- Link URL -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link URL (optional)</label>
                    <input type="url" name="notices[__INDEX__][link_url]" value=""
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent"
                        placeholder="https://example.com/apply">
                </div>

                <!-- Starts At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date (optional)</label>
                    <input type="datetime-local" name="notices[__INDEX__][starts_at]" value=""
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent">
                </div>

                <!-- Ends At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date (optional)</label>
                    <input type="datetime-local" name="notices[__INDEX__][ends_at]" value=""
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent">
                </div>
            </div>

            <!-- Toggles -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                <!-- Is Active -->
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notices[__INDEX__][is_active]" value="1" checked
                        class="w-5 h-5 text-[#C8A165] border-gray-300 rounded focus:ring-[#C8A165]">
                    <span class="text-sm text-gray-700">Active</span>
                </label>

                <!-- Is Scrolling -->
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notices[__INDEX__][is_scrolling]" value="1" checked
                        class="notice-scrolling w-5 h-5 text-[#C8A165] border-gray-300 rounded focus:ring-[#C8A165]">
                    <span class="text-sm text-gray-700">Scrolling Animation</span>
                </label>

                <!-- Is Dismissible -->
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="notices[__INDEX__][is_dismissible]" value="1" checked
                        class="notice-dismissible w-5 h-5 text-[#C8A165] border-gray-300 rounded focus:ring-[#C8A165]">
                    <span class="text-sm text-gray-700">Can be Dismissed</span>
                </label>

                <!-- Order -->
                <div>
                    <label class="block text-sm text-gray-700">Priority Order</label>
                    <input type="number" name="notices[__INDEX__][order]" value="__ORDER__" min="1" required
                        class="w-20 px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-transparent">
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    /* Preview Animation */
    #preview-content.scrolling {
        display: inline-block;
        padding-left: 100%;
        animation: preview-scroll 15s linear infinite;
    }

    #preview-content.static {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        text-align: center;
    }

    @keyframes preview-scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-100%);
        }
    }
</style>
@endsection

@push('scripts')
<script type="module">
import { initModal } from "/js/modal.js";

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('notices-container');
    const addBtn = document.getElementById('add-notice-btn');
    const template = document.getElementById('notice-template');
    const noNoticesMessage = document.getElementById('no-notices-message');
    
    // Preview elements
    const previewContainer = document.getElementById('notice-preview');
    const previewContent = document.getElementById('preview-content');
    const previewCloseBtn = document.getElementById('preview-close-btn');
    
    // Initialize delete modal (this sets up close and cancel buttons)
    initModal('delete-notice-modal', null, 'delete-notice-modal-close-btn', 'delete-notice-cancel-btn', 'delete-notice-modal-container');
    
    // Delete modal elements
    const deleteModalContainer = document.getElementById('delete-notice-modal-container');
    const deleteModal = document.getElementById('delete-notice-modal');
    const deleteModalConfirm = document.getElementById('delete-notice-confirm-btn');
    let noticeToDelete = null;

    // Get next index
    function getNextIndex() {
        const cards = container.querySelectorAll('.notice-card');
        let maxIndex = -1;
        cards.forEach(card => {
            const idx = parseInt(card.dataset.index);
            if (idx > maxIndex) maxIndex = idx;
        });
        return maxIndex + 1;
    }

    // Update numbers
    function updateNoticeNumbers() {
        const cards = container.querySelectorAll('.notice-card');
        cards.forEach((card, i) => {
            card.querySelector('.notice-number').textContent = i + 1;
        });
        
        // Show/hide no notices message
        if (noNoticesMessage) {
            noNoticesMessage.style.display = cards.length === 0 ? 'block' : 'none';
        }
    }

    // Add notice
    addBtn.addEventListener('click', function() {
        const index = getNextIndex();
        const html = template.innerHTML
            .replace(/__INDEX__/g, index)
            .replace(/__NUMBER__/g, index + 1)
            .replace(/__ORDER__/g, index + 1);
        
        // Hide no notices message if visible
        if (noNoticesMessage) {
            noNoticesMessage.style.display = 'none';
        }
        
        container.insertAdjacentHTML('beforeend', html);
        
        const newCard = container.lastElementChild;
        attachCardEvents(newCard);
        updateNoticeNumbers();
        updatePreview();
    });

    // Attach events to card
    function attachCardEvents(card) {
        // Delete button
        const deleteBtn = card.querySelector('.delete-notice-btn');
        deleteBtn.addEventListener('click', function() {
            noticeToDelete = card;
            // Open modal
            deleteModalContainer.classList.remove('opacity-0', 'pointer-events-none');
            deleteModalContainer.classList.add('opacity-100');
            
            deleteModal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            deleteModal.classList.add('opacity-100', 'scale-100');
        });

        // Color pickers sync
        const bgColor = card.querySelector('.notice-bg-color');
        const bgColorText = card.querySelector('.notice-bg-color-text');
        const textColor = card.querySelector('.notice-text-color');
        const textColorText = card.querySelector('.notice-text-color-text');
        const messageInput = card.querySelector('.notice-message');
        const scrollingCheck = card.querySelector('.notice-scrolling');
        const dismissibleCheck = card.querySelector('.notice-dismissible');

        bgColor.addEventListener('input', function() {
            bgColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        textColor.addEventListener('input', function() {
            textColorText.value = this.value.toUpperCase();
            updatePreview();
        });

        messageInput.addEventListener('input', updatePreview);
        scrollingCheck.addEventListener('change', updatePreview);
        dismissibleCheck.addEventListener('change', updatePreview);
    }

    // Update preview from first notice
    function updatePreview() {
        const firstCard = container.querySelector('.notice-card');
        if (!firstCard) {
            previewContent.textContent = 'Your notice message will appear here...';
            return;
        }

        const message = firstCard.querySelector('.notice-message').value || 'Your notice message will appear here...';
        const bgColor = firstCard.querySelector('.notice-bg-color').value;
        const textColor = firstCard.querySelector('.notice-text-color').value;
        const isScrolling = firstCard.querySelector('.notice-scrolling').checked;
        const isDismissible = firstCard.querySelector('.notice-dismissible').checked;

        previewContent.textContent = message;
        previewContainer.style.backgroundColor = bgColor;
        previewContent.style.color = textColor;
        previewCloseBtn.style.color = textColor;
        
        previewContent.classList.toggle('scrolling', isScrolling);
        previewContent.classList.toggle('static', !isScrolling);
        previewCloseBtn.style.display = isDismissible ? 'block' : 'none';
    }

    // Confirm deletion
    deleteModalConfirm.addEventListener('click', function() {
        if (noticeToDelete) {
            noticeToDelete.remove();
            updateNoticeNumbers();
            updatePreview();
            noticeToDelete = null;
        }
        // Close modal
        deleteModalContainer.classList.add('opacity-0', 'pointer-events-none');
        deleteModalContainer.classList.remove('opacity-100');
        
        deleteModal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        deleteModal.classList.remove('opacity-100', 'scale-100');
    });

    // Initialize existing cards
    container.querySelectorAll('.notice-card').forEach(attachCardEvents);
    updatePreview();
});
</script>
@endpush
