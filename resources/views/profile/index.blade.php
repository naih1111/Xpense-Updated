@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Profile Header -->
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-90"></div>
                <div class="relative px-8 py-10">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold text-white tracking-tight">{{ auth()->user()->name }}</h1>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="px-8 py-8">
                <!-- Profile Information -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <svg class="h-6 w-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                    </div>
                    
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ auth()->user()->name }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ auth()->user()->email }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ auth()->user()->created_at->format('F j, Y') }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ auth()->user()->updated_at->format('F j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Delete Account Section -->
                <div class="mt-10 space-y-6">
                    <div class="flex items-center space-x-3">
                        <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <h2 class="text-xl font-semibold text-red-600">Delete Account</h2>
                    </div>
                    
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-6">
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                            </p>
                            <div class="mt-6">
                                <form method="POST" action="{{ route('profile.destroy') }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                            onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                                        Delete Account
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 