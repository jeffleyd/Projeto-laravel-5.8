<?php

namespace App\Helpers\Commercial;

use App\Helpers\Commercial\Orders\OrdersInvoice;
use NFePHP\NFe\Common\Standardize;
use Ddeboer\Imap\Server;

class OrderInvoiceEmail
{
    private $connection = null;
    private $server = null;
    private $username = null;
    private $password = null;
    private $mailbox = null;
    private $attachments = null;
    private $id_msg = null;
    
    public function __construct($server, $username, $password)
    {   
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->executeConnection();
    }


    private function executeConnection()
    {
        $server = new Server($this->server, 993, '/imap/ssl/novalidate-cert');
        $this->connection = $server->authenticate($this->username, $this->password);

        if(!$this->connection->ping())
            throw new \Exception('Não foi possível connectar, servidor com instabilidade');
    }

    public function startTaskInvoice() 
    {
        if($this->connection->hasMailbox('INBOX')) {
            $this->mailbox = $this->connection->getMailbox('INBOX');
            $this->executeMessages($this->mailbox->getMessages());
        }
    }

    private function executeMessages($messages) 
    {
        foreach ($messages as $message) {

            $this->id_msg = $message->getNumber();
            $this->attachments = $message->getAttachments();
            if(count($this->attachments) > 0) {
                $this->readAttachments();
            }
            $this->mailbox->getMessage($this->id_msg)->delete();
            $this->connection->expunge();   
        }
        $this->connection->close();
    }

    public function readAttachments() 
    {
        foreach ($this->attachments as $attach) {

            if(stristr($attach->getFilename(), 'xml')) {    

                $string_xml = $attach->getDecodedContent();
                $std = new Standardize($string_xml);
                $xml = $std->toArray();
                
                if(!empty($xml['NFe'])) {
                    $cfop = $xml['NFe']['infNFe']['det'][0]['prod']['CFOP'];
                    if($cfop != 6202) {
                        $invoice = new OrdersInvoice($std);
                        $invoice->saveInvoice();
                    }
                }    
            }
        } 
    }
}    