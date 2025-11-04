<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Send New Notification') }}
            </h2>
            <a href="{{ route('admin.notifications.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                View History
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Device Count Info -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-800 text-sm font-medium">
                                {{ $deviceCount }} registered devices available for notifications
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.notifications.store') }}" id="notificationForm">
                        @csrf

                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Notification Title
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   value="{{ old('title') }}"
                                   required 
                                   maxlength="255"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter notification title">
                        </div>

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Notification Message
                            </label>
                            <textarea name="message" 
                                      id="message" 
                                      rows="4" 
                                      required 
                                      maxlength="1000"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter your notification message">{{ old('message') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                        </div>

                        <!-- Target Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Send To
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="target_type" 
                                           value="all" 
                                           {{ old('target_type', 'all') === 'all' ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-900">
                                        All Users ({{ $deviceCount }} devices)
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="target_type" 
                                           value="specific" 
                                           {{ old('target_type') === 'specific' ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-900">
                                        Specific Devices
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Specific Tokens Selection (Hidden by default) -->
                        <div id="specific-tokens" class="mb-6 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Select Devices
                            </label>
                            <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2">
                                @foreach($devices as $device)
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="target_tokens[]" 
                                               value="{{ $device->token }}"
                                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-900">
                                            {{ $device->user ? $device->user->name . ' (' . $device->user->email . ')' : 'Unknown User' }}
                                            - {{ ucfirst($device->platform) ?? 'Unknown Platform' }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                                Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const targetTypeRadios = document.querySelectorAll('input[name="target_type"]');
            const specificTokensDiv = document.getElementById('specific-tokens');
            
            targetTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'specific') {
                        specificTokensDiv.classList.remove('hidden');
                    } else {
                        specificTokensDiv.classList.add('hidden');
                        // Uncheck all specific tokens
                        const tokenCheckboxes = document.querySelectorAll('input[name="target_tokens[]"]');
                        tokenCheckboxes.forEach(checkbox => checkbox.checked = false);
                    }
                });
            });
            
            // Initialize on page load
            const checkedRadio = document.querySelector('input[name="target_type"]:checked');
            if (checkedRadio && checkedRadio.value === 'specific') {
                specificTokensDiv.classList.remove('hidden');
            }
        });
    </script>
</x-app-layout>