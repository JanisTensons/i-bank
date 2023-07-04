<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Portfolio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        onclick="location.href='create'" type="submit">Add new account
                    </button>

                    @if (session('success'))
                        <div class="flex items-center justify-center">
                            <div id="toast-success"
                                 class="flex items-center max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                                 role="alert">
                                <div
                                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Check icon</span>
                                </div>
                                <div class="ml-3 text-sm font-normal">{{ session('success') }}</div>
                                <button type="button"
                                        class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                                        data-dismiss-target="#toast-success" aria-label="Close">
                                    <span class="sr-only">Close</span>
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <script>
                            // Wait for the document to be ready
                            document.addEventListener('DOMContentLoaded', function () {
                                // Get the notification element
                                var toast = document.getElementById('toast-success');

                                // Hide the notification after 3 seconds
                                setTimeout(function () {
                                    toast.style.display = 'none';
                                }, 3000);
                            });
                        </script>
                    @endif

                </div>
            </div>

            <div class="relative overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Profit
                        </th>
                        <th>
                        </th>
                        <th>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($investments as $investment)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $investment['name'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ '€' . number_format($investment['price'], 2) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $investment['amount'] }}
                            </td>
                            <td class="px-6 py-4 {{ ($investment['price'] - $investment['user_price']) < 0 ? 'text-red-500' : 'text-green-500' }}">
                                €{{ number_format($investment['price'] - $investment['user_price'], 2) }}
                            </td>
                            <td>
                                <a class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                   href="{{ route('portfolio/destroy') }}?id={{ $investment['id'] }}&name={{ urlencode($investment['name']) }}&profit={{ number_format($investment['price'] - $investment['user_price'], 2) }}">Sell</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <script>
                // Function to trigger the investment price scheduler and reload the page
                function runInvestmentPriceScheduler() {
                    // Make an AJAX request to trigger the investment price scheduler
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '/run-investment-price-scheduler', true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                            // Reload the page after the scheduler has executed
                            location.reload();
                        }
                    };
                    xhr.send();
                }

                // Delay the execution of the investment price scheduler by 1 minute (60,000 milliseconds)
                setTimeout(runInvestmentPriceScheduler, 10000);
            </script>

        </div>
    </div>
</x-app-layout>
