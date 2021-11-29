<?php

namespace App\Jobs;

use App\Model\SacClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SacTryCompletedProtocol implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $protocol;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->protocol->attemps_call = $this->protocol->attemps_call+1;
        $client = SacClient::find($this->protocol->client_id);
        $source = array('(', ')', ' ', '-');
        $replace = array('', '', '', '');

        $phone = "";
        if ($client->phone) {
            $phone = str_replace($source, $replace, $client->phone);
        } else {
            $phone = str_replace($source, $replace, $client->phone_2);
        }

        $this->protocol->api_call_id = total_voice_call('55'.$phone);
        $this->protocol->save();

        return;
    }
}
