<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SponBot</title>

        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" />
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-1 gap-4 py-6 sm:grid-cols-2 lg:grid-cols-3 sm:py-10">
                        <div class="flex justify-center lg:col-start-2">
                            <img src="{{ asset('images/logo.png') }}" alt="SponBot Logo" class="h-12">
                            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 dark:text-gray-100 ml-4">SponBot</h1>
                        </div>
                        @if (Route::has('login'))
                            <div class="flex justify-center sm:justify-end col-span-2 sm:col-span-1 lg:col-start-3">
                                <livewire:welcome.navigation />
                            </div>
                        @endif
                    </header>

                    <main class="mt-6">
                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <!-- Introduction Section -->
                            <div class="rounded-lg bg-white p-6 shadow-lg ring-1 ring-white/[0.05] dark:bg-zinc-900 dark:ring-zinc-800">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Welcome to SponBot</h2>
                                <p class="mt-4 text-gray-600 dark:text-gray-400">
                                    SponBot is designed to guide you through your recovery journey. Easily connect with sponsors or sponsees and get the support you need to overcome addiction, anytime, anywhere.
                                </p>
                            </div>

                            <!-- Meetings Section -->
                            <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-lg ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Real-time Global Meetings</h3>
                                <p class="text-gray-600 dark:text-gray-400">Access local and global meetings in real-time, synced with your time zone. Find support groups that fit your schedule and location.</p>
                            </div>

                            <!-- Sponsor Request Section -->
                            <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-lg ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Connect with a Sponsor</h3>
                                <p class="text-gray-600 dark:text-gray-400">Looking for a sponsor? SponBot makes it easy to connect with available sponsors in your area and kickstart your journey towards a healthier life.</p>
                            </div>

                            <!-- Support Connection Section -->
                            <div class="flex mb-10 lg:mb-0 flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-lg ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 w-full">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Reach Out, Anytime</h3>
                                <p class="text-gray-600 dark:text-gray-400">Find people available for support in real-time. Whether you need a quick chat or deep conversation, SponBot is here to help.</p>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
