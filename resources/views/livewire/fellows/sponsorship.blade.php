<?php

use function Livewire\Volt\{state, action};
use App\Models\Sponsorship;
use App\Models\User;
use App\Models\ChatMessage;

$userId = auth()->user()->id;

state([
    'sponsor' => Sponsorship::where("sponsee_id", $userId)->get()->map(function ($sponsorship) {
        return $sponsorship->sponsor;
    }),
    'sponsees' => Sponsorship::where("sponsor_id", $userId)->get(),
    'activeChat' => null,
    'messages' => null,
    'userId' => $userId,
    'newMessage' => '',
]);

$setActiveChat = action(function ($id) {
    $userId = $this->userId;
    $activeChat = User::find($id);
    $messages = ChatMessage::where('sender_id', $userId)
        ->where('receiver_id', $id)
        ->orWhere('sender_id', $id)
        ->where('receiver_id', $userId)
        ->orderBy('created_at', 'asc')
        ->get();
    
    $this->activeChat = $activeChat;
    $this->messages = $messages;

    // Mark all messages as read
    ChatMessage::where('sender_id', $id)
        ->where('receiver_id', $userId)
        ->update(['is_read' => true]);
});

$sendMessage = action(function () {
    if (strlen(trim($this->newMessage)) == 0) {
        return;
    }

    $userId = $this->userId;
    $receiverId = $this->activeChat->id;
    $message = $this->newMessage;

    ChatMessage::create([
        'sender_id' => $userId,
        'receiver_id' => $receiverId,
        'message' => $message,
    ]);

    $this->newMessage = '';
    $this->messages = ChatMessage::where('sender_id', $userId)
        ->where('receiver_id', $receiverId)
        ->orWhere('sender_id', $receiverId)
        ->where('receiver_id', $userId)
        ->orderBy('created_at', 'asc')
        ->get();
});

?>

<div x-data="{ isSidebarOpen: false }" class="flex flex-col h-screen bg-white text-black dark:bg-gray-900 dark:text-white">
    <!-- Mobile Toggle for Sidebar -->
    <div class="md:hidden p-4 border-b border-gray-300 dark:border-gray-700 flex justify-between items-center">
        <button 
            x-on:click="isSidebarOpen = !isSidebarOpen" 
            class="text-sm bg-blue-600 text-white px-3 py-1 rounded-lg"
        >
            Chats
        </button>
        <!-- The active chat header should be hidden on small screens when sidebar is open -->
    </div>

    <!-- Main Content -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <div 
            x-show="isSidebarOpen || window.innerWidth >= 768" 
            x-transition 
            class="md:w-1/3 w-[50%] bg-white dark:bg-gray-900 border-r border-gray-300 dark:border-gray-700 p-4 flex-col md:flex"
        >
            <!-- Search Bar -->
            <form class="mb-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
                        </svg>
                    </span>
                    <input type="search" id="search" class="w-full p-2 pl-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
                </div>
            </form>

            <!-- Chats List -->
            <div class="flex-grow overflow-y-auto">
                @if (!$sponsor->isEmpty())
                    <p class="text-sm font-semibold">Your Sponsor</p>
                    <div 
                    class="p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg" 
                    wire:click="setActiveChat('{{ $sponsor[0]->id }}')"
                    x-on:click="isSidebarOpen = false">
                    <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                            <div class="ml-3">
                                <p class="text-sm font-semibold">{{ $sponsor[0]->name }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!$sponsees->isEmpty())
                    <p class="text-sm font-semibold">Your Sponsees</p>
                    @foreach ($sponsees as $sponsee)
                       <div 
                            class="p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg" 
                            wire:click="setActiveChat('{{ $sponsee->sponsee->id }}')"
                            x-on:click="isSidebarOpen = false">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">{{ $sponsee->sponsee->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if ($sponsor->isEmpty() && $sponsees->isEmpty())
                <div class="flex justify-center items-center h-[100%]">
                    <p class="text-sm font-semibold">Add a sponsor/sponsees to chat with</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Chat Area -->
         @if ($activeChat)
            <div class="flex-1 flex flex-col">
                <!-- Chat Header (Visible only when a chat is open) -->
                <div class="p-4 border-b border-gray-300 dark:border-gray-700 flex items-center bg-white dark:bg-gray-900 md:flex">
                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full"></div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold">{{ $activeChat->name }}</p>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="flex-grow overflow-y-auto p-4">
                    <div class="space-y-2">
                        <!-- Incoming Message -->
                        @foreach ($messages as $message)
                            @if ($message->sender_id == $userId)
                                <div class="flex justify-end">
                                    <div class="bg-blue-600 text-white p-2 rounded-lg max-w-xs text-sm">
                                        {{ $message->message }}
                                    </div>
                                    @if (!$message->is_read && $message->sender_id == $userId)
                                        <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 ml-2 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                            @else
                                <div class="flex">
                                    <div class="bg-gray-100 dark:bg-gray-800 p-2 rounded-lg max-w-xs text-sm">
                                        {{ $message->message }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Message Input (Always visible at the bottom) -->
                <form  wire:submit.prevent="sendMessage" class="p-4 border-t border-gray-300 dark:border-gray-700">
                    <div class="relative">
                        <input wire:model="newMessage" type="text" class="w-full p-2 pl-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500" placeholder="Type a message...">
                        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-sm px-4 py-1 bg-blue-600 text-white hover:bg-blue-700 rounded-lg">Send</button>
                    </div>
                </form>
            </div>
        @else
            <div class="flex-1 flex justify-center items-center">
                <p class="text-sm font-semibold">Select a chat to start messaging</p>
            </div>
        @endif
    </div>
</div>
