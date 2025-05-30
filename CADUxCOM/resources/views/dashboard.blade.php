<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            @if(auth()->user()->role === 'empresa')
                <div class="mt-6 bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Opciones para empresa</h3>

                    <a href="{{ route('productos.create') }}"
                       class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Crear producto
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
