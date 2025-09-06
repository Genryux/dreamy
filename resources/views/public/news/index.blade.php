@extends('layouts.app')

@section('section_1')
    <div class="relative bg-[#1A3165] min-h-screen w-screen py-40 px-[120px] border border-red-500">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h1 class="font-bold text-[48px] text-white mb-4">News & Announcements</h1>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-white/80 max-w-2xl mx-auto">Stay updated with the latest news and announcements from Dreamy School</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($news as $article)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" 
                         data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-sm text-white/70">
                                    <i class="fi fi-rr-calendar mr-2"></i>
                                    {{ $article->published_at->format('M d, Y') }}
                                </div>
                                <div class="flex items-center text-sm text-white/70">
                                    <i class="fi fi-rr-clock mr-2"></i>
                                    {{ $article->published_at->diffForHumans() }}
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-[#1A3165] mb-3 line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($article->content, 150) }}
                            </p>
                            <a href="{{ route('public.news.show', $article) }}" 
                               class="inline-flex items-center text-[#1A3165] font-semibold hover:text-[#C8A165] transition-colors duration-200">
                                Read More
                                <i class="fi fi-rr-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fi fi-rr-newspaper text-6xl text-white/50 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">No News Available</h3>
                        <p class="text-white/70">Check back later for the latest updates.</p>
                    </div>
                @endforelse
            </div>

            @if($news->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="flex items-center space-x-2">
                        {{ $news->links() }}
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
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
