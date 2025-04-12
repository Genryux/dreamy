<div id="modal" class="bg-[#f8f8f8] flex flex-col rounded-md w-[40%]" onclick="event.stopPropagation()">
    <span class="px-4 py-2 flex flex-row items-center justify-between">
        <p class="font-bold">{{ $modal}}</p>
        <i id="close-btn" class="fi fi-rs-cross-small text-[20px] flex items-center rounded-full cursor-pointer hover:ring hover:ring-[#1e1e1e]/15"></i>
    </span>

    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

    {{ $slot }}

    <x-divider color="#1e1e1e" opacity="0.15"></x-divider>

    <div class="flex justify-end px-4 py-3 space-x-1">
        {{ $modal_buttons }}
    </div>
</div>