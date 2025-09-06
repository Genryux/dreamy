@extends('layouts.app')

@section('section_1')
    <div class="relative bg-white min-h-screen w-screen py-20 px-[120px]">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-8" data-aos="fade-right" data-aos-duration="600">
                <a href="{{ route('public.news.index') }}" 
                   class="inline-flex items-center text-[#1A3165] hover:text-[#C8A165] transition-colors duration-200">
                    <i class="fi fi-rr-arrow-left mr-2"></i>
                    Back to News
                </a>
            </div>

            <!-- Article Header -->
            <div class="mb-8" data-aos="fade-up" data-aos-duration="800">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fi fi-rr-calendar mr-2"></i>
                        {{ $news->published_at->format('F d, Y') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fi fi-rr-clock mr-2"></i>
                        {{ $news->published_at->diffForHumans() }}
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-[#1A3165] mb-6 leading-tight">
                    {{ $news->title }}
                </h1>
                <div class="bg-[#C8A165] w-[100px] h-[4px]"></div>
            </div>

            <!-- Article Content -->
            <div class="prose prose-lg max-w-none mb-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ $news->content }}
                </div>
            </div>

            <!-- Article Footer -->
            <div class="border-t border-gray-200 pt-8 mb-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Published on {{ $news->published_at->format('F d, Y \a\t g:i A') }}
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('public.news.index') }}" 
                           class="inline-flex items-center text-[#1A3165] hover:text-[#C8A165] transition-colors duration-200">
                            <i class="fi fi-rr-arrow-left mr-2"></i>
                            Back to News
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related News -->
            @if($relatedNews->count() > 0)
                <div class="border-t border-gray-200 pt-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">
                    <h3 class="text-2xl font-bold text-[#1A3165] mb-8">Related News</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedNews as $related)
                            <div class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fi fi-rr-calendar mr-1"></i>
                                        {{ $related->published_at->format('M d, Y') }}
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fi fi-rr-clock mr-1"></i>
                                        {{ $related->published_at->diffForHumans() }}
                                    </div>
                                </div>
                                <h4 class="text-lg font-semibold text-[#1A3165] mb-2 line-clamp-2">
                                    {{ $related->title }}
                                </h4>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($related->content, 100) }}
                                </p>
                                <a href="{{ route('public.news.show', $related) }}" 
                                   class="inline-flex items-center text-[#1A3165] text-sm font-medium hover:text-[#C8A165] transition-colors duration-200">
                                    Read More
                                    <i class="fi fi-rr-arrow-right ml-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .prose {
            color: #374151;
        }
        
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #1A3165;
        }
    </style>
@endpush
