@props(['modal_id', 'modal_name', 'modal_buttons', 'close_btn_id'])

<div id="{{ $modal_id }}" class="absolute bottom-0 left-0 bg-[#0f111c]/40 h-0 w-full z-20 ease-in-out duration-150 overflow-hidden">

    <div class="flex items-center justify-center h-screen w-screen">
        <div class="bg-[#f8f8f8] flex flex-col rounded-md w-[40%]" onclick="event.stopPropagation()">
            <span class="px-4 py-2 flex flex-row items-center justify-between">
                <p class="font-bold">{{ $modal_name}}</p>
                <i id="{{ $close_btn_id }}" class="fi fi-rs-cross-small text-[20px] flex items-center rounded-full cursor-pointer hover:ring hover:ring-[#1e1e1e]/15"></i>
            </span>

            <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

            {{ $slot }}

            <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

            <div class="flex justify-end px-4 py-3 space-x-1">
                {{ $modal_buttons }}
            </div>
        </div>
    </div>
</div>