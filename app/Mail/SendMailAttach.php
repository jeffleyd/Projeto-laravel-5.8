<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\BlogPostAttach;
use Illuminate\Support\Facades\Storage;

class SendMailAttach extends Mailable
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

        $message = $this->from('noreply@gree-app.com.br');
        $message = $this->view('emails.'. $this->pattern['template']);

        $files = BlogPostAttach::where('blog_post_id', $this->pattern['id'])->get();

                foreach ($files as $file) {
                    
                    $message = $this->attach($file->url, [
                        'as' => $file->name,
                    ]);
                }
                $message = $this->subject($this->pattern['subject']);
                $message =  $this->with([ 'body' => $this->pattern ]);
        
        return $message;
    }
}
