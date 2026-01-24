@extends('layouts.app', ['title' => 'Dreamy School'])

@section('hero')
    @if($hero && $hero->is_active)
        <div id="home"
            class="relative h-[600px] md:h-screen w-screen overflow-hidden flex flex-col justify-center items-center pb-16 md:pb-[20px]">

            <div class="w-full h-full">
                @if($hero->background_type === 'video' && $hero->background_video_path)
                    <video autoplay muted loop playsinline
                        class="pointer-events-none background absolute inset-0 w-full h-full object-cover object-center -z-20">
                        <source src="{{ asset('storage/' . $hero->background_video_path) }}" type="video/mp4">
                    </video>
                @elseif($hero->background_type === 'image' && $hero->background_image_path)
                    <img src="{{ asset('storage/' . $hero->background_image_path) }}" 
                        class="background absolute inset-0 w-full h-full object-cover -z-20">
                @endif
            </div>

            <div
                class="absolute inset-0 h-full w-full bg-gradient-to-b from-[#1A3165]/80 from-5% via-[#1A3165]/40 via-70% to-[#1A3165] to-95% -z-10">
                {{-- gradient filter on top of the video --}}
            </div>

            <div class="self-center flex flex-col justify-center items-center mb-20 md:mb-24 ">

                <p class="relative z-10 font-nunito text-[42px] self-center text-center md:text-[80px] font-black tracking-[8px] [text-shadow:2px_2px_8px_rgba(0,0,0,0.5)] text-white"
                    data-aos="fade-up" data-aos-duration="1000">
                    {{ $hero->title }}
                </p>

                <p class="text-[24px] md:text-[40px] text-white tracking-[3px] leading-sm [text-shadow:2px_2px_8px_rgba(0,0,0,0.5)]"
                    data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    {{ $hero->subtitle }}</p>

            </div>

            {{-- line --}}
            <div
                class="hidden absolute w-full bottom-0 left-1/2 transform -translate-x-1/2 md:flex flex-row items-center justify-center">
                <svg class="h-[60px] w-[1px] text-white flex flex-row justify-center items-center" viewBox="0 0 1 60"
                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img">
                    <!-- sharp top -->
                    <polygon points="0,8 0.5,0 1,8" fill="currentColor" />
                    <!-- line body -->
                    <rect x="0" y="8" width="1" height="52" fill="currentColor" />
                </svg>
            </div>

        </div>
    @endif
@endsection

@section('about_us')
    @if($about && $about->is_active)
        <div id="about"
            class="relative bg-[#1A3165] h-1/2 md:h-screen w-screen flex flex-col md:flex-row justify-center md:justify-between items-center overflow-hidden gap-10 px-[20px] md:px-[120px]">


            <div class="md:flex-1 w-full flex flex-col justify-start items-center md:items-start gap-4">
                <div data-aos="fade-right" class="space-y-2" data-aos-duration="800" data-aos-delay="150">
                    <h2 class="font-bold text-[32px] text-white">{{ $about->heading }}</h2>
                    <div class="bg-[#C8A165] w-[100%] h-[5px]"></div>
                </div>
                <p class="text-[18px] md:pr-16 text-center md:text-start text-white" data-aos="fade-right"
                    data-aos-duration="800" data-aos-delay="350">{{ $about->description }}</p>
            </div>

            {{-- also line --}}
            <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                <span class="hidden md:block border-l border-white h-full w-[1px]"></span>
            </div>

            <div class="md:flex-1 flex justify-center rounded-xl overflow-hidden items-center md:ml-20"
                data-aos="fade-left" data-aos-duration="800" data-aos-delay="150">
                <img src="{{ asset('storage/' . $about->image_path) }}" class="w-full max-h-[500px] object-contain" alt="{{ $about->heading }}">
            </div>

        </div>
    @endif
@endsection

@section('mission_values')
    @if($missionValues && $missionValues->is_active)
        <!-- Our Mission & Values Section -->
        <div class="relative bg-[#F8F8F8] w-screen md:h-screen py-16 px-[20px] md:px-[120px]">
            <div class="h-full w-full mx-auto md:flex md:flex-col md:justify-center md:items-center">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="font-bold text-[28px] md:text-[40px] text-[#1A3165] mb-2">{{ $missionValues->heading }}</h2>
                    <div class="bg-[#C8A165] w-[120px] h-[4px] mx-auto mb-6"></div>
                    <p class="text-[16px] md:text-[20px] text-gray-600 max-w-2xl mx-auto">{{ $missionValues->description }}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($missionValues->items as $index => $item)
                        <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center" 
                            data-aos="fade-up" data-aos-duration="{{ 600 + ($index * 200) }}">
                            <i class="{{ $item->icon }} text-3xl mb-4" style="color: {{ $item->color }}"></i>
                            <h3 class="text-lg font-bold mb-2" style="color: {{ $item->color }}">{{ $item->title }}</h3>
                            <p class="text-gray-600">{{ $item->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection

@section('glance')
    @if($schoolAtGlance && $schoolAtGlance->is_active)
        <div id="glance-section" class="relative bg-[#C8A165] w-screen py-12 px-[20px] md:px-[120px]">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="font-bold text-[28px] md:text-[40px] text-[#f8f8f8] mb-2">{{ $schoolAtGlance->heading }}</h2>
                    <div class="bg-[#C8A165] w-[120px] h-[4px] mx-auto mb-6"></div>
                    <p class="text-[16px] md:text-[20px] text-gray-200 max-w-2xl mx-auto">{{ $schoolAtGlance->description }}</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($schoolAtGlance->items as $item)
                        <div class="w-[200px] flex flex-col flex-shrink-0 aspect-square items-center justify-center rounded-full shadow-lg"
                            style="background-color: {{ $item->bg_color }}; color: {{ $item->text_color }};">
                            <div class="count-up text-[50px] font-bold mb-2" 
                                 data-target="{{ preg_replace('/[^0-9]/', '', $item->value) }}"
                                 data-suffix="{{ preg_replace('/[0-9]/', '', $item->value) }}">0</div>
                            <div class="text-sm opacity-80">{{ $item->label }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const counters = document.querySelectorAll('.count-up');
                let hasAnimated = false;

                function animateCounters() {
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target')) || 0;
                        const suffix = counter.getAttribute('data-suffix') || '';
                        const duration = 2000; // 2 seconds
                        const steps = 60;
                        const stepTime = duration / steps;
                        let current = 0;
                        const increment = target / steps;

                        const updateCounter = () => {
                            current += increment;
                            if (current < target) {
                                counter.textContent = Math.floor(current) + suffix;
                                setTimeout(updateCounter, stepTime);
                            } else {
                                counter.textContent = target + suffix;
                            }
                        };

                        updateCounter();
                    });
                }

                // Intersection Observer to trigger animation when section is visible
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !hasAnimated) {
                            hasAnimated = true;
                            animateCounters();
                        }
                    });
                }, {
                    threshold: 0.3 // Trigger when 30% of the section is visible
                });

                const glanceSection = document.getElementById('glance-section');
                if (glanceSection) {
                    observer.observe(glanceSection);
                }
            });
        </script>
    @endif
@endsection

@section('academic_programs')
    @if($academicPrograms && $academicPrograms->is_active)
        <div class="relative bg-white min-h-screen w-screen py-20 px-[50px] md:px-[120px]">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                    <h2 class="font-bold text-[32px] md:text-[48px] text-[#1A3165] mb-4">{{ $academicPrograms->heading }}</h2>
                    <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                    <p class="text-[18px] text-gray-600 max-w-2xl mx-auto">{{ $academicPrograms->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @forelse($academicPrograms->items as $program)
                        <div class="bg-gradient-to-br from-[{{ $program->gradient_from }}] to-[{{ $program->gradient_to }}] rounded-xl p-8 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 relative"
                            style="background: linear-gradient(to bottom right, {{ $program->gradient_from }}, {{ $program->gradient_to }});"
                            data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $loop->index * 100 + 100 }}">
                            
                            @if($program->status === 'coming_soon')
                                <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    Coming Soon
                                </div>
                            @endif

                            <h3 class="text-2xl font-bold mb-4">{{ $program->title }}</h3>
                            @if($program->track_name)
                                <p class="text-white/80 mb-2 text-sm font-medium">{{ $program->track_name }} Track</p>
                            @endif
                            <p class="text-white/80 mb-6">{{ $program->description }}</p>
                            
                            @if($program->status === 'active')
                                <a href="{{ $program->link_url ?? '#' }}"
                                    class="inline-flex items-center {{ $program->isGoldTrack() ? 'text-white hover:text-[#1A3165]' : 'text-[#C8A165] hover:text-white' }} font-semibold transition-colors duration-200">
                                    Learn More <i class="fi fi-rr-arrow-right ml-2 flex justify-center items-center"></i>
                                </a>
                            @else
                                <span class="inline-flex items-center text-white/60 font-semibold">
                                    Available Soon
                                </span>
                            @endif
                        </div>
                    @empty
                        <!-- Fallback content when no programs are available -->
                        <div class="col-span-full text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">📚</div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">Programs Coming Soon</h3>
                            <p class="text-gray-500">We're preparing exciting academic programs for you.</p>
                        </div>
                    @endforelse
                </div>

                <div class="text-center" data-aos="fade-up" data-aos-duration="800">
                    @php
                        $activeCount = $academicPrograms->items->where('status', 'active')->count();
                    @endphp
                    @if($activeCount > 0)
                        <a href="/portal/login"
                            class="inline-flex items-center bg-[#1A3165] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#C8A165] transition-colors duration-200">
                            Explore All {{ $activeCount }} Programs <i
                                class="fi fi-rr-arrow-right ml-2 flex justify-center items-center"></i>
                        </a>
                    @else
                        <a href="/portal/register"
                            class="inline-flex items-center bg-[#1A3165] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#C8A165] transition-colors duration-200">
                            Apply Now <i class="fi fi-rr-arrow-right ml-2 flex justify-center items-center"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@section('reason')
    @if($reasonSection && $reasonSection->is_active)
    <div class="relative bg-[#1A3165] min-h-screen w-screen py-20 px-[50px] md:px-[120px]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[32px] md:text-[48px] text-white mb-4">{{ $reasonSection->heading }}</h2>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-white/80 max-w-2xl mx-auto">{{ $reasonSection->description }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @php
                    // Fallback images for items without uploaded images
                    $fallbackImages = [
                        'images/grad.jpg',
                        'images/teaching.jpg',
                        'images/tech.jpg',
                        'images/guide.jpg',
                        'images/support.jpg',
                        'images/facility.jpg',
                    ];
                @endphp
                @foreach($reasonSection->items as $index => $item)
                    @php
                        $imageUrl = $item->image ? asset('storage/' . $item->image) : asset($fallbackImages[$index % count($fallbackImages)]);
                        $delay = 100 + ($index * 100);
                    @endphp
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                        data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $delay }}">
                        <img src="{{ $imageUrl }}"
                            class="background absolute inset-0 w-full h-full object-cover -z-10" alt="{{ $item->title }}">

                        <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                            {{-- gradient filter on top of the image --}}
                        </div>
                        <h3 class="text-2xl font-bold mb-4">{{ $item->title }}</h3>
                        <p class="text-white/80">{{ $item->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection

@section('alumni')
    <!-- Success Stories / Alumni Spotlight -->
    @if($alumniSection && $alumniSection->is_active)
    <div class="relative w-screen min-h-screen py-16 px-[20px] md:px-[120px]">
        <!-- Background Image -->
        <div class="absolute inset-0 -z-20">
            <img src="{{ $alumniSection->getBackgroundImageUrl() }}" class="w-full h-full object-cover" alt="Campus Background">
        </div>
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#C8A165] via-[#C8A165]/70 to-transparent -z-10"></div>

        <div class="max-w-6xl mx-auto relative z-10">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[28px] md:text-[40px] text-white mb-2">{{ $alumniSection->heading }}</h2>
                <div class="bg-white w-[120px] h-[4px] mx-auto mb-6"></div>
                <p class="text-[16px] md:text-[20px] text-white max-w-2xl mx-auto">{{ $alumniSection->description }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $fallbackPhotos = ['images/alumni1.jpg', 'images/alumni2.jpg', 'images/alumni3.jpg'];
                @endphp
                @foreach($alumniSection->items as $index => $alumni)
                    @php
                        $photoUrl = $alumni->photo ? asset('storage/' . $alumni->photo) : asset($fallbackPhotos[$index % count($fallbackPhotos)]);
                    @endphp
                    <div class="bg-white rounded-xl shadow-lg p-8 flex flex-col items-center text-center" data-aos="fade-up"
                        data-aos-duration="800">
                        <img src="{{ $photoUrl }}" class="w-24 h-24 rounded-full mb-4 object-cover"
                            alt="{{ $alumni->name }}">
                        <h3 class="text-xl font-bold text-[#1A3165] mb-1">{{ $alumni->name }}</h3>
                        <p class="text-[#C8A165] text-sm mb-2">{{ $alumni->getClassInfo() }}</p>
                        <p class="text-gray-600 mb-4">{{ $alumni->quote }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection

@section('campus_tour')
    <!-- Virtual Tour / Campus Gallery -->
    @if($campusTour && $campusTour->is_active)
    <div class="relative bg-white w-screen py-16 px-[20px] md:px-[120px]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[28px] md:text-[40px] text-[#1A3165] mb-2">{{ $campusTour->heading }}</h2>
                <div class="bg-[#C8A165] w-[120px] h-[4px] mx-auto mb-6"></div>
                <p class="text-[16px] md:text-[20px] text-gray-600 max-w-2xl mx-auto">{{ $campusTour->description }}</p>
            </div>

            <!-- Carousel Container -->
            <div id="campus-tour-carousel" class="relative">
                <!-- Slides -->
                <div class="tour-slides relative overflow-hidden rounded-2xl bg-gray-50 shadow-xl">
                    @php
                        $defaultImages = [
                            'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1544717302-de2939b7ef71?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1567168539593-59673ababaae?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=800&q=80',
                            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80',
                        ];
                    @endphp
                    @foreach($campusTour->items as $index => $item)
                        @php
                            $imageUrl = $item->image ? asset('storage/' . $item->image) : ($defaultImages[$index % count($defaultImages)] ?? $defaultImages[0]);
                        @endphp
                        <div class="tour-slide {{ $index === 0 ? 'active' : '' }} flex flex-col md:flex-row items-center gap-8 p-8 md:p-12">
                            <div class="w-full md:w-1/2">
                                <img src="{{ $imageUrl }}"
                                    class="w-full h-[300px] md:h-[400px] object-cover rounded-xl shadow-lg"
                                    alt="{{ $item->title }}">
                            </div>
                            <div class="w-full md:w-1/2 space-y-4">
                                <h3 class="text-2xl md:text-3xl font-bold text-[#1A3165]">{{ $item->title }}</h3>
                                <p class="text-gray-600 text-lg">{{ $item->description }}</p>
                                @if($item->highlight)
                                    <div class="flex items-center text-[#C8A165] font-semibold">
                                        <i class="fi {{ $item->icon ?? 'fi-rr-marker' }} mr-2"></i>
                                        <span>{{ $item->highlight }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Arrows -->
                <button type="button" id="tour-prev"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white hover:bg-[#1A3165] text-[#1A3165] hover:text-white flex items-center justify-center rounded-full shadow-lg transition-all duration-200 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button type="button" id="tour-next"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white hover:bg-[#1A3165] text-[#1A3165] hover:text-white flex items-center justify-center rounded-full shadow-lg transition-all duration-200 z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Slide Indicators -->
                <div class="flex justify-center gap-2 mt-8">
                    @foreach($campusTour->items as $index => $item)
                    <button class="tour-indicator {{ $index === 0 ? 'active' : '' }} w-3 h-3 rounded-full {{ $index === 0 ? 'bg-[#1A3165]' : 'bg-gray-300 hover:bg-[#C8A165]' }} transition-all duration-200"
                        data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .tour-slide {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .tour-slide.active {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .tour-indicator.active {
            width: 2rem;
            background-color: #1A3165;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.tour-slide');
            const indicators = document.querySelectorAll('.tour-indicator');
            const prevBtn = document.getElementById('tour-prev');
            const nextBtn = document.getElementById('tour-next');
            let currentSlide = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    if (i === index) slide.classList.add('active');
                });
                indicators.forEach((indicator, i) => {
                    indicator.classList.remove('active');
                    if (i === index) indicator.classList.add('active');
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }

            prevBtn.addEventListener('click', prevSlide);
            nextBtn.addEventListener('click', nextSlide);

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });

            // Auto-advance every 5 seconds
            setInterval(nextSlide, 5000);
        });
    </script>
@endsection

@section('how_to_apply')
    <!-- How to Apply / Admissions Steps -->
    @if($howToApply && $howToApply->is_active)
    <div class="relative bg-[#1A3165] w-screen py-16 px-[20px] md:px-[120px]">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[28px] md:text-[40px] text-[#f8f8f8] mb-2">{{ $howToApply->heading }}</h2>
                <div class="bg-[#C8A165] w-[120px] h-[4px] mx-auto mb-6"></div>
                <p class="text-[16px] md:text-[20px] text-gray-500 max-w-2xl mx-auto">{{ $howToApply->description }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-{{ min($howToApply->steps->count(), 4) }} gap-8">
                @foreach($howToApply->steps as $step)
                <div class="flex flex-col items-center bg-white rounded-xl shadow-lg p-8">
                    <div
                        class="w-12 h-12 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-lg mb-4">
                        {{ $step->step_number }}</div>
                    <h3 class="text-lg font-bold text-[#1A3165] mb-2">{{ $step->title }}</h3>
                    <p class="text-gray-600 text-center">{{ $step->description }}</p>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ $howToApply->button_link }}"
                    class="inline-flex items-center bg-[#C8A165] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#1A3165] transition-colors duration-200">
                    {{ $howToApply->button_text }} <i class="fi fi-rr-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('news_announcement')
    <div class="relative bg-white min-h-screen w-screen py-20 px-[50px] md:px-[120px]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[32px] md:text-[48px] text-[#1A3165] mb-4">Latest News & Announcements</h2>
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

@section('footer')
    <footer class="bg-[#1A3165] w-screen">
        <!-- Main Footer Content -->
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Left Column: School Info & Quick Links -->
                <div class="lg:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- School Info -->
                        <div class="md:col-span-1">
                            <div class="flex items-center gap-3 mb-6">
                                <img src="{{ asset('images/Dreamy_logo.png') }}" alt="Dreamy School Logo"
                                    class="h-16 w-16">
                                <div>
                                    <h3 class="text-white font-bold text-lg">Dreamy School</h3>
                                    <p class="text-[#C8A165] text-sm">Philippines</p>
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm leading-relaxed mb-6">
                                Nurturing minds, building futures. Dreamy School Philippines is committed to providing quality education that empowers students to achieve their dreams.
                            </p>
                            <!-- Social Links -->
                            <div class="flex gap-3">
                                <a href="https://www.facebook.com/dreamyschoolph" rel="noreferrer" target="_blank"
                                    class="w-10 h-10 bg-white/10 hover:bg-[#C8A165] rounded-full flex items-center justify-center text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <a href="https://www.instagram.com/dreamyschoolph/" rel="noreferrer" target="_blank"
                                    class="w-10 h-10 bg-white/10 hover:bg-[#C8A165] rounded-full flex items-center justify-center text-white transition-all duration-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <a href="mailto:info@dreamyschool.ph" 
                                    class="w-10 h-10 bg-white/10 hover:bg-[#C8A165] rounded-full flex items-center justify-center text-white transition-all duration-300">
                                    <i class="fi fi-rr-envelope flex items-center justify-center"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div>
                            <h4 class="text-white font-bold text-lg mb-6 flex items-center">
                                <span class="w-8 h-[2px] bg-[#C8A165] mr-3"></span>
                                Quick Links
                            </h4>
                            <ul class="space-y-3">
                                <li>
                                    <a href="#home" class="text-gray-400 hover:text-[#C8A165] transition-colors duration-200 flex items-center group">
                                        <i class="fi fi-rr-angle-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a href="#about" class="text-gray-400 hover:text-[#C8A165] transition-colors duration-200 flex items-center group">
                                        <i class="fi fi-rr-angle-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                        About Us
                                    </a>
                                </li>
                                <li>
                                    <a href="#programs" class="text-gray-400 hover:text-[#C8A165] transition-colors duration-200 flex items-center group">
                                        <i class="fi fi-rr-angle-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                        Programs
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('public.news.index') }}" class="text-gray-400 hover:text-[#C8A165] transition-colors duration-200 flex items-center group">
                                        <i class="fi fi-rr-angle-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                        News & Events
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('login') }}" class="text-gray-400 hover:text-[#C8A165] transition-colors duration-200 flex items-center group">
                                        <i class="fi fi-rr-angle-right text-xs mr-2 group-hover:translate-x-1 transition-transform"></i>
                                        Admission Portal
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Contact Info -->
                        <div>
                            <h4 class="text-white font-bold text-lg mb-6 flex items-center">
                                <span class="w-8 h-[2px] bg-[#C8A165] mr-3"></span>
                                Contact Us
                            </h4>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-[#C8A165]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fi fi-rr-marker text-[#C8A165] text-sm flex items-center justify-center"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">Address</p>
                                        <p class="text-gray-400 text-sm">Lot 23 Block 2 PSD 56216 Sitio Tanag, Brgy, San Isidro Rodriguez, Rizal, Philippines</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-[#C8A165]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fi fi-rr-phone-call text-[#C8A165] text-sm flex items-center justify-center"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">Phone</p>
                                        <a href="tel:+639123456789" class="text-gray-400 text-sm hover:text-[#C8A165] transition-colors">+63 917 630 0777</a>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-[#C8A165]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fi fi-rr-envelope text-[#C8A165] text-sm flex items-center justify-center"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">Email</p>
                                        <a href="mailto:ph@dreamyedu.net" class="text-gray-400 text-sm hover:text-[#C8A165] transition-colors">ph@dreamyedu.net</a>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-[#C8A165]/20 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fi fi-rr-clock text-[#C8A165] text-sm flex items-center justify-center"></i>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">Office Hours</p>
                                        <p class="text-gray-400 text-sm">Mon - Fri: 7:00 AM - 5:00 PM</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Map -->
                <div class="lg:col-span-1">
                    <h4 class="text-white font-bold text-lg mb-6 flex items-center">
                        <span class="w-8 h-[2px] bg-[#C8A165] mr-3"></span>
                        Find Us
                    </h4>
                    <div class="rounded-xl overflow-hidden shadow-xl border-4 border-white/10">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3913.5198869544997!2d121.1463245!3d14.7544682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397a500593df367%3A0x6459a4ed7c24d2a3!2sDREAMY%20SCHOOL%20PHILIPPINES!5e1!3m2!1sen!2sph!4v1767610787863!5m2!1sen!2sph" 
                            width="100%" 
                            height="280" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="w-full">
                        </iframe>
                    </div>
                    <a href="https://maps.google.com/?q=DREAMY+SCHOOL+PHILIPPINES" target="_blank" rel="noopener noreferrer"
                        class="mt-4 inline-flex items-center justify-center w-full bg-[#C8A165] hover:bg-[#B8915A] text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300">
                        <i class="fi fi-rr-marker mr-2 flex items-center justify-center"></i>
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Download Apps Section -->
        <div class="bg-[#142850] py-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <h4 class="text-white font-bold text-lg mb-1">Download Our Mobile App</h4>
                        <p class="text-gray-400 text-sm">Stay connected with Dreamy School on the go</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href='{{ asset('apk/application-e7b64287-1960-4774-a106-378d55079c78.apk') }}' download
                            class="flex items-center gap-3 px-5 py-3 rounded-xl bg-gradient-to-r from-[#199BCF] to-[#1A7BA8] hover:from-[#1A7BA8] hover:to-[#199BCF] text-white transition-all duration-300 shadow-lg shadow-[#199BCF]/30">
                            <i class="fi fi-brands-android text-2xl flex items-center justify-center"></i>
                            <div class="text-left">
                                <p class="text-[10px] text-white/70 uppercase tracking-wide">Download for</p>
                                <p class="text-sm font-semibold">Android (Students & Teachers)</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Copyright Bar -->
        <div class="bg-[#0F1D32] py-4">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
                    <p class="text-gray-500">
                        © {{ date('Y') }} Dreamy School Philippines. All rights reserved.
                    </p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-gray-500 hover:text-[#C8A165] transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-500 hover:text-[#C8A165] transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-500 hover:text-[#C8A165] transition-colors">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection
