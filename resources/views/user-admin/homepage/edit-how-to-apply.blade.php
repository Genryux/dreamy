@extends('layouts.admin', ['title' => 'Edit How to Apply Section'])

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
                <span class="block text-gray-900">Edit How to Apply</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <h2 class="text-3xl font-bold text-gray-900">Edit How to Apply</h2>
    <p class="text-gray-600 mt-1">Manage the application process steps displayed on the homepage</p>
@endsection

@section('modal')
    <!-- Delete Confirmation Modal -->
    <x-modal modal_id="delete-step-modal" modal_name="Confirm Deletion" close_btn_id="delete-step-modal-close-btn"
        modal_container_id="delete-step-modal-container">
        <x-slot name="modal_icon">
            <i class='fi fi-rr-trash flex justify-center items-center text-red-600'></i>
        </x-slot>
        
        <div class="p-6">
            <p class="text-gray-700">Are you sure you want to remove this step? This action cannot be undone.</p>
        </div>

        <x-slot name="modal_buttons">
            <button id="delete-step-cancel-btn"
                class="bg-gray-50 border border-[#1e1e1e]/15 text-[14px] px-3 py-2 rounded-xl text-[#0f111c]/80 font-bold shadow-sm hover:bg-gray-100 hover:ring hover:ring-gray-200 transition duration-200">
                Cancel
            </button>
            <button id="delete-step-confirm-btn"
                class="bg-red-600 text-white text-[14px] px-3 py-2 rounded-xl font-bold shadow-sm hover:bg-red-700 hover:ring hover:ring-red-200 transition duration-200">
                Delete Step
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

        <form action="{{ route('admin.homepage.how-to-apply.update') }}" method="POST" id="howToApplyForm">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   required>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Section Description *</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                                      required>{{ old('description', $section->description) }}</textarea>
                        </div>

                        <!-- Button Settings -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text *</label>
                                <input type="text" 
                                       name="button_text" 
                                       id="button_text" 
                                       value="{{ old('button_text', $section->button_text) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       required>
                            </div>
                            <div>
                                <label for="button_link" class="block text-sm font-medium text-gray-700 mb-2">Button Link *</label>
                                <input type="text" 
                                       name="button_link" 
                                       id="button_link" 
                                       value="{{ old('button_link', $section->button_link) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                                       placeholder="/portal/register"
                                       required>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $section->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Display this section on the homepage
                            </label>
                        </div>
                    </div>

                    <!-- Application Steps Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Application Steps</h2>
                            <button type="button" 
                                    onclick="addNewStep()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Step
                            </button>
                        </div>

                        <div id="stepsContainer" class="space-y-4">
                            @foreach($section->steps as $index => $step)
                                @include('user-admin.homepage.partials.how-to-apply-step', ['step' => $step, 'index' => $index])
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            <strong>Step Number:</strong> The number displayed in the circle (1, 2, 3, etc.)
                            <br>
                            <strong>Icon:</strong> Optional Flaticon class (e.g., fi-rr-form, fi-rr-document-signed)
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
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
                            <div id="preview" class="relative bg-[#1A3165] p-6">
                                <div class="text-center mb-6">
                                    <h2 id="preview-heading" class="font-bold text-xl text-white mb-2">{{ $section->heading }}</h2>
                                    <div class="bg-[#C8A165] w-[60px] h-[2px] mx-auto mb-3"></div>
                                    <p id="preview-description" class="text-xs text-gray-400">{{ $section->description }}</p>
                                </div>
                                <div id="preview-steps" class="grid grid-cols-2 gap-3">
                                    @foreach($section->steps as $step)
                                        <div class="bg-white rounded-lg shadow p-3 flex flex-col items-center text-center">
                                            <div class="w-8 h-8 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-sm mb-2">
                                                {{ $step->step_number }}
                                            </div>
                                            <h3 class="text-xs font-bold text-[#1A3165] mb-1">{{ $step->title }}</h3>
                                            <p class="text-[10px] text-gray-600 line-clamp-2">{{ Str::limit($step->description, 50) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-center mt-4">
                                    <span id="preview-button" class="inline-flex items-center bg-[#C8A165] text-white px-4 py-2 rounded-lg text-xs font-semibold">
                                        {{ $section->button_text }} <i class="fi fi-rr-arrow-right ml-1 text-[10px]"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Step Template (hidden) -->
<template id="stepTemplate">
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 step-card" data-step-index="INDEX">
        <div class="flex items-start justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">Step #<span class="step-number">INDEX</span></h3>
            <button type="button" 
                    onclick="removeStep(this)"
                    class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <input type="hidden" name="steps[INDEX][id]" value="">
        
        <div class="space-y-3">
            <!-- Step Number & Title -->
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Step # *</label>
                    <input type="number" 
                           name="steps[INDEX][step_number]" 
                           class="step-num w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                           value="INDEX"
                           min="1"
                           required>
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" 
                           name="steps[INDEX][title]" 
                           class="step-title w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="e.g., Submit Application"
                           required>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Description *</label>
                <textarea name="steps[INDEX][description]" 
                          class="step-description w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                          rows="2"
                          placeholder="Brief description of this step..."
                          required></textarea>
            </div>

            <!-- Icon & Order -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Icon Class</label>
                    <input type="text" 
                           name="steps[INDEX][icon]" 
                           class="step-icon w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                           placeholder="fi-rr-form">
                    <p class="text-[10px] text-gray-400 mt-0.5">Optional Flaticon class</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Display Order *</label>
                    <input type="number" 
                           name="steps[INDEX][order]" 
                           class="step-order w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                           value="INDEX"
                           min="1"
                           required>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="module">
import { initModal } from "/js/modal.js";

let stepIndex = {{ count($section->steps) }};
let stepToRemove = null;

// Initialize delete modal
document.addEventListener('DOMContentLoaded', function() {
    initModal('delete-step-modal', null, 'delete-step-modal-close-btn', 'delete-step-modal-container', 'delete-step-cancel-btn');
});

// Update preview when inputs change
document.getElementById('heading').addEventListener('input', function() {
    document.getElementById('preview-heading').textContent = this.value;
});

document.getElementById('description').addEventListener('input', function() {
    document.getElementById('preview-description').textContent = this.value;
});

document.getElementById('button_text').addEventListener('input', function() {
    document.getElementById('preview-button').innerHTML = this.value + ' <i class="fi fi-rr-arrow-right ml-1 text-[10px]"></i>';
});

// Update preview for steps
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('step-num') || 
        e.target.classList.contains('step-title') ||
        e.target.classList.contains('step-description')) {
        updateStepPreviews();
    }
});

function updateStepPreviews() {
    const previewSteps = document.getElementById('preview-steps');
    const steps = document.querySelectorAll('.step-card');
    
    let previewHTML = '';
    steps.forEach((step) => {
        const stepNum = step.querySelector('.step-num').value || '1';
        const title = step.querySelector('.step-title').value || 'Untitled';
        const description = step.querySelector('.step-description').value || 'No description';
        const truncatedDesc = description.length > 50 ? description.substring(0, 50) + '...' : description;
        
        previewHTML += `
            <div class="bg-white rounded-lg shadow p-3 flex flex-col items-center text-center">
                <div class="w-8 h-8 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-sm mb-2">
                    ${stepNum}
                </div>
                <h3 class="text-xs font-bold text-[#1A3165] mb-1">${title}</h3>
                <p class="text-[10px] text-gray-600 line-clamp-2">${truncatedDesc}</p>
            </div>
        `;
    });
    
    previewSteps.innerHTML = previewHTML;
}

window.addNewStep = function() {
    stepIndex++;
    const template = document.getElementById('stepTemplate').innerHTML;
    const newStepHTML = template.replace(/INDEX/g, stepIndex).replace(/#<span class="step-number">.*?<\/span>/, `#${stepIndex}`);
    
    document.getElementById('stepsContainer').insertAdjacentHTML('beforeend', newStepHTML);
    updateStepPreviews();
    
    // Scroll to the newly added step
    const container = document.getElementById('stepsContainer');
    const newStep = container.lastElementChild;
    if (newStep) {
        newStep.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

window.removeStep = function(button) {
    stepToRemove = button.closest('.step-card');
    // Open modal
    const modalContainer = document.getElementById('delete-step-modal-container');
    const modal = document.getElementById('delete-step-modal');
    
    modalContainer.classList.remove('opacity-0', 'pointer-events-none');
    modalContainer.classList.add('opacity-100', 'pointer-events-auto');
    
    modal.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
    modal.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
}

// Confirm deletion
document.getElementById('delete-step-confirm-btn').addEventListener('click', function() {
    if (stepToRemove) {
        stepToRemove.remove();
        updateStepPreviews();
        renumberSteps();
        stepToRemove = null;
    }
    
    // Close modal
    const modalContainer = document.getElementById('delete-step-modal-container');
    const modal = document.getElementById('delete-step-modal');
    
    modalContainer.classList.add('opacity-0', 'pointer-events-none');
    modalContainer.classList.remove('opacity-100', 'pointer-events-auto');
    
    modal.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
    modal.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
});

function renumberSteps() {
    const steps = document.querySelectorAll('.step-card');
    steps.forEach((step, index) => {
        const number = index + 1;
        const numberSpan = step.querySelector('.step-number');
        if (numberSpan) {
            numberSpan.textContent = number;
        }
    });
}
</script>
@endsection
