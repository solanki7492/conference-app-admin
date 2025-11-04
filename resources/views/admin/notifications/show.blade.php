<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notification Details #') }}{{ $notification->id }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.notifications.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to History
                </a>
                <a href="{{ route('admin.notifications.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Send New Notification
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Notification Content -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Content</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="mb-3">
                                <span class="text-sm font-medium text-gray-700">Title:</span>
                                <p class="text-gray-900 mt-1">{{ $notification->title }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Message:</span>
                                <p class="text-gray-900 mt-1">{{ $notification->message }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Statistics -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Delivery Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-full">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-blue-600">Total Sent</p>
                                        <p class="text-2xl font-semibold text-blue-900">{{ $notification->sent_count }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-full">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-green-600">Successful</p>
                                        <p class="text-2xl font-semibold text-green-900">{{ $notification->success_count }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-red-50 p-4 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 bg-red-100 rounded-full">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-red-600">Failed</p>
                                        <p class="text-2xl font-semibold text-red-900">{{ $notification->failure_count }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Rate Progress Bar -->
                        <div class="mt-6">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">Success Rate</span>
                                <span class="text-sm font-medium text-gray-900">{{ $notification->success_rate }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $notification->success_rate }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notification Details</h3>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700">Target Audience:</span>
                                <span class="text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $notification->sent_to === 'all' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($notification->sent_to) }}
                                    </span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700">Sent By:</span>
                                <span class="text-sm text-gray-900">{{ $notification->sentBy ? $notification->sentBy->name : 'System' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700">Sent At:</span>
                                <span class="text-sm text-gray-900">{{ $notification->created_at->format('M d, Y \a\t H:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700">Time Ago:</span>
                                <span class="text-sm text-gray-900">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Specific Tokens (if applicable) -->
                    @if($notification->sent_to === 'specific' && $notification->target_tokens)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Target Devices</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 mb-3">
                                    This notification was sent to {{ count($notification->target_tokens) }} specific device(s).
                                </p>
                                <div class="max-h-48 overflow-y-auto">
                                    @foreach($notification->target_tokens as $index => $token)
                                        <div class="text-xs font-mono text-gray-600 py-1">
                                            {{ $index + 1 }}. {{ Str::limit($token, 60) }}...
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.notifications.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Send Similar Notification
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>