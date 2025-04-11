@props(['card_icon', 'card_title', 'text_color', 'color' => '#1e1e1e'])
<div {{ $attributes->merge(['class' => 'border rounded-md flex-1']) }}>
    <div class="flex flex-row items-center px-[16px] py-[6px] space-x-2">
        {{ $card_icon }}
        <p class="text-[16px] font-bold text-[#34A853]" style="color: {{ $text_color }}"> {{ $card_title }}</p>
    </div>
    <x-divider :color="$color"/>
    <div class="px-[16px] py-[16px]">
        <p id="pending-application" class="text-2xl text-center font-black text-[#0f111c]/80">{{ $slot}}</p>
    </div>
</div>
