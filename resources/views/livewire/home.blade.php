<?php

use function Livewire\Volt\{state, computed, action};

state([
    'counter' => auth()->user()->sponsee_limit,
    'checkboxChecked' => auth()->user()->sponsee_limit > 0 ? true : false,
    'preferedLanguages' => json_decode(auth()->user()->preferred_languages),
    'country' => auth()->user()->country_code,
    'languages' => DB::table('languages')->get(),
    'countries' => DB::table('countries')->get()
]);

$decrement = action(function () {
    if ($this->checkboxChecked && $this->counter > 0) {
        $this->counter--;
    }
});

$increment = action(function () {
    if ($this->checkboxChecked) {
        $this->counter++;
    }
});

$toggleCheckbox = action(function () {
    $this->checkboxChecked = !$this->checkboxChecked;
    if (!$this->checkboxChecked) {
        $this->counter = 0;
    }
});

$setCountry = action(function ($country) {
    $this->country = $country;
});

$save = action(function () {
    // Save the data to the database
    $this->validate([
        'counter' => 'required|numeric',
        'country' => 'required',
        'preferedLanguages' => 'required'
    ]);

    auth()->user()->update([
        'sponsee_limit' => $this->counter,
        'country_code' => $this->country,
        'preferred_languages' => json_encode($this->preferedLanguages)
    ]);

    session()->flash('message', 'Settings saved successfully.');
});

?>

<div>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h1 class="text-2xl font-semibold">{{ __('Welcome, ') . explode(' ', auth()->user()->name)[0] }}</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('You can edit these settings to get a customized experience.') }}</p>
    </div>
    <div class="border-t border-gray-200 dark:border-gray-700"></div>
    <div class="p-6">
        <form wire:submit.prevent="save">
            <div class="max-w-md mx-auto">
                <label class="inline-flex items-center me-5 cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" wire:click="toggleCheckbox" @if ($checkboxChecked) checked @endif>
                    <div class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Ready to sponsor</span>
                </label>
            </div>
            <!-- How many sponsees number input -->
            <label for="sponsees-input" class="sr-only">How many can you sponsor:</label>
            <div class="relative flex items-center pt-3 max-w-md mx-auto" @if (!$checkboxChecked) style='display:none' @endif>
                <button type="button" wire:click="decrement" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none" @if(!$checkboxChecked) disabled @endif>
                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                    </svg>
                </button>
                    <input type="text" wire:model="counter" class="bg-gray-50 border-x-0 border-gray-300 h-11 font-medium text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full pb-6 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                <div class="absolute bottom-1 start-1/2 -translate-x-1/2 rtl:translate-x-1/2 flex items-center text-xs text-gray-400 space-x-1 rtl:space-x-reverse">
                    <span>Willing to sponsor</span>
                </div>
                <button type="button" wire:click="increment" class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none" @if(!$checkboxChecked) disabled @endif>
                    <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                    </svg>
                </button>
            </div>
            <!-- Choose your country -->
            <div class="pt-4"></div>
            <select id="countries" wire:model='country' wire:change="setCountry($event.target.value)" class="max-w-md mx-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option selected>Your country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                @endforeach
            </select>
            <div class="max-w-md mx-auto pt-3 bg-gray">
                <label for="languages" class="pb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Languages</label>
                <select id="languages" wire:model="preferedLanguages" multiple placeholder="Preferred Languages..." class="max-w-md mx-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    @foreach ($languages as $lang)
                        <option value="{{ $lang->code }}">{{ $lang->name }}</option>
                    @endforeach
                    <!-- Add more languages as needed -->
                </select>
            </div>
            <button type="submit" class="mt-4 block max-w-md mx-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg p-3 w-full focus:ring-blue-500 focus:outline-none dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-500 dark:focus:outline-none">{{ __('Save') }}</button>
            <div class="mt-2 mx-auto max-w-md">
                @error('counter')
                    <span class="text-red-500 block">{{ $message }}</span>
                @enderror
                @error('country')
                    <span class="text-red-500 block">{{ $message }}</span>
                @enderror
                @error('preferedLanguages')
                    <span class="text-red-500 block">{{ $message }}</span>
                @enderror
            </div>
        </form>
    </div>
</div>
