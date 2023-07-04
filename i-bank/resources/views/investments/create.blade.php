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
                            <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }} | {{ $account->currency }} | {{ $account->balance }}</option>
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

                <!-- Check if account currency is not USD -->
                @if($account->currency !== 'EUR')
                    @php
                        $convertedPrice = convertToAccountCurrency(request('price'), $account->currency);
                    @endphp
                    <x-input id="total-price" class="block mt-1 w-full" type="text" name="total-price" :value="$convertedPrice * request('amount')" required autofocus readonly/>
                @else
                    <x-input id="total-price" class="block mt-1 w-full" type="text" name="total-price" :value="request('price')" required autofocus readonly/>
                @endif

                <script>
                    // Function to calculate and update the total price
                    function updateTotalPrice() {
                        var amount = parseFloat(document.getElementById('amount').value);
                        var price = parseFloat(document.getElementById('price').value);
                        var convertedPrice = parseFloat(document.getElementById('total-price').value);
                        var totalPriceElement = document.getElementById('total-price');

                        // Calculate the total price
                        // Update the value of the total price input field
                        totalPriceElement.value = convertedPrice * amount;
                    }

                    // Add event listeners to the input fields to trigger the updateTotalPrice function when the values change
                    document.getElementById('amount').addEventListener('input', updateTotalPrice);
                    document.getElementById('price').addEventListener('input', updateTotalPrice);

                    // Call the updateTotalPrice function initially to calculate and display the initial total price
                    updateTotalPrice();
                </script>


                <x-input id="total-price" class="block mt-1 w-full" type="text" name="total-price"
                         :value="request('price') * 1" required autofocus readonly/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Buy') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
