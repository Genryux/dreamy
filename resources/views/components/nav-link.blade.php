@props(['active' => false])

<a {{$attributes}} class="{{ $active ? 'bg-[#199BCF] bg-opacity-[0.80] text-white/90 border-b-2 border-[#199BCF] flex flex-row justify-start items-center space-x-2 shadow-lg' 

: 'text-gray-300/80 hover:bg-[#199BCF] hover:bg-opacity-[0.30] hover:text-white transition-all duration-150 ease-in-out' }} rounded-lg px-3 py-2 text-sm font-medium cursor-pointer" aria-current="{{ request()->is('/') ? 'page' : 'false' }}">
    {{$slot}}
</a>