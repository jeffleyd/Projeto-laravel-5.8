<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailCopy extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->from('noreply@gree-app.com.br')
                ->view('emails.'. $this->pattern['template'])
                ->cc($this->pattern['copys'])
                ->subject($this->pattern['subject'])
                ->with([
                    'body' => $this->pattern,
                ]);
    }
}
