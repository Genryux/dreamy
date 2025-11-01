@extends('layouts.app', ['title' => 'Dreamy School'])

@section('section_1')
    <div
        class="relative h-[600px] md:h-screen w-screen overflow-hidden flex flex-col justify-center items-center pb-16 md:pb-[20px]">

        <div class="w-full h-full">
            {{-- Hard-coded video background to prevent corruption --}}
            <video autoplay muted loop playsinline
                class="pointer-events-none background absolute inset-0 w-full h-full object-cover object-center -z-20">
                {{-- <source src="{{ asset('storage/background/background.mp4') }}" type="video/mp4"> --}}
                <source src="{{ asset('storage/background/Dreamy Bg-1.mp4') }}" type="video/mp4">
                <source src="{{ asset('storage/background/Dreamy Bg-1.webm') }}" type="video/webm">
            </video>
        </div>

        {{-- @if ($background)
            @php
                $url = asset('storage/' . $background);
                $ext = strtolower(pathinfo($background, PATHINFO_EXTENSION));
            @endphp

            @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ $url }}" class="background absolute inset-0 w-full h-full object-cover -z-20">
            @elseif(in_array($ext, ['mp4', 'mov']))
                <video autoplay muted loop playsinline class="background absolute inset-0 w-full h-full object-cover -z-20">
                    <source src="{{ $url }}" type="video/{{ $ext }}">
                </video>
            @endif
        @endif --}}

        <div
            class="absolute inset-0 h-full w-full bg-gradient-to-b from-[#1A3165]/80 from-5% via-[#1A3165]/40 via-70% to-[#1A3165] to-95% -z-10">
            {{-- gradient filter on top of the video --}}
        </div>

        <div class="self-center flex flex-col justify-center items-center mb-20 md:mb-24 ">

            <p class="relative z-10 font-nunito text-[45px] md:text-[80px] font-black tracking-[8px] [text-shadow:2px_2px_8px_rgba(0,0,0,0.5)] text-white"
                data-aos="fade-up" data-aos-duration="1000">
                Dreamy School
            </p>

            <p class="text-[24px] md:text-[40px] text-white tracking-[3px] leading-sm [text-shadow:2px_2px_8px_rgba(0,0,0,0.5)]"
                data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                Philippines</p>

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
@endsection

@section('section_2')
    <div id="section2"
        class="relative bg-[#1A3165] h-1/2 md:h-screen w-screen flex flex-col md:flex-row justify-center md:justify-between items-center overflow-hidden gap-10 px-[20px] md:px-[120px]">


        <div class="md:flex-1 w-full flex flex-col justify-start items-center md:items-start gap-4">
            <div data-aos="fade-right" class="space-y-2" data-aos-duration="800" data-aos-delay="150">
                <h2 class="font-bold text-[32px] text-white">About us</h2>
                <div class="bg-[#C8A165] w-[100%] h-[5px]"></div>
            </div>
            <p class="text-[18px] md:pr-16 text-center md:text-start text-white" data-aos="fade-right"
                data-aos-duration="800" data-aos-delay="350">Lorem
                ipsum
                dolor sit amet consectetur adipisicing elit. Voluptatibus placeat quas
                perferendis
                repellendus
                eligendi porro ipsa commodi dicta veniam asperiores fugit quo in fugiat numquam rerum vel, reiciendis, sunt
                inventore!</p>
        </div>

        {{-- also line --}}
        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
            <span class="hidden md:block border-l border-white h-full w-[1px]"></span>
        </div>

        <div class="md:flex-1 flex justify-center rounded-xl overflow-hidden items-center md:ml-20 mb-20"
            data-aos="fade-left" data-aos-duration="800" data-aos-delay="150">
            <img src="{{ asset('images/ab.jpg') }}" class="w-full h-full object-contain" alt="">
        </div>

    </div>
@endsection

@section('section_3')
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

@section('section_4')
    <div class="relative bg-white min-h-screen w-screen py-20 px-[50px] md:px-[120px]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[32px] md:text-[48px] text-[#1A3165] mb-4">Academic Programs</h2>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-gray-600 max-w-2xl mx-auto">Discover our comprehensive academic programs designed
                    to prepare students for success</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @forelse($programs as $program)
                    @php
                        // Use actual track data if available, otherwise show program without track classification
                        if ($program->track) {
                            // Use real track data from database
                            $trackName = $program->track->name;
                            $trackGradient = $program->getTrackGradient();
                            $isGoldTrack = $program->isGoldTrack();
                            $trackDescription = $program->track->description;
                        } else {
                            // No track relationship - show program without track classification
                            $trackName = null;
                            $trackGradient = 'from-[#1A3165] to-[#2A4A7A]';
                            $isGoldTrack = false;
                            $trackDescription = $program->description ?? null;
                        }
                    @endphp

                    <div class="bg-gradient-to-br {{ $trackGradient }} rounded-xl p-8 text-white hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                        data-aos="fade-up" data-aos-duration="800" data-aos-delay="{{ $loop->index * 100 + 100 }}">


                        <div class="text-4xl mb-4">
                            <i class="{{ $program->track ? $program->getTrackIcon() : 'fi fi-rr-book' }}"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">{{ $program->name }}</h3>
                        @if ($trackName)
                            <p class="text-white/80 mb-2 text-sm font-medium">{{ $trackName }} Track</p>
                        @endif
                        @if ($trackDescription)
                            <p class="text-white/80 mb-6">{{ $trackDescription }}</p>
                        @endif
                        <a href="#"
                            class="inline-flex items-center {{ $isGoldTrack ? 'text-white hover:text-[#1A3165]' : 'text-[#C8A165] hover:text-white' }} font-semibold transition-colors duration-200">
                            Learn More <i class="fi fi-rr-arrow-right ml-2 flex justify-center items-center"></i>
                        </a>
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
                @if ($programs->count() > 0)
                    <a href="/portal/login"
                        class="inline-flex items-center bg-[#1A3165] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#C8A165] transition-colors duration-200">
                        Explore All {{ $programs->count() }} Programs <i
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
@endsection

@section('section_5')
    <div class="relative bg-[#1A3165] min-h-screen w-screen py-20 px-[50px] md:px-[120px]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[32px] md:text-[48px] text-white mb-4">Why Choose Dreamy School?</h2>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-white/80 max-w-2xl mx-auto">Discover what makes us the preferred choice for
                    quality education</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <img src="{{ asset('images/grad.jpg') }}"
                        class="background absolute inset-0 w-full h-full object-cover -z-10" alt="">

                    <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                        {{-- gradient filter on top of the video --}}
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Academic Excellence</h3>
                    <p class="text-white/80">Committed to providing world-class education with proven track record of
                        student success</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">


                    <img src="{{ asset('images/teaching.jpg') }}"
                        class="background absolute inset-0 w-full h-full object-cover -z-10" alt="">

                    <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                        {{-- gradient filter on top of the video --}}
                    </div>

                    <h3 class="text-2xl font-bold mb-4">Experienced Faculty</h3>
                    <p class="text-white/80">Dedicated and qualified teachers with years of experience in their respective
                        fields</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">

                    <img src="{{ asset('images/tech.jpg') }}"
                        class="background absolute inset-0 w-full h-full object-cover -z-10" alt="">

                    <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                        {{-- gradient filter on top of the video --}}
                    </div>

                    <h3 class="text-2xl font-bold mb-4">Modern Technology</h3>
                    <p class="text-white/80">State-of-the-art facilities and technology integration for 21st-century
                        learning</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">

                    <img src="{{ asset('images/guide.jpg') }}"
                        class="background absolute inset-0 w-full h-full object-cover -z-10" alt="">

                    <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                        {{-- gradient filter on top of the video --}}
                    </div>

                    <h3 class="text-2xl font-bold mb-4">Student Support</h3>
                    <p class="text-white/80">Comprehensive guidance, counseling, and support services for every student</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="500">

                    <img src="{{ asset('images/support.jpg') }}"
                        class="background absolute inset-0 w-full h-full object-cover -z-10" alt="">

                    <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                        {{-- gradient filter on top of the video --}}
                    </div>

                    <h3 class="text-2xl font-bold mb-4">Values & Character</h3>
                    <p class="text-white/80">Building strong character and values alongside academic achievement</p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white hover:bg-white/20 transition-all duration-300 overflow-hidden hover:scale-95 hover:-translate-y-2 pt-20"
                    data-aos="fade-up" data-aos-duration="800" data-aos-delay="600">


                    <img src="{{ asset('images/facility.jpg') }}"
                        class="background absolute inset-0 w-full h-full object-cover -z-10" alt="">

                    <div class="absolute inset-0 h-full w-full bg-gradient-to-b from-transparent to-gray-800 -z-10">
                        {{-- gradient filter on top of the video --}}
                    </div>

                    <h3 class="text-2xl font-bold mb-4">Modern Facilities</h3>
                    <p class="text-white/80">Well-equipped classrooms, laboratories, and learning spaces for optimal
                        education</p>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('section_6')
    <div class="relative bg-white min-h-screen w-screen py-20 px-[50px] md:px-[120px]">
        <div class="max-w-7xl mx-auto">

            <div class="w-full h-full">
                <img src="{{ asset('images/bizm.jpg') }}" class="background absolute inset-0 w-full h-full object-cover"
                    alt="">
            </div>

            <div
                class="absolute inset-0 h-full w-full bg-gradient-to-b from-[#1A3165] from-5% via-[#1A3165]/40 via-70% to-[#1A3165] to-90%">
                {{-- gradient filter on top of the video --}}
            </div>

            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[32px] md:text-[48px] text-white mb-4 z-10">Student Life & Achievements</h2>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-gray-400 max-w-2xl mx-auto">See what makes our school community special</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <!-- Student Testimonials -->
                <div data-aos="fade-right" data-aos-duration="800">
                    <h3 class="text-3xl font-bold text-white mb-8">What Our Students Say</h3>
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl p-6 backdrop-blur-lg bg-opacity-20">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 bg-[#1A3165] rounded-full flex items-center justify-center text-white font-bold">
                                    M</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-[#1A3165]">Maria Santos</h4>
                                    <p class="text-gray-300 text-sm">Grade 12 - STEM</p>
                                </div>
                            </div>
                            <p class="text-gray-50 italic">"Dreamy School has provided me with excellent academic
                                foundation and supportive teachers who believe in my potential."</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6 backdrop-blur-lg bg-opacity-20">
                            <div class="flex items-center mb-4">
                                <div
                                    class="w-12 h-12 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold">
                                    J</div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-[#1A3165]">John Dela Cruz</h4>
                                    <p class="text-gray-300 text-sm">Grade 11 - ABM</p>
                                </div>
                            </div>
                            <p class="text-gray-50 italic">"The modern facilities and technology integration have enhanced
                                my learning experience significantly."</p>
                        </div>
                    </div>
                </div>

                <!-- School Statistics -->
                <div data-aos="fade-left" data-aos-duration="800">
                    <h3 class="text-3xl font-bold text-white mb-8">School Statistics</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center bg-[#1A3165] rounded-xl p-6 text-white">
                            <div class="text-4xl font-bold mb-2">500+</div>
                            <div class="text-sm opacity-80">Active Students</div>
                        </div>
                        <div class="text-center bg-[#C8A165] rounded-xl p-6 text-white">
                            <div class="text-4xl font-bold mb-2">95%</div>
                            <div class="text-sm opacity-80">Graduation Rate</div>
                        </div>
                        <div class="text-center bg-[#1A3165] rounded-xl p-6 text-white">
                            <div class="text-4xl font-bold mb-2">50+</div>
                            <div class="text-sm opacity-80">Qualified Teachers</div>
                        </div>
                        <div class="text-center bg-[#C8A165] rounded-xl p-6 text-white">
                            <div class="text-4xl font-bold mb-2">15+</div>
                            <div class="text-sm opacity-80">Years of Excellence</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Extracurricular Activities -->
            <div class="text-center" data-aos="fade-up" data-aos-duration="800">
                <h3 class="text-3xl font-bold text-white mb-8">Extracurricular Activities</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div
                        class="bg-white rounded-xl flex flex-col justify-center items-center gap-4 p-6 transition-all duration-300 backdrop-blur-lg bg-opacity-20">
                        <img src="{{ asset('images/soccer-ball.png') }}" class="size-14" alt="">
                        <div class="font-semibold text-gray-50">Sports</div>
                    </div>
                    <div
                        class="bg-white rounded-xl flex flex-col justify-center items-center gap-4 p-6 transition-all duration-300 backdrop-blur-lg bg-opacity-20">
                        <img src="{{ asset('images/theater.png') }}" class="size-14" alt="">
                        <div class="font-semibold text-gray-50">Arts & Culture</div>
                    </div>
                    <div
                        class="bg-white rounded-xl flex flex-col justify-center items-center gap-4 p-6 transition-all duration-300 backdrop-blur-lg bg-opacity-20">
                        <img src="{{ asset('images/microscope.png') }}" class="size-14" alt="">
                        <div class="font-semibold text-gray-50">Science Club</div>
                    </div>
                    <div
                        class="bg-white rounded-xl flex flex-col justify-center items-center gap-4 p-6 transition-all duration-300 backdrop-blur-lg bg-opacity-20">
                        <img src="{{ asset('images/book.png') }}" class="size-14" alt="">
                        <div class="font-semibold text-gray-50">Debate Society</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('section_7')
    <div id="section7" class="relative bg-[#1A3165] min-h-screen w-screen py-20 px-[50px] md:px-[120px] scroll-smooth">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <h2 class="font-bold text-[32px] md:text-[48px] text-white mb-4">Ready to Start Your Journey?</h2>
                <div class="bg-[#C8A165] w-[200px] h-[4px] mx-auto mb-8"></div>
                <p class="text-[18px] text-white/80 max-w-2xl mx-auto">Join Dreamy School and be part of our community of
                    learners and achievers</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
                <!-- Admission Process -->
                <div data-aos="fade-right" data-aos-duration="800">
                    <h3 class="text-3xl font-bold text-white mb-8">Admission Process</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div
                                class="w-8 h-8 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-sm mr-4 mt-1 flex-shrink-0">
                                1</div>
                            <div>
                                <h4 class="font-semibold text-white mb-2">Submit Application</h4>
                                <p class="text-white/80">Complete the online application form with required documents</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="w-8 h-8 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-sm mr-4 mt-1 flex-shrink-0">
                                2</div>
                            <div>
                                <h4 class="font-semibold text-white mb-2">Document Review</h4>
                                <p class="text-white/80">Our admissions team will review your application and documents</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="w-8 h-8 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-sm mr-4 mt-1 flex-shrink-0">
                                3</div>
                            <div>
                                <h4 class="font-semibold text-white mb-2">Admission & Assessment</h4>
                                <p class="text-white/80">Schedule an Admission and assessment with our academic team</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="w-8 h-8 bg-[#C8A165] rounded-full flex items-center justify-center text-white font-bold text-sm mr-4 mt-1 flex-shrink-0">
                                4</div>
                            <div>
                                <h4 class="font-semibold text-white mb-2">Enrollment</h4>
                                <p class="text-white/80">Complete enrollment process and start your academic journey</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div data-aos="fade-left" data-aos-duration="800">
                    <h3 class="text-3xl font-bold text-white mb-8">Get in Touch</h3>
                    <div class="space-y-6">
                        <div class="flex flex-row justify-center items-center">
                            <div
                                class="w-12 h-12 bg-[#C8A165] rounded-full flex items-center justify-center flex-shrink-0 mr-4">
                                <i class="fi fi-rr-marker text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white">Address</h4>
                                <p class="text-white/80">Lot 23 Block 2 PSD 56216 Sitio Tanag, Brgy, San Isidro Rodriguez,
                                    Rizal, Philippines</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-[#C8A165] rounded-full flex items-center justify-center mr-4">
                                <i class="fi fi-rr-phone-call text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white">Phone</h4>
                                <p class="text-white/80">+63 917 630 0777</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-[#C8A165] rounded-full flex items-center justify-center mr-4">
                                <i class="fi fi-rr-envelope text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-white">Email</h4>
                                <p class="text-white/80">ph@dreamyedu.net</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Buttons -->
            <div class="text-center space-y-4" data-aos="fade-up" data-aos-duration="800">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/portal/register"
                        class="inline-flex items-center justify-center bg-[#C8A165] text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-[#1A3165] transition-all duration-300 text-lg">
                        Apply Now <i class="fi fi-rr-arrow-right ml-2 flex justify-center items-center"></i>
                    </a>
                    <a href="/portal/login"
                        class="inline-flex items-center justify-center border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-[#1A3165] transition-all duration-300 text-lg">
                        Student Portal <i class="fi fi-rr-user ml-2 flex justify-center items-center"></i>
                    </a>
                </div>
                <p class="text-white/60 text-sm">Have questions? Contact our admissions office for assistance</p>
            </div>
        </div>
    </div>
@endsection
