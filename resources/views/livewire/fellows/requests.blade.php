<?php

use function Livewire\Volt\{state, action};
use App\Models\User;
use App\Models\SponsorshipRequest;
use App\Models\Sponsorship;

state([
    'sentRequests' => SponsorshipRequest::where('sponsee_id', auth()->user()->id)->get(),
    'receivedRequests' => SponsorshipRequest::where('sponsor_id', auth()->user()->id)->get(),
    'isSponsor' => User::find(auth()->user()->id)->sponsee_limit,
]);

$cancelSponsorshipRequest = action(function ($request_id) {
    SponsorshipRequest::find($request_id) ? SponsorshipRequest::find($request_id)->delete() : null;

    $this->sentRequests = SponsorshipRequest::where('sponsee_id', auth()->user()->id)->get();
});

$acceptRequest = action(function ($request_id) {
    $request = SponsorshipRequest::find($request_id);
    if (!$request) return;
    $request->status = 'accepted';
    $request->save();

    $this->receivedRequests = SponsorshipRequest::where('sponsor_id', $request->sponsor_id)->get();

    // update sponsor's sponsee limit
    auth()->user()->sponsee_limit = auth()->user()->sponsee_limit === 1 ? 0 : auth()->user()->sponsee_limit - 1;
    auth()->user()->is_sponsor = true;
    auth()->user()->save();

    // create sponsorship
    $sponsorship = Sponsorship::where('sponsor_id', $request->sponsor_id)
        ->where('sponsee_id', $request->sponsee_id)
        ->first();

    if (!$sponsorship) {
        Sponsorship::create([
            'sponsor_id' => $request->sponsor_id,
            'sponsee_id' => $request->sponsee_id,
        ]);
    }

    // remove sponsees's other requests
    $otherRequests = SponsorshipRequest::where('sponsee_id', $request->sponsee_id)->where('id', '!=', $request_id)->get();
    foreach ($otherRequests as $otherRequest) {
        $otherRequest->delete();
    }
});

$rejectRequest = action(function ($request_id) {
    $request = SponsorshipRequest::find($request_id);
    $request->status = 'rejected';
    $request->save();

    $this->receivedRequests = SponsorshipRequest::where('sponsor_id', auth()->user()->id)->get();
});
?>

<div>
        <div class="flex flex-col items-center justify-center gap-6">
            <h2 class="text-lg font-bold text-white dark:text-white">Received Requests</h2>
            @if ($receivedRequests->isEmpty())
                <p class="text-md text-gray-500 dark:text-gray-400">You have not sent any sponsorship requests yet</p>
            @endif
            @foreach($receivedRequests as $request)
                @php
                    $fellow = User::find($request->sponsee_id);
                @endphp
                <div class="p-4 md:w-3/5 w-full border-[.7px] bg-white rounded-lg shadow-md dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-1/5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <div class="w-4/5">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{$fellow->name}}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{$fellow->country_code . " | " . strtoupper($fellow->preferred_languages)}}</p>
                        </div>
                        <div class="flex gap-2">
                            @if ($request->status === 'pending')
                                <button 
                                    type="button" 
                                    wire:click="acceptRequest({{ $request->id }})"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    Accept
                                    <!-- <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                    </svg> -->
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="rejectRequest({{ $request->id }})"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-700">
                                    Reject
                                    <!-- <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> -->
                                </button>
                            @elseif($request->status === 'accepted')
                                <button 
                                    type="button" 
                                    disabled
                                    class="text-white bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-green-600 dark:focus:ring-green-800">
                                    Accepted
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </button>
                            @else
                                <button 
                                    disabled
                                    type="button" 
                                    class="text-white bg-red-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500">
                                    Rejected
                                    <svg class="rtl:rotate
                                    -180 w-3.5 h-3.5 ms-2 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="border-t my-6 border-gray-200 dark:border-gray-700"></div>
        <div class="flex flex-col items-center justify-center gap-6">
            <h2 class="text-lg font-bold text-white dark:text-white">Sent Requests</h2>
            @if ($sentRequests->isEmpty())
                <p class="text-md text-gray-500 dark:text-gray-400">You have not sent any sponsorship requests yet</p>
            @endif
            @foreach($sentRequests as $request)
                @php
                    $fellow = User::find($request->sponsor_id);
                @endphp
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
                            @if ($request->status === 'accepted')
                                <button 
                                    type="button"
                                    disabled
                                    class="text-white bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-green-600 dark:focus:ring-green-800">
                                    Accepted
                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </button>
                            @elseif($request->status === 'rejected')
                                <button 
                                    disabled
                                    type="button" 
                                    class="text-white bg-red-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500">
                                    Rejected
                                    <svg class="rtl:rotate
                                    -180 w-3.5 h-3.5 ms-2 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>  
                            @else
                            <button
                                wire:click="cancelSponsorshipRequest({{ $request->id }})" 
                                type="button" 
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-700">
                                &nbsp;&nbsp;Cancel&nbsp;
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
</div>
