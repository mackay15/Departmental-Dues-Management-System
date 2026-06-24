<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('students.show', $student) }}" class="text-gray-500 hover:text-gray-700 mr-2">
                &larr; Back
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Student Profile') }}
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

                    <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Index Number (Read Only) -->
                            <div>
                                <x-input-label for="index_number" :value="__('Index Number')" />
                                <x-text-input id="index_number" class="block mt-1 w-full bg-gray-100" type="text" name="index_number" :value="$student->index_number" readonly />
                            </div>

                            <!-- First Name -->
                            <div>
                                <x-input-label for="first_name" :value="__('First Name')" />
                                <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $student->first_name)" required />
                            </div>

                            <!-- Last Name -->
                            <div>
                                <x-input-label for="last_name" :value="__('Last Name')" />
                                <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $student->last_name)" required />
                            </div>

                            <!-- Other Names -->
                            <div>
                                <x-input-label for="other_names" :value="__('Other Names')" />
                                <x-text-input id="other_names" class="block mt-1 w-full" type="text" name="other_names" :value="old('other_names', $student->other_names)" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $student->email)" required />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $student->phone)" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="deferred" {{ old('status', $student->status) == 'deferred' ? 'selected' : '' }}>Deferred</option>
                                    <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                </select>
                            </div>

                            <!-- Programme -->
                            <div>
                                <x-input-label for="programme_id" :value="__('Programme')" />
                                <select id="programme_id" name="programme_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($programmes as $programme)
                                        <option value="{{ $programme->id }}" {{ old('programme_id', $student->programme_id) == $programme->id ? 'selected' : '' }}>
                                            {{ $programme->name }} ({{ $programme->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Current Level -->
                            <div>
                                <x-input-label for="current_level_id" :value="__('Current Level')" />
                                <select id="current_level_id" name="current_level_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('current_level_id', $student->current_level_id) == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Photo Upload -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="photo" :value="__('Update Photo (Optional)')" />
                                <input id="photo" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="file" name="photo" accept="image/*" />
                                @if($student->photo_path)
                                    <p class="text-sm text-gray-500 mt-2">Current photo exists. Uploading a new one will replace it.</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-4">
                                {{ __('Update Profile') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
