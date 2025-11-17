@extends('layouts.app')

@section('content')
{{-- Debug info: Current locale is {{ app()->getLocale() }} --}}
<div class="min-h-screen" style="background: linear-gradient(135deg, #1593E6 0%, #0F7CC8 100%);">
    <!-- Navigation -->
    <nav class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <h1 class="text-2xl font-extrabold text-white tracking-tight">{{ __('app.app_name') }}</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="showLogin()" class="text-white hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        {{ __('app.login') }}
                    </button>
                    <button onclick="showRegister()" class="bg-white text-[#1593E6] hover:bg-gray-100 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ __('app.sign_up') }}
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="flex items-center justify-center min-h-[calc(100vh-4rem)] px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-white space-y-8">
                <div class="space-y-4">
                    <h2 class="text-5xl font-black leading-tight tracking-tight">
                        {{ __('app.find_perfect_kost') }}
                    </h2>
                    <p class="text-xl text-white/90 font-medium leading-relaxed">
                        {{ __('app.comfortable_affordable') }} 
                        {{ __('app.ideal_living_space') }}
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-white/90 font-medium">{{ __('app.verified_properties') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-white/90 font-medium">{{ __('app.easy_booking') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span class="text-white/90 font-medium">{{ __('app.support_24_7') }}</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('kost.index') }}" class="bg-white text-[#1593E6] hover:bg-[#DDDDDD] px-8 py-4 rounded-lg text-lg font-bold transition-all transform hover:scale-105 shadow-lg text-center">
                        Browse Kost
                    </a>
                    <button onclick="showRegister()" class="bg-white/20 text-white hover:bg-white/30 border-2 border-white px-8 py-4 rounded-lg text-lg font-bold transition-all transform hover:scale-105">
                        {{ __('app.get_started_today') }}
                    </button>
                </div>
            </div>

            <!-- Right Content - Auth Forms -->
            <div class="lg:flex justify-center">
                <!-- Login Form -->
                <div id="loginForm" class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">{{ __('app.welcome_back') }}</h3>
                        <p class="text-gray-600 font-medium">{{ __('app.sign_in_account') }}</p>
                    </div>

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                    </svg>
                                </div>
                                <div>{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5h2v6H9V5zm0 8h2v2H9v-2z"/>
                                    </svg>
                                </div>
                                <div>{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.email_address') }}</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="Enter your email">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('app.password') }}</label>
                            <input type="password" id="password" name="password" required 
                                class="w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="{{ __('app.enter_password') }}">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#1593E6] shadow-sm focus:ring-[#1593E6]">
                                <span class="ml-2 text-sm text-gray-600">{{ __('app.remember_me') }}</span>
                            </label>
                            <a href="#" class="text-sm text-[#1593E6] hover:underline">{{ __('app.forgot_password') }}</a>
                        </div>

                        <button type="submit" 
                            class="w-full bg-[#1593E6] hover:bg-[#0F7CC8] text-white py-3 px-4 rounded-lg font-medium transition-colors">
                            {{ __('app.sign_in') }}
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <span class="text-gray-600">{{ __('app.dont_have_account') }} </span>
                        <button onclick="showRegister()" class="text-[#1593E6] hover:underline font-medium">{{ __('app.sign_up') }}</button>
                    </div>
                </div>

                <!-- Register Form -->
                <div id="registerForm" class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md hidden">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h3>
                        <p class="text-gray-600">Join SigmaKost today</p>
                    </div>

                    <!-- Alert Messages for Register -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                    </svg>
                                </div>
                                <div>{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5h2v6H9V5zm0 8h2v2H9v-2z"/>
                                    </svg>
                                </div>
                                <div>{{ session('error') }}</div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="reg_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="reg_name" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="Enter your full name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="reg_email" name="email" value="{{ old('email') }}" required 
                                class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="Enter your email">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="reg_phone" name="phone" value="{{ old('phone') }}" 
                                class="w-full px-4 py-3 border @error('phone') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="Enter your phone number">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" id="reg_password" name="password" required 
                                class="w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="Create a password">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reg_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" id="reg_password_confirmation" name="password_confirmation" required 
                                class="w-full px-4 py-3 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-[#1593E6] focus:border-transparent transition-colors"
                                placeholder="Confirm your password">
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                            class="w-full bg-[#1593E6] hover:bg-[#0F7CC8] text-white py-3 px-4 rounded-lg font-medium transition-colors">
                            Create Account
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <span class="text-gray-600">Already have an account? </span>
                        <button onclick="showLogin()" class="text-[#1593E6] hover:underline font-medium">Sign in</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showLogin() {
    document.getElementById('loginForm').classList.remove('hidden');
    document.getElementById('registerForm').classList.add('hidden');
}

function showRegister() {
    document.getElementById('registerForm').classList.remove('hidden');
    document.getElementById('loginForm').classList.add('hidden');
}

// Auto-show correct form based on validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if ($errors->any())
        @if (session('_old_input.name') || session('_old_input.phone') || $errors->has('name') || $errors->has('phone') || $errors->has('password_confirmation'))
            // Show register form if register-specific fields have errors
            showRegister();
        @else
            // Show login form for general errors
            showLogin();
        @endif
    @endif
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        });
    }, 5000);
});
</script>
@endsection
