@props(['id', 'maxWidth', 'title' => null])

@php
$id = $id ?? 'modal-' . uniqid();

$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth ?? '2xl'];
@endphp

<div
    x-data="{ show: false }"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    id="{{ $id }}"
    class="fixed inset-0 z-50 px-4 py-6 overflow-y-auto jetstream-modal sm:px-0"
    style="display: none;"
>
    <!-- Backdrop -->
    <div 
        x-show="show" 
        class="fixed inset-0 transition-all transform" 
        x-on:click="show = false" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    </div>

    <!-- Modal Content -->
    <div 
        x-show="show" 
        class="relative mb-6 bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto" 
        x-trap.inert.noscroll="show" 
        x-on:click.away="show = false" 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <!-- Header -->
        @if($title)
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
        </div>
        @endif

        <!-- Content -->
        <div class="p-6">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if(isset($footer))
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $footer }}
        </div>
        @endif
    </div>
</div> 