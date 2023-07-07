<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Verification Code') }}
        </div>

        <form method="POST" action="{{ route('verify-code') }}">
            @csrf
            <div class="mt-4">
                <x-label for="verification_code" :value="__('Verification Code')" />

                <x-input id="verification_code" class="block mt-1 w-full" type="text" name="verification_code" :value="old('verification_code')" required autofocus />
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Verify Code') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
