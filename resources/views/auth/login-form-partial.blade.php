<div class="mb-5">
    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-700">
        ระบบห้องสมุด
    </span>
    <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">เข้าสู่ระบบ</h1>
    <p class="mt-1 text-xs text-slate-500">กรอกข้อมูลบัญชีเพื่อเข้าสู่ระบบจัดคลังหนังสือของคุณ</p>
</div>

<!-- Session Status -->
<x-auth-session-status class="mb-3" :status="session('status')" />

<form method="POST" action="{{ route('login') }}" class="space-y-3.5">
    @csrf

    <!-- Email Address -->
    <div>
        <x-input-label for="email_login" value="อีเมล" class="text-xs font-semibold text-slate-700" />
        <x-text-input id="email_login" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" placeholder="name@example.com" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <!-- Password -->
    <div>
        <x-input-label for="password_login" value="รหัสผ่าน" class="text-xs font-semibold text-slate-700" />
        <x-text-input id="password_login" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between gap-3 pt-0.5">
        <label for="remember_me" class="inline-flex items-center cursor-pointer">
            <input id="remember_me" type="checkbox" class="h-3.5 w-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" name="remember">
            <span class="ms-1.5 text-xs text-slate-600 font-medium">จดจำการเข้าสู่ระบบ</span>
        </label>
        @if (Route::has('password.request'))
            <a class="text-xs font-semibold text-emerald-600 hover:text-emerald-700" href="{{ route('password.request') }}">
                ลืมรหัสผ่าน?
            </a>
        @endif
    </div>

    <!-- Actions -->
    <div class="pt-1 space-y-2.5">
        <button type="submit" class="w-full rounded-xl bg-emerald-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            เข้าสู่ระบบ
        </button>

        <p class="text-center text-xs text-slate-500 lg:hidden">
            ยังไม่มีบัญชี? 
            <button type="button" @click="activeTab = 'register'" class="font-semibold text-emerald-600 hover:text-emerald-700">
                สมัครสมาชิก
            </button>
        </p>
    </div>
</form>
