<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Currency Rates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-xs">The exchange rates are euro reference rates published by the ECB daily. New rates
                        are
                        typically published between 15:15GMT+2 and 16:00GMT+2.</p>
                </div>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Currency
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Rate
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($currencyRatesCollection as $listing)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $listing['currency'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $listing['rate'] }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
