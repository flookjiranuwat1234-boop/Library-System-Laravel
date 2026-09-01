<x-guest-layout>
    <div class="mb-8">
        <span class="text-sm font-semibold text-emerald-600">ยินดีต้อนรับกลับ</span>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">เข้าสู่ระบบ</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-500">กรอกข้อมูลบัญชีเพื่อเข้าสู่คลังหนังสือของคุณ</p>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full py-3" type="email" name="email" :value="old('email')" placeholder="name@example.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-2 block w-full py-3"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-emerald-600 hover:text-emerald-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

        </div>
        <x-primary-button class="w-full py-3">{{ __('Log in') }} →</x-primary-button>
        <div class="relative py-1"><div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div><div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400">ยังไม่มีบัญชี?</span></div></div>
        <a href="{{ route('register') }}" class="button-secondary w-full">สร้างบัญชีสมาชิกใหม่</a>
    </form>
</x-guest-layout>
