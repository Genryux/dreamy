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
                    <h3 class="text-3xl font-bold mt-1">{{ $totalSections }}</h3>
                </div>
                <i class="fi fi-rr-layout-fluid text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#C8A165] to-[#8B6F47] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Active Content</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $activeSections }}</h3>
                </div>
                <i class="fi fi-rr-check-circle text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#10b981] to-[#059669] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Images</p>
                    <h3 class="text-3xl font-bold mt-1">{{ $totalImages }}</h3>
                </div>
                <i class="fi fi-rr-picture text-4xl opacity-50"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-xl p-5 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Last Updated</p>
                    <h3 class="text-lg font-bold mt-1">{{ $lastUpdated ? $lastUpdated->diffForHumans() : 'Never' }}</h3>
                </div>
                <i class="fi fi-rr-calendar text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Section Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-[14px]">

        <!-- Homepage Notice Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#C8A165] to-[#d4af37]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Notice Bar</h3>
                        <p class="text-sm text-gray-500 mt-1">Top announcement banner</p>
                    </div>
                    <i class="fi fi-rr-megaphone text-[#C8A165] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-bell text-xs mr-2"></i>
                        <span>{{ $homepageNotices->count() }} notice(s) configured</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-calendar text-xs mr-2"></i>
                        <span>Schedule visibility</span>
                    </div>
                </div>
                @if($homepageNotices->where('is_active', true)->count() > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
                <a href="{{ route('admin.homepage.notice.edit') }}" class="block w-full mt-2 bg-[#C8A165] hover:bg-[#b8914f] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Manage Notices
                </a>
            </div>
        </div>
        
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
                        <span>{{ $heroSection ? Str::limit($heroSection->title, 30) : 'No title set' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>{{ $heroSection && $heroSection->background_image ? '1 background image' : 'No background' }}</span>
                    </div>
                </div>
                @if($heroSection && $heroSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
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
                        <span>{{ $aboutSection ? Str::limit($aboutSection->heading, 30) : 'No heading set' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-picture text-xs mr-2"></i>
                        <span>{{ ($aboutSection && $aboutSection->image_left ? 1 : 0) + ($aboutSection && $aboutSection->image_right ? 1 : 0) }} images</span>
                    </div>
                </div>
                @if($aboutSection && $aboutSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
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
                        <span>{{ $missionValuesSection ? Str::limit($missionValuesSection->heading, 30) : 'No heading set' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-list text-xs mr-2"></i>
                        <span>{{ $missionValuesSection && $missionValuesSection->items ? $missionValuesSection->items->count() : 0 }} value items</span>
                    </div>
                </div>
                @if($missionValuesSection && $missionValuesSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
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
                        <span>{{ $glanceSection ? Str::limit($glanceSection->heading, 30) : 'No heading set' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-chart-pie text-xs mr-2"></i>
                        <span>{{ $glanceSection && $glanceSection->items ? $glanceSection->items->count() : 0 }} statistics items</span>
                    </div>
                </div>
                @if($glanceSection && $glanceSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
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
                        <i class="fi fi-rr-text text-xs mr-2"></i>
                        <span>{{ $academicProgramsSection ? Str::limit($academicProgramsSection->heading, 30) : 'No heading set' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-book text-xs mr-2"></i>
                        <span>{{ $academicProgramsSection && $academicProgramsSection->items ? $academicProgramsSection->items->count() : 0 }} program items</span>
                    </div>
                </div>
                @if($academicProgramsSection && $academicProgramsSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
                <a href="{{ route('admin.homepage.academic-programs.edit') }}" class="block w-full mt-2 bg-[#8b5cf6] hover:bg-[#6d28d9] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
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
                        <i class="fi fi-rr-text text-xs mr-2"></i>
                        <span>{{ $reasonSection ? Str::limit($reasonSection->heading, 30) : 'No heading set' }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-list text-xs mr-2"></i>
                        <span>{{ $reasonSection && $reasonSection->items ? $reasonSection->items->count() : 0 }} key reasons</span>
                    </div>
                </div>
                @if($reasonSection && $reasonSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
                <a href="{{ route('admin.homepage.reason.edit') }}" class="block w-full mt-2 bg-[#ec4899] hover:bg-[#be185d] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
            </div>
        </div>

        <!-- Alumni Testimonials Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
            <div class="h-2 bg-gradient-to-r from-[#06b6d4] to-[#0891b2]"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Alumni Success Stories</h3>
                        <p class="text-sm text-gray-500 mt-1">Success stories & testimonials</p>
                    </div>
                    <i class="fi fi-rr-quote-right text-[#06b6d4] text-2xl"></i>
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-user text-xs mr-2"></i>
                        <span>{{ $alumniSection && $alumniSection->items ? $alumniSection->items->count() : 0 }} alumni profiles</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-comment-quote text-xs mr-2"></i>
                        <span>Quotes & achievements</span>
                    </div>
                </div>
                @if($alumniSection && $alumniSection->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
                <a href="{{ route('admin.homepage.alumni.edit') }}" class="block w-full mt-2 bg-[#06b6d4] hover:bg-[#0891b2] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
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
                        <span>{{ $campusTour && $campusTour->items ? $campusTour->items->count() : 0 }} tour slides</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-magic-wand text-xs mr-2"></i>
                        <span>Auto-play carousel</span>
                    </div>
                </div>
                @if($campusTour && $campusTour->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
                <a href="{{ route('admin.homepage.campus-tour.edit') }}" class="block w-full mt-2 bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
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
                        <span>{{ $howToApply && $howToApply->steps ? $howToApply->steps->count() : 0 }} application steps</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fi fi-rr-link text-xs mr-2"></i>
                        <span>{{ $howToApply ? Str::limit($howToApply->button_link, 25) : '/portal/register' }}</span>
                    </div>
                </div>
                @if($howToApply && $howToApply->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-4">
                    Active
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mb-4">
                    Inactive
                </span>
                @endif
                <a href="{{ route('admin.homepage.how-to-apply.edit') }}" class="block w-full mt-2 bg-[#f97316] hover:bg-[#ea580c] text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                    Edit Section
                </a>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // Future: Add interactivity for edit buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Page ready
    });
</script>
@endpush
