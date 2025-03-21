@extends('layouts.admission')


@section('header')
    <span class="flex flex-row space-x-4">
        <i class="fi fi-rs-chart-simple text-[20px]"></i>
        <p class="text-[18px] md:text-[20px] font-bold">Dashboard</p>
    </span>
@endsection


@section('content')
    <div class="flex flex-col h-full">
        <div class="text-center border-b border-[#1e1e1e]/10 p-4 md:p-6">
            <p class="text-[18px] md:text-[20px] font-bold">Welcome to Dreamy School admission portal!</p>
            <p class="text-sm md:text-base mt-2">Please click the button below to fill out the form.</p>
        </div>
        <div class="flex flex-col justify-center items-center flex-grow p-4 md:p-6">
            <x-nav-link href="/admission/application-form" class="bg-[#199BCF] text-white px-6 py-3 rounded-full hover:bg-[#1689b8] transition-colors duration-200">
                <p class="text-[16px] font-bold">Get Started</p>
            </x-nav-link>
        </div>
    </div>
@endsection