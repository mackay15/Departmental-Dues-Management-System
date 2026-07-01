<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('students.index') }}" class="text-azure-600 hover:text-azure-800 transition mr-3">
                    <svg class="w-6 h-6 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-azure-800 leading-tight">
                    {{ __('Import Student Records') }}
                </h2>
            </div>
            <a href="{{ route('students.import.template') }}" 
               class="inline-flex items-center px-4 py-2 bg-azure-800 hover:bg-azure-900 text-white text-xs font-semibold uppercase tracking-widest rounded-lg shadow transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                {{ __('Download CSV Template') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-azure-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success/Error Feedback -->
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-400 text-emerald-800 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-rose-50 border border-rose-400 text-rose-800 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('imported_students_credentials'))
                <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-8 space-y-4">
                    <div class="flex items-center justify-between border-b border-azure-50 pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-azure-950">Imported Student Credentials</h3>
                            <p class="text-xs text-azure-500">Provide these temporary login credentials to the students. They will be forced to set a new password on their first login.</p>
                        </div>
                        <button onclick="downloadCredentialsCSV()" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold uppercase tracking-widest rounded-lg shadow transition duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Credentials CSV
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-azure-100 text-sm text-left">
                            <thead class="bg-azure-50/50 text-azure-800 font-semibold">
                                <tr>
                                    <th class="px-6 py-3">Index Number</th>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Email Address</th>
                                    <th class="px-6 py-3">Temporary Password</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-azure-50 text-azure-900">
                                @foreach (session('imported_students_credentials') as $cred)
                                    <tr class="hover:bg-azure-50/20">
                                        <td class="px-6 py-4 font-mono font-bold">{{ $cred['index_number'] }}</td>
                                        <td class="px-6 py-4">{{ $cred['name'] }}</td>
                                        <td class="px-6 py-4">{{ $cred['email'] }}</td>
                                        <td class="px-6 py-4"><code class="px-2 py-1 bg-amber-50 text-amber-800 rounded border border-amber-200 font-bold select-all font-mono">{{ $cred['temp_password'] }}</code></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    function downloadCredentialsCSV() {
                        const data = @json(session('imported_students_credentials'));
                        let csvContent = "data:text/csv;charset=utf-8,";
                        csvContent += "Index Number,Name,Email Address,Temporary Password\n";
                        
                        data.forEach(function(row) {
                            csvContent += `"${row.index_number}","${row.name}","${row.email}","${row.temp_password}"\n`;
                        });
                        
                        const encodedUri = encodeURI(csvContent);
                        const link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", "imported_students_credentials.csv");
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                </script>
            @endif

            <!-- Validation Errors Grouped by Row -->
            @if (session('import_errors'))
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-6 rounded-xl shadow-sm space-y-2">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="font-bold text-lg text-rose-900">Import Failed (Validation Errors)</h3>
                    </div>
                    <p class="text-sm text-rose-700">No student records were imported. Please fix the errors below in your file and upload it again.</p>
                    <div class="mt-4 max-h-60 overflow-y-auto border border-rose-100 rounded-lg bg-white p-3 space-y-1.5 shadow-inner">
                        @foreach (session('import_errors') as $error)
                            <div class="text-xs text-rose-800 flex items-start">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-rose-500 mt-1.5 mr-2 shrink-0"></span>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main Upload Form and Guidelines Card -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100">
                <div class="p-8">
                    
                    <form method="POST" action="{{ route('students.import.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Upload Box -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-azure-950">{{ __('Choose File') }}</label>
                            <div id="dropzone" class="border-2 border-dashed border-azure-200 hover:border-azure-400 rounded-2xl p-8 text-center bg-azure-50/20 hover:bg-azure-50/40 transition duration-200 cursor-pointer relative">
                                <input type="file" name="file" id="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".csv,.xls,.xlsx" required>
                                <div class="space-y-2 pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-azure-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div class="text-sm font-medium text-azure-900">
                                        <span class="text-azure-600 hover:text-azure-800">Click to upload</span> or drag and drop
                                    </div>
                                    <p class="text-xs text-azure-500">Supports CSV, XLS, XLSX (Max 5MB)</p>
                                    <p id="file-name" class="text-sm font-bold text-azure-950 mt-2 hidden"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm rounded-xl shadow transition duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                {{ __('Import Students') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Guidelines Card -->
            <div class="bg-white overflow-hidden shadow-md sm:rounded-2xl border border-azure-100 p-8 space-y-6">
                <h3 class="text-lg font-bold text-azure-950 border-b border-azure-50 pb-3 flex items-center">
                    <svg class="w-5 h-5 text-azure-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Import Guidelines & Specifications
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-azure-900 leading-relaxed">
                    <div class="space-y-3">
                        <h4 class="font-bold text-azure-950">Column Requirements:</h4>
                        <ul class="list-disc pl-5 space-y-1.5">
                            <li><strong>index_number</strong>: Student index number. Must be unique.</li>
                            <li><strong>first_name & last_name</strong>: Required text columns.</li>
                            <li><strong>email</strong>: Valid email address. Must be unique.</li>
                            <li><strong>programme_code</strong>: Code of an existing program (e.g. <code>CS</code>).</li>
                            <li><strong>level_numeric</strong>: Numeric representation of the student level (e.g., <code>100</code>, <code>200</code>, <code>300</code>, <code>400</code>).</li>
                        </ul>
                    </div>
                    
                    <div class="space-y-3">
                        <h4 class="font-bold text-azure-950">Key System Rules:</h4>
                        <ul class="list-disc pl-5 space-y-1.5">
                            <li><strong>User Accounts</strong>: The system automatically generates a user login account for each student. A secure random <strong>One-Time Password (OTP)</strong> is generated, and they will be prompted to reset it on their first login.</li>
                            <li><strong>All-Or-Nothing Transaction</strong>: If a single row contains a validation error (such as a duplicate email or invalid programme code), the entire import is rolled back to prevent incomplete records.</li>
                            <li><strong>Dues & Invoicing</strong>: Once imported, students will be ready for invoice generation via the Invoices module.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- UI Enhancements for File Input -->
    <script>
        const fileInput = document.getElementById('file');
        const dropzone = document.getElementById('dropzone');
        const fileNameEl = document.getElementById('file-name');

        fileInput.addEventListener('change', (e) => {
            if (fileInput.files.length > 0) {
                fileNameEl.textContent = "Selected: " + fileInput.files[0].name;
                fileNameEl.classList.remove('hidden');
            } else {
                fileNameEl.classList.add('hidden');
            }
        });

        // Add visual feedback on drag-and-drop
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('bg-azure-100/60', 'border-azure-400');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                dropzone.classList.remove('bg-azure-100/60', 'border-azure-400');
            }, false);
        });
    </script>
</x-app-layout>
