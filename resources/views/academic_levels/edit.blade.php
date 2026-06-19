<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Academic Level') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('academic-levels.update', $academicLevel) }}">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <x-input-label for="level_code" :value="__('Level Code')" />
                            <x-text-input id="level_code" class="block mt-1 w-full" type="text" name="level_code" :value="old('level_code', $academicLevel->level_code)" required autofocus />
                            <x-input-error :messages="$errors->get('level_code')" class="mt-2" />
                        </div>
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Level Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $academicLevel->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('academic-levels.index') }}" class="text-gray-600 mr-4">Cancel</a>
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
