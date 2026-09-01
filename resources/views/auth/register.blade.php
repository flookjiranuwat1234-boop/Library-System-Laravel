<x-guest-layout>
    <div class="mb-7">
        <span class="text-sm font-semibold text-emerald-600">เริ่มต้นใช้งานฟรี</span>
        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">สร้างบัญชีสมาชิก</h1>
        <p class="mt-2 text-sm text-slate-500">สมัครเพื่อค้นหาและยืมหนังสือที่คุณสนใจ</p>
    </div>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="mt-2 block w-full py-3" type="text" name="name" :value="old('name')" placeholder="ชื่อและนามสกุล" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full py-3" type="email" name="email" :value="old('email')" placeholder="name@example.com" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="mt-2 block w-full py-3"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="mt-2 block w-full py-3"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <p class="text-xs leading-relaxed text-slate-400">เมื่อสมัครสมาชิก ถือว่าคุณยอมรับเงื่อนไขการใช้งานระบบห้องสมุด</p>
        <x-primary-button class="w-full py-3">{{ __('Register') }} →</x-primary-button>
        <p class="text-center text-sm text-slate-500">มีบัญชีอยู่แล้ว? <a class="font-semibold text-emerald-600 hover:text-emerald-800" href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
    </form>
</x-guest-layout>
