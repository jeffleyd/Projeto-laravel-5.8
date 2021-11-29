<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $pattern;
    private $email;
    private $create_notify;
    private $need_valid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pattern, $email, $create_notify = false, $need_valid = true)
    {
        $this->pattern = $pattern;
        $this->email = $email;
        $this->create_notify = $create_notify;
        $this->need_valid = $need_valid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		try {
			if ($this->create_notify) {
				NotifyUser(
					$this->pattern['subject'],
					$this->pattern['notify_r_code'],
					'fa-exclamation',
					'text-info',
					$this->pattern['description'],
					$this->pattern['notify_url']);
			}
			if ($this->need_valid) {
				if (filter_var($this->email, FILTER_VALIDATE_EMAIL))
					Mail::to($this->email)->send(new SendMail($this->pattern));
			} else {
				Mail::to($this->email)->send(new SendMail($this->pattern));
			}
		} catch (\Exception $e) {
		}
    }
}
