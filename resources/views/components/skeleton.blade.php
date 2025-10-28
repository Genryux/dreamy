{{-- Clean, Minimal Skeleton Component --}}
<div id="skeleton" class="fixed inset-0 bg-gray-50 z-50 overflow-auto">
    {{-- Header Skeleton --}}
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-8 h-8 bg-gray-200 rounded-lg animate-pulse"></div>
                <div class="space-y-2">
                    <div class="h-5 w-32 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-3 w-24 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-6 h-6 bg-gray-200 rounded animate-pulse"></div>
                <div class="w-8 h-8 bg-gray-200 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>

    {{-- Main Content Skeleton --}}
    <div class="p-6">
        {{-- Page Title Skeleton --}}
        <div class="mb-8">
            <div class="h-8 w-48 bg-gray-200 rounded animate-pulse mb-2"></div>
            <div class="h-4 w-64 bg-gray-200 rounded animate-pulse"></div>
        </div>

        {{-- Stats Cards Skeleton --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @for($i = 0; $i < 4; $i++)
            <div class="bg-white p-6 rounded-xl border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="w-6 h-6 bg-gray-200 rounded animate-pulse"></div>
                </div>
                <div class="space-y-3">
                    <div class="h-6 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
            @endfor
        </div>

        {{-- Filters Skeleton --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200 mb-6">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="flex items-center space-x-3 flex-1">
                    <div class="w-5 h-5 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-10 w-64 bg-gray-200 rounded-lg animate-pulse"></div>
                </div>
                <div class="flex space-x-3">
                    <div class="h-10 w-24 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="h-10 w-20 bg-gray-200 rounded-lg animate-pulse"></div>
                    <div class="h-10 w-20 bg-gray-200 rounded-lg animate-pulse"></div>
                </div>
            </div>
        </div>

        {{-- Table Skeleton --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            {{-- Table Header --}}
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="grid grid-cols-6 gap-4">
                    <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>

            {{-- Table Rows --}}
            @for($i = 0; $i < 6; $i++)
            <div class="px-6 py-4 border-b border-gray-100 last:border-b-0">
                <div class="grid grid-cols-6 gap-4 items-center">
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-28 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        {{-- Pagination Skeleton --}}
        <div class="flex items-center justify-between mt-6">
            <div class="h-4 w-32 bg-gray-200 rounded animate-pulse"></div>
            <div class="flex space-x-2">
                <div class="w-8 h-8 bg-gray-200 rounded animate-pulse"></div>
                <div class="w-8 h-8 bg-gray-200 rounded animate-pulse"></div>
                <div class="w-8 h-8 bg-gray-200 rounded animate-pulse"></div>
                <div class="w-8 h-8 bg-gray-200 rounded animate-pulse"></div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Enhanced skeleton animations */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    /* Smooth skeleton transitions */
    #skeleton {
        transition: opacity 0.3s ease-in-out;
    }
    
    /* Ensure skeleton covers full viewport */
    #skeleton {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        z-index: 9999 !important;
    }
</style>

<script>
    // Ensure skeleton stays in place during scroll
    document.addEventListener('DOMContentLoaded', function() {
        const skeleton = document.getElementById('skeleton');
        if (skeleton) {
            // Prevent scroll on skeleton
            skeleton.style.overflow = 'hidden';
            skeleton.style.position = 'fixed';
            skeleton.style.top = '0';
            skeleton.style.left = '0';
            skeleton.style.width = '100vw';
            skeleton.style.height = '100vh';
            skeleton.style.zIndex = '9999';
        }
    });
</script>