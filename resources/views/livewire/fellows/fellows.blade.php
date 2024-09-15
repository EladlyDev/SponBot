<?php

use function Livewire\Volt\{state};

// Initialize state to manage the active view
state('activeView', 'sponsorship'); // Default to 'users' view

?>

<div>
    <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <!-- Users Link -->
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium focus:outline-none transition duration-150 ease-in-out {{ $activeView === 'users' ? 'text-gray-800 dark:text-gray-200 border-b-2 border-indigo-500' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}"
                                wire:click="$set('activeView', 'users')">
                            {{ __('Users') }}
                        </button>

                        <!-- Requests Link -->
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium focus:outline-none transition duration-150 ease-in-out {{ $activeView === 'requests' ? 'text-gray-800 dark:text-gray-200 border-b-2 border-indigo-500' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}"
                                wire:click="$set('activeView', 'requests')">
                            {{ __('Requests') }}
                        </button>

                        <!-- Sponsorship Link -->
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium focus:outline-none transition duration-150 ease-in-out {{ $activeView === 'sponsorship' ? 'text-gray-800 dark:text-gray-200 border-b-2 border-indigo-500' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}"
                                wire:click="$set('activeView', 'sponsorship')">
                            {{ __('Sponsorship') }}
                        </button>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <!-- Users Link -->
                <button wire:click="$set('activeView', 'users')" class="block w-full text-left px-4 py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out {{ $activeView === 'users' ? 'text-gray-800 dark:text-gray-200 border-l-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    {{ __('Users') }}
                </button>
                <!-- Requests Link -->
                <button wire:click="$set('activeView', 'requests')" class="block w-full text-left px-4 py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out {{ $activeView === 'requests' ? 'text-gray-800 dark:text-gray-200 border-l-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    {{ __('Requests') }}
                </button>
                <!-- Sponsorship Link -->
                <button wire:click="$set('activeView', 'sponsorship')" class="block w-full text-left px-4 py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out {{ $activeView === 'sponsorship' ? 'text-gray-800 dark:text-gray-200 border-l-4 border-indigo-500' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    {{ __('Sponsorship') }}
                </button>
            </div>
        </div>
    </nav>

    <!-- Content based on the active view -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if ($activeView === 'users')
            <livewire:fellows.users />
        @elseif ($activeView === 'requests')
            <livewire:fellows.requests />
        @elseif ($activeView === 'sponsorship')
            <livewire:fellows.sponsorship />
        @endif
    </div>
</div>
