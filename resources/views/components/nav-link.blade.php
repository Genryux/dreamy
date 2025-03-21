@props(['active' => false])

<a {{$attributes}} class="{{ $active ? 'bg-[#199BCF] bg-opacity-[0.60] text-white/80 ring-1 ring-blue-400/80 flex flex-row space-x-2' : 'text-gray-300/60 hover:bg-[#199BCF] hover:bg-opacity-[0.30] hover:text-white transition-all duration-150 ease-in-out' }} rounded-md px-3 py-2 text-sm font-medium cursor-pointer" aria-current="{{ request()->is('/') ? 'page' : 'false' }}">
    {{$slot}}
</a>