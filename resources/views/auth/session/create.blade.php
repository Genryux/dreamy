@extends('layouts.app')

@section('login_page')
    <div class="relative w-screen h-screen ">

        <div class="absolute top-10 left-20 hidden md:flex flex-row justify-center items-center" data-aos="fade-left" data-aos-duration="1000">
            <a href="/" class="hover:-translate-x-4 text-white transition duration-200 flex flex-row justify-center items-center">
                <i class="fi fi-rr-angle-left text-gray-300 text-[32px] flex flex-row justify-center items-center"></i>
                Back to Homepage
            </a>
        </div>


        <div class=" h-full flex flex-col md:flex-row justify-center items-center text-white">
            <div class="flex-1 w-full h-full flex flex-col justify-center items-center space-y-4">
                <div class="flex flex-col justify-center items-center">
                    <p class="text-[18px] md:text-[24px]" data-aos="fade-up" data-aos-duration="800">Welcome to Dreamy School</p>
                    <p class="text-[30px] md:text-[46px] font-bold text-[#C8A165]" data-aos="fade-up" data-aos-duration="900">Unified Access Portal</p>
                </div>
                <img src="{{ asset('images/Dreamy_logo.png') }}" data-aos="fade-up" data-aos-duration="1000" class="h-1/3 w-1/3 hidden md:block" alt="Dreamy School logo">
            </div>
            <div class="flex-1  w-full h-full flex flex-col justify-center items-center">

                <form action="/session" method="post" class="w-full md:w-1/2 max-w-md px-4 space-y-4">
                    @csrf
                    <div class="flex flex-col justify-center items-start gap-4">

                        <h1 class="font-bold text-[28px]" data-aos="fade-up" data-aos-duration="800">Log In</h1>

                        @if (session('success'))
                            <div class="w-full bg-green-50 border border-green-200 rounded-lg p-3 mb-4"  data-aos="fade-up" data-aos-duration="800">
                                <div class="flex items-center">
                                    <i class="fi fi-rr-check-circle flex justify-center items-center text-green-500 mr-2"></i>
                                    <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        <p class="text-gray-400 font-medium text-[14px]" data-aos="fade-up" data-aos-duration="800">Don't have an account yet?
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-[#C8A165] ">Sign Up here</a>
                            @endif
                        </p>
                    </div>
                    <div class="space-y-6 w-full">

                        <div class="border-b border-gray-900/10 w-full space-y-4">

                            <div class="flex flex-col justify-center items-start space-y-4">
                                <div class="sm:col-span-4 w-full text-white space-y-4" data-aos="fade-up" data-aos-duration="900">
                                    <label for="email" class="block font-semibold text-[16px] ">Email</label>
                                    <div class="mt-2 space-y-2">
                                        <div
                                            class="flex items-center rounded-md bg-transparent pl-3 border-2 border-white/60">
                                            <input type="email" name="email" id="email"
                                                class="block min-w-0 grow mb-1 py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Enter your email..." value="{{ old('email') }}" autocomplete="email" autofocus @error('email') aria-invalid="true" @enderror required>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="flex flex-col w-full space-y-4" data-aos="fade-up" data-aos-duration="1000">
                                    <div class="flex flex-row justify-between items-center ">
                                        <label for="password"
                                            class="block text-[16px] font-semibold text-white">Password</label>
                                        <a href="{{ Route::has('password.request') ? route('password.request') : url('/forgot-password') }}" class="text-[#C8A165] font-medium text-[14px]">Forgot
                                            Password?</a>
                                    </div>
                                    <div class="mt-2 space-y-2 w-full ">
                                        <div
                                            class="flex items-center rounded-md bg-transparent pl-3 border-2 border-white/60">
                                            <input type="password" name="password" id="password"
                                                class="block min-w-0 grow mb-1 py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 placeholder:font-medium focus:outline-none sm:text-sm/6 bg-transparent text-white"
                                                placeholder="Enter your password..." autocomplete="current-password" @error('password') aria-invalid="true" @enderror required>
                                        </div>
                                        
                                    </div>
                                </div>


                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="w-full" data-aos="fade-up" data-aos-duration="1000">
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
                        <div class="flex flex-col justify-center items-center gap-y-6 w-full">
                            <button type="submit"
                                class="rounded-md bg-[#199BCF] px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full shadow-xl" data-aos="fade-up" data-aos-duration="1000">Login</button>
                            
                            <!-- Forgot Password Link -->
                            <div class="w-full text-center" data-aos="fade-up" data-aos-duration="1100">
                                <a href="{{ route('password.request') }}" class="text-[#C8A165] hover:text-yellow-400 text-sm font-medium">
                                    Forgot your password?
                                </a>
                            </div>
                            
                            <span class="flex items-center w-full" data-aos="fade-up" data-aos-duration="1200">
                                <span class="h-px flex-1 bg-gray-400/40"></span>

                                <span class="shrink-0 px-4 text-gray-200/60">or login with</span>

                                <span class="h-px flex-1 bg-gray-400/40"></span>
                            </span>

                            <button type="button"
                                class="rounded-md bg-gray-200 px-3 py-2 text-sm font-bold shadow-xs hover:bg-gray-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full shadow-xl flex flex-row justify-center items-center gap-2 text-gray-700 " data-aos="fade-up" data-aos-duration="1400"><svg
                                    xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25"
                                    viewBox="0 0 48 48">
                                    <path fill="#FFC107"
                                        d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z">
                                    </path>
                                    <path fill="#FF3D00"
                                        d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z">
                                    </path>
                                    <path fill="#4CAF50"
                                        d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z">
                                    </path>
                                    <path fill="#1976D2"
                                        d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z">
                                    </path>
                                </svg>Google</button>
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
