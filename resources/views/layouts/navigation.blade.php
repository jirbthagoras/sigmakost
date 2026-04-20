<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-2xl font-extrabold text-[#1593E6] tracking-tight">
                        {{ __('app.app_name') }}
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'border-[#1593E6] text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.rentals.index') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.rentals.index') ? 'border-[#1593E6] text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    {{ __('app.booking_request') }}
                                </a>
                                <a href="{{ route('admin.kosts.index') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.kosts.index') ? 'border-[#1593E6] text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Kelola Kost
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-[#1593E6] text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    {{ __('app.dashboard') }}
                                </a>
                                <a href="{{ route('rentals.index') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('rentals.index') ? 'border-[#1593E6] text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Lihat Pemesanan
                                </a>
                                <a href="{{ route('payments.index') }}"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('payments.index') ? 'border-[#1593E6] text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Pembayaran
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <span
                        class="text-gray-700 font-medium hidden sm:block">{{ __('app.welcome_user', ['name' => Auth::user()->name]) }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-[#DDDDDD] hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                            {{ __('app.logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-600 hover:text-[#1593E6] px-3 py-2 rounded-md text-sm font-medium">
                        {{ __('app.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-[#1593E6] text-white hover:bg-[#0F7CC8] px-4 py-2 rounded-lg text-sm font-medium">
                        {{ __('app.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>