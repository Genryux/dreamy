@extends('layouts.admin', ['title' => 'Homepage Manager'])

@section('header')
    <div class="flex flex-row justify-between items-start text-start px-[14px] py-2">
        <div>
            <h1 class="text-[20px] font-black">Homepage Content Manager</h1>
            <p class="text-[14px] text-gray-900/60">Manage all homepage sections and content from this dashboard.
            </p>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 px-[14px]">
        <div class="bg-gradient-to-br from-[#199BCF] to-[#1A3165] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Sections</p>
                    <h3 class="text-3xl font-bold mt-1">9</h3>
                </div>
                <i class="fi fi-rr-layout-fluid text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#C8A165] to-[#8B6F47] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Active Content</p>
                    <h3 class="text-3xl font-bold mt-1">7</h3>
                </div>
                <i class="fi fi-rr-check-circle text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#10b981] to-[#059669] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Images</p>
                    <h3 class="text-3xl font-bold mt-1">24</h3>
                </div>
                <i class="fi fi-rr-picture text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Last Updated</p>
                    <h3 class="text-lg font-bold mt-1">2 days ago</h3>
                </div>
                <i class="fi fi-rr-calendar text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Section Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-[14px]">
        
        <!-- Hero Section Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#199BCF] to-[#1A3165]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Hero Section</h3>
                        <p class="text-sm text-gray-500 mt-1">Main banner with headline & CTA</p>
                    </div>
                    <i class="fi fi-rr-star text-[#199BCF] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-text text-xs mr-2"></i>
                        <span>Headline, subtitle, button</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>1 background image</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <a href="{{ route('admin.homepage.hero.edit') }}" class="block w-full mt-2 bg-[#199BCF] hover:bg-[#1A3165] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
            </div>
        </div>

        <!-- About Us Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#C8A165] to-[#8B6F47]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">About Us</h3>
                        <p class="text-sm text-gray-500 mt-1">School introduction & history</p>
                    </div>
                    <i class="fi fi-rr-info text-[#C8A165] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-text text-xs mr-2"></i>
                        <span>Title, description, stats</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>2 images</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <a href="{{ route('admin.homepage.about.edit') }}" class="block w-full mt-2 bg-[#C8A165] hover:bg-[#8B6F47] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
            </div>
        </div>

        <!-- Mission & Values Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#10b981] to-[#059669]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Mission & Values</h3>
                        <p class="text-sm text-gray-500 mt-1">Core principles & beliefs</p>
                    </div>
                    <i class="fi fi-rr-bullseye-arrow text-[#10b981] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-text text-xs mr-2"></i>
                        <span>Mission statement & description</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-list text-xs mr-2"></i>
                        <span>Multiple value items</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <a href="{{ route('admin.homepage.mission-values.edit') }}" class="block w-full mt-2 bg-[#10b981] hover:bg-[#059669] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
            </div>
        </div>

        <!-- At a Glance Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#C8A165] to-[#8B6F47]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">School at a Glance</h3>
                        <p class="text-sm text-gray-500 mt-1">Key statistics & numbers</p>
                    </div>
                    <i class="fi fi-rr-stats text-[#C8A165] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-text text-xs mr-2"></i>
                        <span>Heading & description</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-chart-pie text-xs mr-2"></i>
                        <span>Multiple statistics items</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <a href="{{ route('admin.homepage.school-at-glance.edit') }}" class="block w-full mt-2 bg-[#C8A165] hover:bg-[#8B6F47] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
            </div>
        </div>

        <!-- Academic Programs Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#8b5cf6] to-[#6d28d9]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Academic Programs</h3>
                        <p class="text-sm text-gray-500 mt-1">Available programs & tracks</p>
                    </div>
                    <i class="fi fi-rr-graduation-cap text-[#8b5cf6] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-book text-xs mr-2"></i>
                        <span>Program listings</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>6 program images</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <button class="w-full mt-2 bg-[#8b5cf6] hover:bg-[#6d28d9] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- Why Choose Us Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#ec4899] to-[#be185d]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Why Choose Us</h3>
                        <p class="text-sm text-gray-500 mt-1">Benefits & advantages</p>
                    </div>
                    <i class="fi fi-rr-badge-check text-[#ec4899] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-list text-xs mr-2"></i>
                        <span>6 key reasons</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-sparkles text-xs mr-2"></i>
                        <span>Icons & descriptions</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <button class="w-full mt-2 bg-[#ec4899] hover:bg-[#be185d] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- Alumni Testimonials Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#06b6d4] to-[#0891b2]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Alumni Testimonials</h3>
                        <p class="text-sm text-gray-500 mt-1">Success stories & reviews</p>
                    </div>
                    <i class="fi fi-rr-quote-right text-[#06b6d4] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-user text-xs mr-2"></i>
                        <span>4 testimonials</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>4 profile photos</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-4">
                    Needs Update
                </span>
                <button class="w-full mt-2 bg-[#06b6d4] hover:bg-[#0891b2] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- Virtual Campus Tour Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#14b8a6] to-[#0d9488]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Virtual Campus Tour</h3>
                        <p class="text-sm text-gray-500 mt-1">Photo gallery & carousel</p>
                    </div>
                    <i class="fi fi-rr-camera text-[#14b8a6] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>8 campus images</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-magic-wand text-xs mr-2"></i>
                        <span>Vertical carousel</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <button class="w-full mt-2 bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit Section
                </button>
            </div>
        </div>

        <!-- How to Apply Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#f97316] to-[#ea580c]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">How to Apply</h3>
                        <p class="text-sm text-gray-500 mt-1">Application process steps</p>
                    </div>
                    <i class="fi fi-rr-edit text-[#f97316] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-list-check text-xs mr-2"></i>
                        <span>5 application steps</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-link text-xs mr-2"></i>
                        <span>Portal link included</span>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                <button class="w-full mt-2 bg-[#f97316] hover:bg-[#ea580c] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                    Edit Section
                </button>
            </div>
        </div>

    </div>

    <!-- Coming Soon Notice -->
    <div class="mt-8 px-[14px]">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
            <i class="fi fi-rr-info text-blue-500 text-3xl mb-3"></i>
            <h3 class="text-lg font-bold text-blue-900 mb-2">Advanced Features Coming Soon</h3>
            <p class="text-sm text-blue-700">Edit functionality, image uploads, and dynamic content management will be available in the next update.</p>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Future: Add interactivity for edit buttons
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('button');
        editButtons.forEach(button => {
            if (button.textContent.includes('Edit Section')) {
                button.addEventListener('click', function() {
                    // Placeholder for future edit functionality
                    alert('Edit functionality will be implemented soon!');
                });
            }
        });
    });
</script>
@endpush
