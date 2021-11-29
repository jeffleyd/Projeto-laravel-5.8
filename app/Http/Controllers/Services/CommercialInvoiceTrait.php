<?php

namespace App\Http\Controllers\Services;

use App;
use Illuminate\Support\Facades\Storage;
use App\Model\Commercial\OrderInvoiceErrors;
use NFePHP\DA\NFe\Danfe;

Trait CommercialInvoiceTrait
{
    use FileManipulationTrait;
        
    public function getConvertPDF($xml) 
    {
        try {
            $logo = 'data://text/plain;base64,'. base64_encode(file_get_contents(realpath(public_path() . '/admin/app-assets/images/logo/logo-gree.png')));
            $danfe = new Danfe($xml->asXML());
            return $danfe->render($logo);
        } 
        catch (\Exception $e) {
            return false;
        }
    }

    public function uploadNfe($file, $desc_nfe, $type)
    {
        if (!empty($file)) {
            
            $mimetype = $type == 'xml' ? 'application/xml' : 'application/pdf';
            $content_file = $type == 'xml' ? $file->asXML() : $file;
            try {    
                $filename = 'commercial_nfs/'.$desc_nfe.'.'.$type.'';
                Storage::disk('s3')->put($filename, $content_file, ['mimetype' => $mimetype]);
                return Storage::disk('s3')->url($filename);
            } 
            catch (Exception $e) {
                return false;
            }    
        } else {
            return false;
        }
    }

    public function setErrorInvoice($msg, $nfe_number, $nfe_key, $type_invoice) 
    {
        try {
            
            $error_invoice = new OrderInvoiceErrors;
            $error_invoice->message = $msg;
            $error_invoice->number_nfe = $nfe_number;
            $error_invoice->key_nfe = $nfe_key;
            $error_invoice->type_invoice = $type_invoice;
            $error_invoice->save();

        } catch(\Exception $e) {
            Log::error($e.getMessage());
        }    
    }   
} 