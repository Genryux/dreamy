@props(['modal_id', 'modal_className', 'modal_icon', 'modal_name', 'modal_buttons', 'close_btn_id', 'modal_info', 'modal_container_id'])

<div id="{{ $modal_container_id ?? 'modal-container'}}"
    class="fixed opacity-0 pointer-events-none bottom-0 left-0 bg-[#0f111c]/70 w-full z-20 transition ease-in duration-120 overflow-hidden">

    <div id="{{ $modal_id }}" class="{{ $modal_className ?? '' }} opacity-0 scale-95 pointer-events-none transition ease-in duration-150 flex items-center justify-center h-screen w-screen">
        <div class="bg-[#f8f8f8] flex flex-col rounded-md w-[40%] shadow-lg" onclick="event.stopPropagation()">
            <span class="px-6 py-4 flex flex-row items-center justify-between">
                <div class="flex flex-row justify-center items-center gap-2">
                    {{ $modal_icon ?? '' }}
                    <p class="font-bold">{{ $modal_name }}</p>
                </div>
                <i id="{{ $close_btn_id }}"
                    class="fi fi-rs-cross-small text-[20px] flex items-center rounded-full cursor-pointer hover:ring hover:ring-gray-400 transition duration-150"></i>
            </span>

            <x-divider color="#1e1e1e" opacity="0.10"></x-divider>

            {{ $slot }}


            @if (isset($modal_buttons))
                <x-divider color="#1e1e1e" opacity="0.10"></x-divider>

                <div class="flex flex-row justify-between items-center px-6 py-4 space-x-1">

                    <div class="text-[14px] opacity-70 flex flex-row justify-center items-center gap-2 hover:text-blue-600 hover:underline transition duration-150">
                        {{ $modal_info ?? '' }}
                    </div>
                    <div class="flex flex-row justify-center items-center gap-2">
                        {{ $modal_buttons }}

                    </div>
                </div>
            @endif


        </div>
    </div>
</div>
