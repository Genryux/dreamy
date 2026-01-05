@extends('layouts.admin', ['title' => 'Edit Hero Section'])

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
                <span class="block text-gray-900">Edit Hero Section</span>
            </li>
        </ol>
    </nav>
@endsection

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Edit Hero Section</h1>
            <p class="text-[14px] text-gray-900/60">Customize the main banner section of your homepage.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div class="flex flex-row justify-center items-start gap-4 px-[14px]">
        <div class="flex flex-col justify-start items-center flex-grow p-6 space-y-6 bg-white rounded-xl shadow-md border border-gray-200 w-full max-w-4xl">
            
            <form action="{{ route('admin.homepage.hero.update') }}" method="POST" enctype="multipart/form-data" class="w-full space-y-6">
                @csrf
                @method('PUT')

                <!-- Title Field -->
                <div class="space-y-2">
                    <label for="title" class="block text-sm font-semibold text-gray-700">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" 
                        value="{{ old('title', $hero->title ?? 'Dreamy School') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#199BCF] focus:border-[#199BCF] transition-colors"
                        required>
                    @error('title')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subtitle Field -->
                <div class="space-y-2">
                    <label for="subtitle" class="block text-sm font-semibold text-gray-700">
                        Subtitle
                    </label>
                    <input type="text" name="subtitle" id="subtitle" 
                        value="{{ old('subtitle', $hero->subtitle ?? 'Philippines') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#199BCF] focus:border-[#199BCF] transition-colors">
                    @error('subtitle')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Background Type -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Background Type <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="background_type" value="video" 
                                {{ old('background_type', $hero->background_type ?? 'video') == 'video' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#199BCF] focus:ring-[#199BCF]" 
                                onchange="toggleBackgroundInputs()">
                            <span class="text-sm text-gray-700">Video</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="background_type" value="image" 
                                {{ old('background_type', $hero->background_type) == 'image' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#199BCF] focus:ring-[#199BCF]" 
                                onchange="toggleBackgroundInputs()">
                            <span class="text-sm text-gray-700">Image</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="background_type" value="none" 
                                {{ old('background_type', $hero->background_type) == 'none' ? 'checked' : '' }}
                                class="w-4 h-4 text-[#199BCF] focus:ring-[#199BCF]" 
                                onchange="toggleBackgroundInputs()">
                            <span class="text-sm text-gray-700">None (Gradient Only)</span>
                        </label>
                    </div>
                </div>

                <!-- Background Video Upload -->
                <div id="video-upload" class="space-y-2" style="display: {{ old('background_type', $hero->background_type ?? 'video') == 'video' ? 'block' : 'none' }}">
                    <label for="background_video" class="block text-sm font-semibold text-gray-700">
                        Background Video
                    </label>
                    @if($hero->background_video_path)
                        <div class="mb-2 text-sm text-gray-600">
                            Current: <span class="font-medium">{{ basename($hero->background_video_path) }}</span>
                        </div>
                    @endif
                    <input type="file" name="background_video" id="background_video" accept="video/mp4,video/webm"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#199BCF] focus:border-[#199BCF] transition-colors">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-2">
                        <p class="text-xs text-yellow-800 font-semibold mb-1">⚠️ Important Guidelines:</p>
                        <ul class="text-xs text-yellow-700 space-y-1 ml-4 list-disc">
                            <li>Maximum file size: 50MB</li>
                            <li><strong>Recommended video length: 30-60 seconds maximum</strong></li>
                            <li>Videos longer than 60 seconds may become corrupted even if under 50MB</li>
                            <li>Supported formats: MP4, WebM</li>
                            <li>Recommended resolution: 1920x1080 (Full HD)</li>
                        </ul>
                    </div>
                    @error('background_video')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Background Image Upload -->
                <div id="image-upload" class="space-y-2" style="display: {{ old('background_type', $hero->background_type) == 'image' ? 'block' : 'none' }}">
                    <label for="background_image" class="block text-sm font-semibold text-gray-700">
                        Background Image
                    </label>
                    @if($hero->background_image_path)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $hero->background_image_path) }}" alt="Current background" class="w-48 h-32 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <input type="file" name="background_image" id="background_image" accept="image/jpeg,image/jpg,image/png"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#199BCF] focus:border-[#199BCF] transition-colors">
                    <p class="text-xs text-gray-500">Max file size: 10MB. Formats: JPG, PNG</p>
                    @error('background_image')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="space-y-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                            {{ old('is_active', $hero->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#199BCF] focus:ring-[#199BCF] rounded">
                        <span class="text-sm font-semibold text-gray-700">Active</span>
                    </label>
                    <p class="text-xs text-gray-500">Uncheck to hide this section from the homepage</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="flex-1 bg-[#199BCF] hover:bg-[#1A3165] text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
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
                <div class="bg-gradient-to-b from-[#1A3165] to-[#199BCF] rounded-lg p-8 text-center">
                    <p class="text-white text-2xl font-bold mb-2" id="preview-title">{{ $hero->title ?? 'Dreamy School' }}</p>
                    <p class="text-white text-lg" id="preview-subtitle">{{ $hero->subtitle ?? 'Philippines' }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-2">This is a simplified preview. View the actual homepage to see the full effect.</p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function toggleBackgroundInputs() {
        const backgroundType = document.querySelector('input[name="background_type"]:checked').value;
        const videoUpload = document.getElementById('video-upload');
        const imageUpload = document.getElementById('image-upload');
        
        videoUpload.style.display = backgroundType === 'video' ? 'block' : 'none';
        imageUpload.style.display = backgroundType === 'image' ? 'block' : 'none';
    }

    // Live preview updates
    document.getElementById('title').addEventListener('input', function(e) {
        document.getElementById('preview-title').textContent = e.target.value || 'Dreamy School';
    });

    document.getElementById('subtitle').addEventListener('input', function(e) {
        document.getElementById('preview-subtitle').textContent = e.target.value || 'Philippines';
    });
</script>
@endpush
