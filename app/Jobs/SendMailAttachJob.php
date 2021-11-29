<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailAttach;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMailAttachJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $pattern;
    private $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pattern, $email)
    {
        $this->pattern = $pattern;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            Mail::to($this->email)
                ->send(new SendMailAttach($this->pattern));

    }
}
