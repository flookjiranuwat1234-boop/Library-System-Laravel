<div class="mb-4">
    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-700">
        เริ่มต้นใช้งานฟรี
    </span>
    <h1 class="mt-1.5 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">สร้างบัญชีสมาชิก</h1>
    <p class="mt-1 text-xs text-slate-500">สมัครเพื่อค้นหาและยืมหนังสือที่คุณสนใจได้ทันที</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-3">
    @csrf

    <!-- Name -->
    <div>
        <x-input-label for="name_reg" value="ชื่อ-นามสกุล" class="text-xs font-semibold text-slate-700" />
        <x-text-input id="name_reg" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500" type="text" name="name" :value="old('name')" placeholder="สมชาย ใจดี" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-1" />
    </div>

    <!-- Email Address -->
    <div>
        <x-input-label for="email_reg" value="อีเมล" class="text-xs font-semibold text-slate-700" />
        <x-text-input id="email_reg" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-500" type="email" name="email" :value="old('email')" placeholder="name@example.com" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-1" />
    </div>

    <!-- Password -->
    <div>
        <x-input-label for="password_reg" value="รหัสผ่าน" class="text-xs font-semibold text-slate-700" />
        <x-text-input id="password_reg" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
    </div>

    <!-- Confirm Password -->
    <div>
        <x-input-label for="password_confirmation_reg" value="ยืนยันรหัสผ่าน" class="text-xs font-semibold text-slate-700" />
        <x-text-input id="password_confirmation_reg" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
    </div>

    <!-- Actions -->
    <div class="pt-1 space-y-2">
        <button type="submit" class="w-full rounded-xl bg-emerald-600 py-2.5 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
            สมัครสมาชิก
        </button>

        <p class="text-center text-xs text-slate-500 lg:hidden">
            มีบัญชีอยู่แล้ว? 
            <button type="button" @click="activeTab = 'login'" class="font-semibold text-emerald-600 hover:text-emerald-700">
                เข้าสู่ระบบ
            </button>
        </p>
    </div>
</form>
