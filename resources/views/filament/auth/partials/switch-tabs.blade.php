<div class="grid grid-cols-2 overflow-hidden rounded-full border border-gray-300">
    <a
        href="{{ filament()->getLoginUrl() }}"
        @class([
            'px-6 py-3 text-center text-sm font-semibold transition',
            'bg-primary-600 text-white' => request()->routeIs(filament()->getCurrentPanel()->generateRouteName('auth.login')),
            'text-gray-900 hover:bg-gray-50' => ! request()->routeIs(filament()->getCurrentPanel()->generateRouteName('auth.login')),
        ])
    >
        Login
    </a>

    <a
        href="{{ filament()->getRegistrationUrl() }}"
        @class([
            'border-l border-gray-300 px-6 py-3 text-center text-sm font-semibold transition',
            'bg-primary-600 text-white' => request()->routeIs(filament()->getCurrentPanel()->generateRouteName('auth.register')),
            'text-gray-900 hover:bg-gray-50' => ! request()->routeIs(filament()->getCurrentPanel()->generateRouteName('auth.register')),
        ])
    >
        Sign Up
    </a>
</div>
