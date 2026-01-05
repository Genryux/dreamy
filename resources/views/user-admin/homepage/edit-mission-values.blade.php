@extends('layouts.admin', ['title' => 'Edit Mission & Values Section'])

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
                <span class="block text-gray-900">Edit Mission & Values Section</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <h2 class="text-3xl font-bold text-gray-900">Edit Mission & Values Section</h2>
    <p class="text-gray-600 mt-1">Manage the mission and values displayed on the homepage</p>
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

        <form action="{{ route('admin.homepage.mission-values.update') }}" method="POST" id="missionValuesForm">
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

                    <!-- Value Items Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Value Items</h2>
                            <button type="button" 
                                    onclick="addNewItem()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Item
                            </button>
                        </div>

                        <div id="itemsContainer" class="space-y-4">
                            @foreach($section->items as $index => $item)
                                @include('user-admin.homepage.partials.mission-value-item', ['item' => $item, 'index' => $index])
                            @endforeach
                        </div>

                        <p class="text-sm text-gray-500 mt-4">
                            <strong>Icon Classes:</strong> Use Flaticon classes like <code class="bg-gray-100 px-1 py-0.5 rounded">fi fi-rr-bulb</code>, 
                            <code class="bg-gray-100 px-1 py-0.5 rounded">fi fi-rr-heart</code>, 
                            <code class="bg-gray-100 px-1 py-0.5 rounded">fi fi-rr-globe</code>, etc.
                            <br>
                            Browse all available icons at: <a href="https://www.flaticon.com/uicons/interface-icons" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Flaticon Interface Icons</a>
                            <br>
                            <strong>Color:</strong> Use hex color codes (e.g., #1A3165, #C8A165)
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
                            <div id="preview" class="bg-[#F8F8F8] p-8">
                                <div class="text-center mb-8">
                                    <h2 id="preview-heading" class="font-bold text-2xl text-[#1A3165] mb-2">{{ $section->heading }}</h2>
                                    <div class="bg-[#C8A165] w-[80px] h-[3px] mx-auto mb-4"></div>
                                    <p id="preview-description" class="text-sm text-gray-600">{{ $section->description }}</p>
                                </div>
                                <div id="preview-items" class="space-y-4">
                                    @foreach($section->items as $item)
                                        <div class="bg-white rounded-lg shadow p-4 text-center" data-item-id="{{ $item->id }}">
                                            <i class="{{ $item->icon }} text-2xl mb-2" style="color: {{ $item->color }}"></i>
                                            <h3 class="text-sm font-bold mb-1" style="color: {{ $item->color }}">{{ $item->title }}</h3>
                                            <p class="text-xs text-gray-600">{{ Str::limit($item->description, 60) }}</p>
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
            <h3 class="text-sm font-semibold text-gray-700">Value Item #<span class="item-number">INDEX</span></h3>
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

            <!-- Icon & Color -->
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Icon Class *</label>
                    <input type="text" 
                           name="items[INDEX][icon]" 
                           class="item-icon w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="fi fi-rr-bulb"
                           required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Color *</label>
                    <input type="color" 
                           name="items[INDEX][color]" 
                           class="item-color w-full h-9 border border-gray-300 rounded cursor-pointer"
                           value="#1A3165"
                           required>
                </div>
            </div>

            <!-- Order -->
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
</template>

<script>
let itemIndex = {{ count($section->items) }};

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
        e.target.classList.contains('item-color')) {
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
        const icon = item.querySelector('.item-icon').value;
        const color = item.querySelector('.item-color').value;
        const itemId = item.querySelector('input[name$="[id]"]').value;
        
        if (title || description) {
            const truncatedDesc = description.length > 60 ? description.substring(0, 60) + '...' : description;
            previewHTML += `
                <div class="bg-white rounded-lg shadow p-4 text-center" data-item-id="${itemId}">
                    <i class="${icon || 'fi fi-rr-star'} text-2xl mb-2" style="color: ${color}"></i>
                    <h3 class="text-sm font-bold mb-1" style="color: ${color}">${title}</h3>
                    <p class="text-xs text-gray-600">${truncatedDesc}</p>
                </div>
            `;
        }
    });
    
    previewItems.innerHTML = previewHTML;
}

function addNewItem() {
    itemIndex++;
    const template = document.getElementById('itemTemplate').innerHTML;
    const newItemHTML = template.replace(/INDEX/g, itemIndex).replace(/#<span class="item-number">.*?<\/span>/, `#${itemIndex}`);
    
    document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', newItemHTML);
    updateItemPreviews();
}

function removeItem(button) {
    if (confirm('Are you sure you want to remove this item?')) {
        const itemCard = button.closest('.item-card');
        itemCard.remove();
        updateItemPreviews();
        renumberItems();
    }
}

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
