
@extends('layouts.app')

@section('content')
    <div class="mt-4 dark:bg-black dark:text-white/50">

        <h2>Login page</h2>

        <form action="/session" method="post">
            @csrf

            <div class="space-y-12">

                <div class="border-b border-gray-900/10 pb-12">
    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
                            <div class="mt-2 space-y-2">
                                <div
                                    class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                    <input type="text" name="email" id="email"
                                        class="block min-w-0 grow mb-1 py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                        placeholder="Enter your email..." value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <x-error>{{ $message }}</x-error>
                                @enderror
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                            <div class="mt-2 space-y-2">
                                <div
                                    class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                    <input type="password" name="password" id="password"
                                        class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-black placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                        placeholder="Enter your password..." value="{{ old('password') }}" required>
                                </div>
                                @error('password')
                                    <x-error>{{ $message }}</x-error>
                                @enderror
                            </div>
                        </div>
    
    
                    </div>
                </div>
    
            </div>
    
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <x-nav-link href="/" class="text-sm/6 font-semibold text-gray-900">Cancel</x-nav-link>
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Login</button>
            </div>

        </form>

    </div>    
@endsection

