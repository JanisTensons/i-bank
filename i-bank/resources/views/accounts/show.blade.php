<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="flex items-center">
                <span class="mr-2">{{ __('Account') }}</span>
                <span class="text-sm text-gray-500">
                {{ $account->type }} |
                {{ $account->number }} |
                {{ $account->currency }} |
                {{ $account->balance }}
            </span>
            </div>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <button
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                        onclick="location.href='create'" type="submit">New transaction
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
