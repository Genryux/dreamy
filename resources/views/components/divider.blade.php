@props(['color' => '#1e1e1e', 'opacity' => ''])
<span {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <span class="h-[0.9px] flex-1 " style="background-color: {{ $color }}; opacity: {{ $opacity }}"></span>
</span>