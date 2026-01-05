@extends('layouts.admin', ['title' => 'Edit About Section'])

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
                <span class="block text-gray-900">Edit About Section</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Edit About Section</h1>
            <p class="text-[14px] text-gray-900/60">Customize the about us section with your school's story.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-row justify-center items-start gap-4 px-[14px]">
        <div class="flex flex-col justify-start items-center flex-grow p-6 space-y-6 bg-white rounded-xl shadow-md border border-gray-200 w-full max-w-4xl">
            
            <form action="{{ route('admin.homepage.about.update') }}" method="POST" enctype="multipart/form-data" class="w-full space-y-6">
                @csrf
                @method('PUT')

                <!-- Heading Field -->
                <div class="space-y-2">
                    <label for="heading" class="block text-sm font-semibold text-gray-700">
                        Heading <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="heading" id="heading" 
                        value="{{ old('heading', $about->heading ?? 'About us') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-[#C8A165] transition-colors"
                        required>
                    @error('heading')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-[#C8A165] transition-colors"
                        required>{{ old('description', $about->description ?? '') }}</textarea>
                    <p class="text-xs text-gray-500">Tell visitors about your school's mission, history, and values.</p>
                    @error('description')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="space-y-2">
                    <label for="image" class="block text-sm font-semibold text-gray-700">
                        Section Image
                    </label>
                    @if($about->image_path)
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-2">Current Image:</p>
                            <img src="{{ asset('storage/' . $about->image_path) }}" alt="Current image" class="w-64 h-48 object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/jpeg,image/jpg,image/png"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#C8A165] focus:border-[#C8A165] transition-colors">
                    <p class="text-xs text-gray-500">Max file size: 10MB. Formats: JPG, PNG. Recommended: 800x600px or higher.</p>
                    @error('image')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="space-y-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $about->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#C8A165] focus:ring-[#C8A165] rounded">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                    <p class="text-xs text-gray-500">Uncheck to hide this section from the homepage</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 bg-[#C8A165] hover:bg-[#8B6F47] text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                        <i class="fi fi-rr-disk mr-2"></i>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.homepage.index') }}"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors duration-200 text-center">
                        <i class="fi fi-rr-cross-small mr-2"></i>
                        Cancel
                    </a>
                </div>
            </form>

        </div>

        <!-- Preview Panel -->
        <div class="hidden lg:block w-80 sticky top-4">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Preview</h3>
                <div class="bg-[#1A3165] rounded-lg p-6">
                    <h2 class="text-white text-xl font-bold mb-2" id="preview-heading">{{ $about->heading ?? 'About us' }}</h2>
                    <div class="bg-[#C8A165] w-full h-1 mb-3"></div>
                    <p class="text-white text-sm" id="preview-description">{{ Str::limit($about->description ?? 'Your description will appear here...', 150) }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-2">This is a simplified preview. View the actual homepage to see the full effect.</p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Live preview updates
    document.getElementById('heading').addEventListener('input', function(e) {
        document.getElementById('preview-heading').textContent = e.target.value || 'About us';
    });

    document.getElementById('description').addEventListener('input', function(e) {
        const text = e.target.value || 'Your description will appear here...';
        document.getElementById('preview-description').textContent = text.substring(0, 150) + (text.length > 150 ? '...' : '');
    });
</script>
@endpush
