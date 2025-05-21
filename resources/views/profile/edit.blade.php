@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-100 to-pink-100 py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Card -->
        <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="relative px-8 py-8 bg-gradient-to-r from-indigo-500 to-purple-600">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <h2 class="relative text-4xl font-bold text-white text-center">Edit Profile</h2>
            </div>

            <!-- Content -->
            <div class="p-6 sm:p-8 space-y-8">
                <!-- Profile Information -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl shadow-sm">
                            <span class="material-icons text-2xl text-indigo-600">person</span>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900">Profile Information</h3>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 sm:p-8 shadow-lg">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl shadow-sm">
                            <span class="material-icons text-2xl text-purple-600">lock</span>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900">Update Password</h3>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 sm:p-8 shadow-lg">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-br from-pink-100 to-red-100 rounded-2xl shadow-sm">
                            <span class="material-icons text-2xl text-red-600">delete</span>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-900">Delete Account</h3>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 sm:p-8 shadow-lg">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 