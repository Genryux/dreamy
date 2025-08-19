{{-- resources/views/components/enrollment-skeleton.blade.php --}}
<div id="skeleton" class="min-h-screen bg-gray-50">
    {{-- Sidebar Skeleton --}}
    <div class="fixed inset-y-0 left-0 w-[300px] bg-[#1A3165]">
        <div class="p-6">
            {{-- Logo Skeleton --}}
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-blue-700 rounded-lg animate-pulse"></div>
                <div class="space-y-2">
                    <div class="h-4 w-32 bg-blue-700 rounded animate-pulse"></div>
                    <div class="h-3 w-20 bg-blue-700 rounded animate-pulse"></div>
                </div>
            </div>

            {{-- Navigation Skeleton --}}
            <div class="space-y-2">
                <div class="flex items-center space-x-3 p-3">
                    <div class="w-5 h-5 bg-blue-700 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-blue-700 rounded animate-pulse"></div>
                </div>
                <div class="flex items-center space-x-3 p-3">
                    <div class="w-5 h-5 bg-blue-700 rounded animate-pulse"></div>
                    <div class="h-4 w-24 bg-blue-700 rounded animate-pulse"></div>
                    <div class="w-4 h-4 bg-blue-700 rounded animate-pulse"></div>
                </div>
                <div class="flex items-center space-x-3 p-3 bg-[#199BCF] rounded-lg">
                    <div class="w-5 h-5 bg-cyan-400 rounded animate-pulse"></div>
                    <div class="h-4 w-32 bg-cyan-400 rounded animate-pulse"></div>
                </div>
            </div>
        </div>

        {{-- User Info Skeleton --}}
        <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-blue-800">
            <div class="space-y-2">
                <div class="h-3 w-24 bg-blue-700 rounded animate-pulse"></div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-blue-700 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-blue-700 rounded animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Skeleton --}}
    <div class="ml-72 p-6">
        {{-- Header Skeleton --}}
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <div class="h-8 w-64 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-96 bg-gray-200 rounded animate-pulse"></div>
                </div>
                <div class="flex space-x-4">
                    <div class="w-6 h-6 bg-gray-200 rounded animate-pulse"></div>
                    <div class="w-8 h-8 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
            </div>
        </div>

        {{-- Stats Cards Skeleton --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Total Card --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-5 h-5 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-5 w-12 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="w-5 h-5 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <div class="h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>

            {{-- Grade Level Card --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-5 h-5 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-5 w-24 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="w-5 h-5 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>

            {{-- Program Card --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-5 h-5 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-5 w-20 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="w-5 h-5 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full animate-pulse"></div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between">
                        <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                    <div class="flex justify-between">
                        <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters and Search Skeleton --}}
        <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                {{-- Search Bar --}}
                <div class="flex items-center space-x-2 flex-1 max-w-md">
                    <div class="w-5 h-5 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-10 w-full bg-gray-200 rounded animate-pulse"></div>
                </div>

                {{-- Filter Dropdowns --}}
                <div class="flex space-x-2">
                    <div class="h-10 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-10 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="w-6 h-6 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>
        </div>

        {{-- Table Skeleton --}}
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            {{-- Table Header --}}
            <div class="border-b bg-gray-50 px-6 py-4">
                <div class="grid grid-cols-7 gap-4">
                    <div class="h-4 w-8 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-28 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                </div>
            </div>

            {{-- Table Rows --}}
            @for($i = 0; $i < 4; $i++)
            <div class="border-b px-6 py-4">
                <div class="grid grid-cols-7 gap-4 items-center">
                    <div class="h-4 w-20 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-32 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-16 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-24 bg-gray-200 rounded animate-pulse"></div>
                    <div class="h-4 w-40 bg-gray-200 rounded animate-pulse"></div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>