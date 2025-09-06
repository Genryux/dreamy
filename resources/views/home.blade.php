@extends('layouts.app')

@section('section_1')
    <div class="relative h-screen w-screen overflow-hidden flex flex-col justify-center items-center">

        <div class="w-full h-full">
            @if ($background)
                @php
                    $url = asset('storage/' . $background);
                    $ext = strtolower(pathinfo($background, PATHINFO_EXTENSION));
                @endphp

                @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                    <img src="{{ $url }}" class="background absolute inset-0 w-full h-full object-cover -z-20">
                @elseif(in_array($ext, ['mp4', 'mov']))
                    <video autoplay muted loop playsinline
                        class="background absolute inset-0 w-full h-full object-cover -z-20">
                        <source src="{{ $url }}" type="video/{{ $ext }}">
                    </video>
                @endif
            @endif
        </div>

        <div
            class="absolute inset-0 h-full w-full bg-gradient-to-b from-[#1A3165]/80 from-5% via-[#1A3165]/40 via-70% to-[#1A3165] to-90% -z-10">
            {{-- gradient filter on top of the video --}}
        </div>

        <div class="self-center flex flex-col justify-center items-center mb-24">
            <p class="relative z-10 text-white font-nunito text-[80px] font-black tracking-[8px] [text-shadow:2px_2px_8px_rgba(0,0,0,0.5)]"
                data-aos="fade-up" data-aos-duration="1000">
                Dreamy School
            </p>

            <p class="text-[40px] text-white tracking-[3px] leading-sm [text-shadow:2px_2px_8px_rgba(0,0,0,0.5)]"
                data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                Philippines</p>


        </div>

        {{-- line --}}
        <div class="absolute w-full bottom-0 left-1/2 transform -translate-x-1/2 flex flex-row items-center justify-center">
            <svg class="h-[60px] w-[1px] text-white flex flex-row justify-center items-center" viewBox="0 0 1 60"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                <!-- sharp top -->
                <polygon points="0,8 0.5,0 1,8" fill="currentColor" />
                <!-- line body -->
                <rect x="0" y="8" width="1" height="52" fill="currentColor" />
            </svg>
        </div>


    </div>
@endsection

@section('section_2')
    <div
        class="relative bg-[#1A3165] h-screen w-screen flex flex-row justify-between items-center overflow-hidden px-[120px]">


        <div class="flex-1 h-full w-full flex flex-col justify-center items-start gap-4">
            <div data-aos="fade-right" data-aos-duration="800" data-aos-delay="150">
                <h2 class="font-bold text-[32px] text-white">About us</h2>
                <div class="bg-[#C8A165] w-[100%] h-[5%]"></div>
            </div>
            <p class="text-[18px] pr-16" data-aos="fade-right" data-aos-duration="800" data-aos-delay="350">Lorem ipsum
                dolor sit amet consectetur adipisicing elit. Voluptatibus placeat quas
                perferendis
                repellendus
                eligendi porro ipsa commodi dicta veniam asperiores fugit quo in fugiat numquam rerum vel, reiciendis, sunt
                inventore!</p>
        </div>

        {{-- also line --}}
        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
            <span class="block border-l border-white h-full w-[1px]"></span>
        </div>

        <div class="flex-1 h-[500px] w-[500px] flex justify-center items-center pl-16" data-aos="fade-left"
            data-aos-duration="800" data-aos-delay="150">
            <div class="bg-white h-[90%] w-full rounded-xl shadow-xl overflow-hidden">
                <img src="{{ asset('images/Dreamy_logo.png') }}" class="w-full h-full object-cover" alt="">
            </div>
        </div>

    </div>
@endsection

@section('section_3')
    <div class="relative bg-white min-h-screen w-screen py-20 px-[120px]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[48px] text-[#1A3165] mb-4">Latest News & Announcements</h2>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-gray-600 max-w-2xl mx-auto">Stay updated with the latest news and announcements
                    from Dreamy School</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @forelse($news ?? [] as $article)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300"
                        data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fi fi-rr-calendar mr-2"></i>
                                    {{ $article->published_at->format('M d, Y') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fi fi-rr-clock mr-2"></i>
                                    {{ $article->published_at->diffForHumans() }}
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-[#1A3165] mb-3 line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ Str::limit($article->content, 120) }}
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
                        <i class="fi fi-rr-newspaper text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No News Available</h3>
                        <p class="text-gray-500">Check back later for the latest updates.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center" data-aos="fade-up" data-aos-duration="800">
                <a href="{{ route('public.news.index') }}"
                    class="inline-flex items-center bg-[#1A3165] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#C8A165] transition-colors duration-200">
                    View All News
                    <i class="fi fi-rr-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
