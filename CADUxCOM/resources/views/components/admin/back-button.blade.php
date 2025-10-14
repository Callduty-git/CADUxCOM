@props(['href' => 'javascript:history.back()'])

<div class="mb-3">
    <a href="{{ $href }}" 
       class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
        </svg>
        Regresar
    </a>
</div>