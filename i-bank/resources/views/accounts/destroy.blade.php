<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/accounts">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('destroy') }}">
            @csrf

            <!-- Account type -->
            <div>
                <x-label for="type" :value="__('Account')"/>

                <select id="account_id" class="block mt-1 w-full" name="account_id" required autofocus>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }}
                            | {{ $account->currency }} | {{ $account->balance }}</option>
                    @endforeach
                </select>

            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Delete') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
