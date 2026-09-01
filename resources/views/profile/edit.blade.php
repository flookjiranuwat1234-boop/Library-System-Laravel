<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="page-shell">
        <div class="page-container space-y-6">
            <div class="panel p-5 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="panel p-5 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-rose-200 bg-white p-5 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
