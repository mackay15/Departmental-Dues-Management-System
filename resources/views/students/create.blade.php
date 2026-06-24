<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('students.index') }}" class="text-gray-500 hover:text-gray-700 mr-2">
                &larr; Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Register New Student') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Index Number -->
                            <div>
                                <x-input-label for="index_number" :value="__('Index Number')" />
                                <x-text-input id="index_number" class="block mt-1 w-full" type="text" name="index_number" :value="old('index_number')" required autofocus />
                            </div>

                            <!-- First Name -->
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                            </div>

                            <!-- Other Names -->
                            <div>
                                <x-input-label for="other_names" :value="__('Other Names')" />
                                <x-text-input id="other_names" class="block mt-1 w-full" type="text" name="other_names" :value="old('other_names')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                            </div>

                            <!-- Photo Upload -->
                            <div>
                                <x-input-label for="photo" :value="__('Student Photo')" />
                                <input id="photo" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="file" name="photo" accept="image/*" />
                            </div>

                            <!-- Programme -->
                            <div>
                                <x-input-label for="programme_id" :value="__('Programme')" />
                                <select id="programme_id" name="programme_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Programme</option>
                                    @foreach($programmes as $programme)
                                        <option value="{{ $programme->id }}" {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                            {{ $programme->name }} ({{ $programme->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Current Level -->
                            <div>
                                <x-input-label for="current_level_id" :value="__('Current Level')" />
                                <select id="current_level_id" name="current_level_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Level</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('current_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Register Student') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
