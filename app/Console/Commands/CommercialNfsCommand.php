<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Exceptions\FolderFetchingException;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;
use Webklex\IMAP\Commands\ImapIdleCommand;

class CommercialNfsCommand extends ImapIdleCommand {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commercial:nfs';

    /**
     * Holds the account information
     *
     * @var string|array $account
     */
    protected $account = "default";
	
	/**
     * Callback used for the idle command and triggered for every new received message
     * @param Message $message
     */
    public function onNewMessage(Message $message){
        $this->info("New message received: ".$message->subject);
    }
}