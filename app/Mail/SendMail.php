<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
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
        $cc = [];
        $bcc = [];
        $files = [];
		$h_namespace = '';
		$h_code = '';
        
        if( array_key_exists("copys", $this->pattern) && $this->pattern['copys']) {
            $cc = $this->pattern['copys'];
        }
        if( array_key_exists("bcc", $this->pattern) && $this->pattern['bcc']) {
            $bcc = $this->pattern['bcc'];
        }
        if( array_key_exists("files", $this->pattern) && $this->pattern['files']) {
            $files = $this->pattern['files'];
        }
		
		if( array_key_exists("h_namespace", $this->pattern) && $this->pattern['h_namespace'])
            $h_namespace = $this->pattern['h_namespace'];
		if( array_key_exists("h_code", $this->pattern) && $this->pattern['h_code'])
            $h_code = $this->pattern['h_code'];

        $message = $this->from('noreply@gree-app.com.br')
        ->view('emails.'. $this->pattern['template'])
        ->cc($cc)
        ->bcc($bcc)
        ->subject($this->pattern['subject'])
        ->with([
            'body' => $this->pattern,
        ]);

        foreach ($files as $file) {
            $message = $this->attach($file->url, [
                'as' => $file->name,
            ]);
        }
		
		if ($h_namespace && $h_code) {
			$message = $this->withSwiftMessage(function ($header) use ($h_namespace, $h_code) {
				$header->getHeaders()->addTextHeader(
					'h_namespace', $h_namespace
				);
				$header->getHeaders()->addTextHeader(
					'h_code', $h_code
				);
			});
		}
		

        return $message;
    }
}
