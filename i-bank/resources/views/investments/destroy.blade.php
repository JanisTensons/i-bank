<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/portfolio">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('portfolio/destroy') }}">
            @csrf

            <!-- Account type -->
            <div>
                <x-label for="type" :value="__('Account')"/>

                <select id="account_id" class="block mt-1 w-full" name="account_id" required autofocus>
                    @foreach($accounts as $account)
                        @if($account->type === 'Investing Account')
                            <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }} | {{ $account->currency }} | {{ $account->balance }}</option>
                        @endif
                    @endforeach
                </select>

            </div>

            <!-- ID -->
            <div>
                <x-label for="id" :value="__('ID')"/>

                <x-input id="id" class="block mt-1 w-full" type="text" name="id" :value="request('id')" required
                         autofocus readonly/>
            </div>

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')"/>

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="request('name')" required
                         autofocus readonly/>
            </div>

            <!-- Profit -->
            <div>
                <x-label for="profit" :value="__('Profit')"/>

                <x-input id="profit" class="block mt-1 w-full" type="number" name="profit" :value="request('profit')"
                         min="1" required autofocus readonly/>
            </div>

            <!-- Amount
            <div>
                <x-label for="amount" :value="__('Amount')"/>

                <x-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="1"
                         min="1" required autofocus readonly/>
            </div>

             Price
            <div>
                <x-label for="price" :value="__('Price')"/>

                <x-input id="price" class="block mt-1 w-full" type="text" name="price" :value="request('price')"
                         required autofocus readonly/>
            </div>

             Total Price
            <div>
                <x-label for="total-price" :value="__('Total')"/>

                <x-input id="total-price" class="block mt-1 w-full" type="text" name="total-price"
                         :value="request('price') * 1" required autofocus readonly/>
            </div>
-->
            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Sell') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
