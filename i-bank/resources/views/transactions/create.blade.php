<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/transactions">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <!-- From Account -->
            <div>
                <x-label for="from" :value="__('From')"/>

                <select id="from" class="block mt-1 w-full" name="from" required autofocus>
                    @foreach($accounts as $account)
                        @if($account->type === 'Checking Account')
                            <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }}
                                | {{ $account->currency }} | {{ $account->balance }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- To Account -->
            <div>
                <x-label for="to" :value="__('To')"/>

                <select id="to" class="block mt-1 w-full" name="to" required autofocus>
                    @foreach($accounts as $account)
                        @if($account->type === 'Checking Account')
                            <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }}
                                | {{ $account->currency }} | {{ $account->balance }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Amount -->
            <div>
                <x-label for="amount" :value="__('Amount')"/>

                <x-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')" required autofocus/>
            </div>

            <!-- Description -->
            <div>
                <x-label for="description" :value="__('Description')"/>

                <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" autofocus/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Transfer') }}
                </x-button>
            </div>
        </form>

    </x-auth-card>
</x-guest-layout>
