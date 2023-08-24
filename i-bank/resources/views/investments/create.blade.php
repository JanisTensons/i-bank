<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/investments">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('investments/create') }}">
            @csrf

            <!-- Account type -->
            <div>
                <x-label for="type" :value="__('Account')"/>

                <select id="account_id" class="block mt-1 w-full" name="account_id" required autofocus>
                    @foreach($accounts as $account)
                        @if($account->type === 'Investing Account')
                            <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }}
                                | {{ $account->currency }} | {{ $account->balance }}</option>
                        @endif
                    @endforeach
                </select>

            </div>

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Name')"/>

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="request('name')" required
                         autofocus readonly/>
            </div>

            <!-- Amount -->
            <div>
                <x-label for="amount" :value="__('Amount')"/>

                <x-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="1"
                         min="1" required autofocus/>
            </div>

            <!-- Price -->
            <div>
                <x-label for="price" :value="__('Price €')"/>

                <x-input id="price" class="block mt-1 w-full" type="text" name="price" :value="request('price')"
                         required autofocus readonly/>
            </div>

            <!-- Total Price -->
            <div>
                <x-label for="total-price" :value="__('Total')"/>

                @foreach($accounts as $account)
                    @php
                        $convertedPrice = convertToAccountCurrency(request('price'), $account->currency);
                    @endphp
                    <x-input id="converted-price" class="block mt-1 w-full" type="hidden" name="converted-price"
                             :value="$convertedPrice" required readonly hidden/>
                @endforeach

                <div>

                    <x-input id="total-price" class="block mt-1 w-full" type="text" name="total-price"
                             :value="request('price')" required autofocus readonly/>
                </div>

                <script>
                    // Function to calculate and update the total price
                    function updateTotalPrice() {
                        let amount = parseFloat(document.getElementById('amount').value);
                        let convertedPrice = parseFloat(document.getElementById('converted-price').value);
                        let totalPriceElement = document.getElementById('total-price');

                        // Calculate the total price
                        let totalPrice = convertedPrice * amount;

                        // Update the value of the total price input field
                        totalPriceElement.value = isNaN(totalPrice) ? '' : totalPrice.toFixed(2);
                    }

                    // Add an event listener to the amount input field to trigger the updateTotalPrice function when the value changes
                    document.getElementById('amount').addEventListener('input', updateTotalPrice);

                    // Call the updateTotalPrice function initially to calculate and display the initial total price
                    updateTotalPrice();
                </script>

            </div>

            <!-- Verification Code -->
            <div class="mt-4">
                <x-label for="verification_code" :value="__('Verification Code')"/>

                <x-input id="verification_code" class="block mt-1 w-full" type="text" name="verification_code" required
                         autofocus/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Buy') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
