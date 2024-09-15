<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\MessageSent;

class SendMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message to the chat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $name = $this->ask('What is your name?');
        // $message = $this->ask('What is your message?');

        // MessageSent::dispatch($message, $name);
    }
}
