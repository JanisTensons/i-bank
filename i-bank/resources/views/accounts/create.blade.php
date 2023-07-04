<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/accounts">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('create') }}">
            @csrf

            <!-- Account type -->
            <div>
                <x-label for="type" :value="__('Type')"/>

                <select id="type" class="block mt-1 w-full" name="type" required autofocus>
                    <option value="Checking Account">Checking Account</option>
                    <option value="Investing Account">Investing Account</option>
                </select>
            </div>

            <!-- Currency -->
            <div>
                <x-label for="currency" :value="__('Currency')"/>

                <select id="currency" class="block mt-1 w-full" name="currency" required autofocus>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                    <option value="NOK">NOK</option>
                    <option value="AUD">AUD</option>
                    <option value="IDR">IDR</option>
                </select>
            </div>

            <!-- Balance -->
            <div>
                <x-label for="balance" :value="__('Balance')"/>

                <x-input id="balance" class="block mt-1 w-full" type="text" name="balance" :value="old('balance')"
                         required autofocus/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Create account') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
