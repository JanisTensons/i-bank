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
                            <option value="{{ $account->id }}">{{ $account->type }} {{ $account->number }}
                                | {{ $account->currency }} | {{ $account->balance }}</option>
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
                <x-label for="profit" :value="__('Profit €')"/>
                <x-input id="profit" class="block mt-1 w-full" type="number" name="profit" :value="request('profit')"
                         required autofocus readonly/>
            </div>

            <!-- Total Profit -->
            <div>
                <x-label for="totalProfit" :value="__('Total Profit')"/>

                @php
                    $totalProfit = 0;
                @endphp

                @foreach($accounts as $account)
                    @php
                        $convertedProfit = convertToAccountCurrency(request('profit'), $account->currency);
                        $totalProfit += $convertedProfit;
                    @endphp
                    <x-input id="converted-profit-{{ $account->id }}" class="block mt-1 w-full" type="hidden"
                             name="converted-profit"
                             :value="$convertedProfit" required readonly hidden/>
                @endforeach

                <x-input id="totalProfit" class="block mt-1 w-full" type="text" name="totalProfit"
                         :value="$totalProfit"
                         required autofocus readonly/>
            </div>

            <script>
                // Function to update the total profit
                function updateTotalProfit() {
                    let totalProfit = 0;

                    @foreach($accounts as $account)
                    let convertedProfit = parseFloat(document.getElementById('converted-profit-{{ $account->id }}').value);
                    totalProfit += convertedProfit;
                    @endforeach

                    let totalProfitElement = document.getElementById('total-profit');
                    totalProfitElement.value = isNaN(totalProfit) ? '' : totalProfit.toFixed(2);
                }

                // Call the updateTotalProfit function initially to calculate and display the initial total profit
                updateTotalProfit();
            </script>


            <!-- Verification Code -->
            <div class="mt-4">
                <x-label for="verification_code" :value="__('Verification Code')"/>

                <x-input id="verification_code" class="block mt-1 w-full" type="text" name="verification_code" required
                         autofocus/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-4">
                    {{ __('Sell') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
