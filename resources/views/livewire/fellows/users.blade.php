
<?php

use function Livewire\Volt\{state, action, computed};
use App\Models\User;
use App\Models\SponsorshipRequest;

$sponsor = User::where("id", SponsorshipRequest::where('sponsee_id', auth()->user()->id)->where('status', 'accepted')->first()?->sponsor_id)->get();

state([
    'filter' => [
        'sponsors' => false,
        'sponsees' => false,
    ],
    'fellows' => User::where('id', '!=', auth()->user()->id)  // Ensure this condition is applied first
                ->when(auth()->user()->country_code, function ($query) {
                    return $query->where('country_code', auth()->user()->country_code);
                })
                ->when(auth()->user()->preferred_languages, function ($query) {
                    return $query->orWhereIn('preferred_languages', explode(', ', auth()->user()->preferred_languages));
                })
                ->limit(20)
                ->get(),
    'search' => '',
    'sponsorshipRequests' => SponsorshipRequest::where('sponsor_id', auth()->user()->id)->get(),
    'sponsor' => SponsorshipRequest::where('sponsee_id', auth()->user()->id)->where('status', 'accepted')->first()?->sponsor,
]);

$setSearch = action(function () {
    $this->fellows = User::where('id', '!=', auth()->user()->id)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->limit(20)
                    ->get();
});

$setFilter = action(function ($type) {
    $this->filter[$type] = !$this->filter[$type];
    $this->fellows = User::where('id', '!=', auth()->user()->id)
                    ->whereIn('preferred_languages', explode(', ', auth()->user()->preferred_languages))
                    ->where('country_code', auth()->user()->country_code)
                    ->when($this->filter['sponsors'], function ($query) {
                        return $query->where('sponsee_limit', '>', 0);
                    })
                    ->when($this->filter['sponsees'], function ($query) {
                        return $query->where('sponsee_limit', 0);
                    })
                    ->get();
});

$isSponRequestExists = action(function ($sponsor_id) {
    $sponsee = auth()->user();

    return SponsorshipRequest::where('sponsor_id', $sponsor_id)
    ->where('sponsee_id', $sponsee->id)->exists();
});

$sendSponsorshipRequest = action(function ($sponsor_id) {
    $sponsee = auth()->user();

    // Create a new sponsorship request
    $request = SponsorshipRequest::create([
        'sponsee_id' => $sponsee->id,
        'sponsor_id' => $sponsor_id,
    ]);
});

$cancelSponsorshipRequest = action(function ($sponsor_id) {
    $sponsee = auth()->user();

    // Create a new sponsorship request
    SponsorshipRequest::where('sponsor_id', $sponsor_id)
    ->where('sponsee_id', $sponsee->id)
    ->firstOrFail()
    ->delete();
});
?>

<div class="pb-6">
    <form wire:submit.prevent="setSearch" class="max-w-lg mx-auto">
        <div x-data="{filterToggle:false}" class="flex relative">
            <label for="search-dropdown" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your Email</label>
            <button x-on:click="filterToggle = !filterToggle" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" type="button">
                Filter By
                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <div x-show="filterToggle" class="absolute z-10 top-[50px] bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700" x-cloak>
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                    <li>
                        <label class="flex justify-between items-center w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer">
                            Sponsors
                            <input type="checkbox" wire:click="setFilter('sponsors')" class="ml-2 cursor-pointer outline-none focus:outline-none">
                        </label>
                    </li>
                    <li>
                        <label class="flex justify-between items-center w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer">
                            Sponsees
                            <input type="checkbox" wire:click="setFilter('sponsees')" class="ml-2 cursor-pointer outline-none focus:outline-none">
                        </label>
                    </li>
                </ul>
            </div>
            <div class="relative w-full">
                <input type="search" wire:model="search" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" placeholder="Search by a name/username" />
                <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>
        </div>
    </form>
    <div class="border-t my-6 border-gray-200 dark:border-gray-700"></div>
    <!-- Grid for cards -->
    <div class="flex flex-col items-center justify-center gap-6">
        @foreach ($fellows as $fellow)
            <div class="p-4 md:w-3/5 w-full border-[.7px] bg-white rounded-lg shadow-md dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-1/5">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <div class="w-4/5">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{$fellow->name}}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{$fellow->country_code . " | " . strtoupper($fellow->preferred_languages)}}</p>
                        @if ($fellow->sponsee_limit > 0)
                            <p class="text-sm text-green-500">Available to sponsor</p>
                        @endif
                        <p class="text-sm text-green-500"></p>
                    </div>
                    <div>
                        <!-- Request sponsorship button -->
                        @if (!$this->isSponRequestExists($fellow->id))
                            <button 
                                wire:click.throttle.10000ms="sendSponsorshipRequest({{ $fellow->id }})" 
                                @if (!$fellow->sponsee_limit > 0 || SponsorshipRequest::where(["sponsor_id" => auth()->user()->id, "sponsee_id" => $fellow->id])->exists() || $sponsor) style="visibility:hidden" @endif 
                                type="button" 
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Request
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </button>
                        @else
                            @if (SponsorshipRequest::where('sponsor_id', $fellow->id)->where('sponsee_id', auth()->user()->id)->first()->status === 'accepted')
                                <button 
                                    type="button" 
                                    disabled
                                    class="text-white bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-green-600 dark:focus:ring-green-800">
                                    Accepted
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </button>
                            @elseif(SponsorshipRequest::where('sponsor_id', $fellow->id)->where('sponsee_id', auth()->user()->id)->first()->status === 'rejected')
                                <button 
                                    disabled
                                    type="button" 
                                    style="visibility:hidden"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-700">
                                    Rejected
                                    <svg class="rtl:rotate
                                    -180 w-3.5 h-3.5 ms-2 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>  
                            @else
                                <button
                                    wire:click.throttle.10000ms="cancelSponsorshipRequest({{ $fellow->id }})" 
                                    @if (!$fellow->sponsee_limit > 0 || auth()->user()->sponsee_limit !== 0) hidden @endif 
                                    type="button" 
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-700">
                                    &nbsp;&nbsp;Cancel&nbsp;
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        @endforeach
        @if ($fellows->isEmpty())
            <p class="text-lg text-gray-500 dark:text-gray-400">
                No fellows found,
                try to change your search criteria
                or check back later.
            </p>
        @endif
    </div>
</div>
