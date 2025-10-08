@props(['type' => 'success'])

@php
    $isSuccess = $type === 'success';
    $bgClass = $isSuccess ? 'bg-green-100 ring-green-300' : 'bg-red-100 ring-red-300';
    $iconClass = $isSuccess ? 'fi fi-sr-check-circle text-green-500' : 'fi fi-sr-cross-circle text-red-500';
    $title = $isSuccess ? 'Success' : 'Failed';
@endphp

<div id="alert-container"
     class="opacity-0 {{ $bgClass }} ring-2 fixed top-0 left-1/2 -translate-x-1/2 flex flex-row justify-center items-center gap-4 p-4 rounded-lg shadow-lg text-center scale-95 translate-y-5 z-50 text-gray-700 transition ease-in-out duration-200 text-start">

    <i class="{{ $iconClass }} flex justify-center items-center text-[24px] self-start"></i>

    <div class="flex flex-col justify-start items-start gap-1">
        <p class="font-bold leading-none" id="alertTitle">{{ $title }}</p>
        <p class="opacity-70 font-medium text-[14px] self-start" id="alertMessage"></p>
    </div>

    <button id="alert-close-btn">
        <i class="fi fi-ss-cross-small text-[20px] flex justify-center items-center"></i>
    </button>
</div>
