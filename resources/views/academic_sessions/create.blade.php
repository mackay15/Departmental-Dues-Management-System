<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Academic Session') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('academic-sessions.store') }}">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Session Name (e.g. 2024/2025)')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="semester" :value="__('Semester (e.g. 1, 2, or Summer)')" />
                            <x-text-input id="semester" class="block mt-1 w-full" type="text" name="semester" :value="old('semester')" required />
                            <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                        </div>
                        <div class="block mt-4 mb-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ __('Set as Active Session (Deactivates others)') }}</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('academic-sessions.index') }}" class="text-gray-600 mr-4">Cancel</a>
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
