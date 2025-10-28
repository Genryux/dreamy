@extends('layouts.app')

@section('login_page')
    <div class="relative w-screen h-screen ">

        <div class="absolute top-10 left-20 text-white hidden md:flex flex-row justify-center items-center" data-aos="fade-left" data-aos-duration="1000">
            <a href="/" class="hover:-translate-x-4 transition duration-200 flex flex-row justify-center items-center"><i class="fi fi-rr-angle-left text-gray-300 text-[32px] flex flex-row justify-center items-center"></i>Back to Homepage</a>
        </div>

        <div class=" h-full flex flex-col md:flex-row justify-center items-center text-white">
            <div class="flex-1 w-full h-full flex flex-col justify-center items-center space-y-4">
                <div class="flex flex-col justify-center items-center">
                    <p class="text-[18px] md:text-[24px]" data-aos="fade-up" data-aos-duration="800">Welcome to Dreamy School</p>
                    <p class="text-[30px] md:text-[46px] font-bold text-[#C8A165]" data-aos="fade-up" data-aos-duration="900">Create Your Account</p>
                </div>
                <img src="{{ asset('images/Dreamy_logo.png') }}" data-aos="fade-up" data-aos-duration="1000" class="h-1/3 w-1/3 hidden md:block" alt="Dreamy School logo">
            </div>
            <div class="flex-1  w-full h-full flex flex-col justify-center items-center">

                <form method="POST" action="/register" class="flex flex-col justify-center items-start w-full md:w-1/2 max-w-md px-4 space-y-4 ">
                    @csrf
                    <div class="flex flex-col justify-center items-start gap-4 ">

                        <h1 class="font-bold text-[28px]" data-aos="fade-up" data-aos-duration="800">Sign Up</h1>

                        <p class="text-gray-400 font-medium text-[14px]" data-aos="fade-up" data-aos-duration="800">Already have an account?
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="text-[#C8A165] ">Log in here</a>
                            @else
                                <a href="{{ url('/portal/login') }}" class="text-[#C8A165] ">Log in here</a>
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-col justify-center items-end space-y-6 w-full">

                        <div class="border-b border-gray-900/10 w-full space-y-4">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="sm:col-span-4 md:col-span-1 w-full text-white space-y-4" data-aos="fade-up" data-aos-duration="900">
                                    <label for="first_name" class="block font-semibold text-[16px] ">First Name</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center rounded-md bg-transparent pl-2 border-2 border-white/60">
                                            <input type="text" name="first_name" id="first_name"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Enter your first name..." value="{{ old('first_name') }}" autocomplete="given-name" required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="sm:col-span-4 md:col-span-1 w-full text-white space-y-4" data-aos="fade-up" data-aos-duration="900">
                                    <label for="last_name" class="block font-semibold text-[16px] ">Last Name</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center rounded-md bg-transparent pl-2 border-2 border-white/60">
                                            <input type="text" name="last_name" id="last_name"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Enter your last name..." value="{{ old('last_name') }}" autocomplete="family-name" required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="sm:col-span-4 md:col-span-2 w-full text-white space-y-4" data-aos="fade-up" data-aos-duration="900">
                                    <label for="email" class="block font-semibold text-[16px] ">Email</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center rounded-md bg-transparent pl-2 border-2 border-white/60">
                                            <input type="email" name="email" id="email"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Enter your email..." value="{{ old('email') }}" autocomplete="email" @error('email') aria-invalid="true" @enderror required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="flex flex-col w-full space-y-4 md:col-span-1" data-aos="fade-up" data-aos-duration="1000">
                                    <label for="password" class="block text-[16px] font-semibold text-white">Password</label>
                                    <div class="mt-2 space-y-2 w-full ">
                                        <div class="flex items-center rounded-md bg-transparent pl-2 border-2 border-white/60">
                                            <input type="password" name="password" id="password"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Enter your password..." autocomplete="new-password" @error('password') aria-invalid="true" @enderror required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="flex flex-col w-full space-y-4 md:col-span-1" data-aos="fade-up" data-aos-duration="1000">
                                    <label for="password_confirmation" class="block text-[16px] font-semibold text-white">Confirm Password</label>
                                    <div class="mt-2 space-y-2 w-full ">
                                        <div class="flex items-center rounded-md bg-transparent pl-2 border-2 border-white/60">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Confirm your password..." autocomplete="new-password" required>
                                        </div>
                                        
                                    </div>
                                </div>

                                @if ($errors->any())
                                    <div class="md:col-span-2 w-full" data-aos="fade-up" data-aos-duration="1000">
                                        <div class="mt-2">
                                            <div class="rounded-md border-2 border-red-400/60 bg-red-900/10 text-red-200 px-3 py-2">
                                                <ul class="list-disc ml-5 space-y-1">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="flex flex-col justify-center items-center gap-y-6 w-full">
                            <button type="submit"
                                class="rounded-xl bg-[#199BCF] px-3 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full shadow-xl" data-aos="fade-up" data-aos-duration="1000">Register</button>

                        </div>

                    </div>



                </form>
            </div>
        </div>

        <div
            class="absolute inset-0 h-full w-full bg-gradient-to-b from-[#1A3165]/80 from-10% via-[#1A3165]/85 via-40% to-[#1A3165] to-90% -z-10">
            {{-- gradient filter on top of the video --}}
        </div>

        <div class="w-full h-full">
            <img src="{{ asset('images/dreamy_bg.png') }}" alt=""
                class="background absolute inset-0 w-full h-full object-cover -z-20">
        </div>


    </div>
@endsection


