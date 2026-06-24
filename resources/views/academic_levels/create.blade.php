<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Academic Level') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('academic-levels.store') }}">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="numeric_value" :value="__('Numeric Value (e.g. 100)')" />
                            <x-text-input id="numeric_value" class="block mt-1 w-full" type="number" name="numeric_value" :value="old('numeric_value')" required autofocus />
                            <x-input-error :messages="$errors->get('numeric_value')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Level Name (e.g. Level 100)')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('academic-levels.index') }}" class="text-gray-600 mr-4">Cancel</a>
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
