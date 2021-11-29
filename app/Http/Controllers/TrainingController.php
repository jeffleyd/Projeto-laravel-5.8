<?php

namespace App\Http\Controllers;
//sistema-gree\app\Imports\DefaultImport.php
use Illuminate\Support\Facades\DB;
use App\Imports\DefaultImport;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use App\Classes\FPDF;
use App\EventPerson;
use Maatwebsite\Excel\Facades\Excel;
class TrainingController extends Controller
{
    //Constroi certificado
    public function generateCertificate(Request $request)
    {
        $training = DB::table('event_training')->get();

        return view('gree_i.administration.marketing.training_generate_certificate', [
            'training' => $training,
        ]);
    }

    //Certificado
    public function certificate(Request $request)
    {
		if (!$request->training)
			return redirect()->back()->with('error', 'Você precisa selecionar o evento.');
			
        $fullName = strtoupper($request->fullName);
        $cpf = $request->cpf;
        $date = date('d  m  Y');

        $existCpf = DB::table('event_person')->where('cpfCnpj', $cpf)->first();

        if (!$existCpf) {
            return Redirect()->back()->with('error', "CPF não cadastrado!");
        } else {
            //Gera certificado em PDF
            //image
            $imgCertificate = 'media/certificado.png';
            $pdf = new FPDF();
            $pdf->AddPage('L');
            $pdf->Image($imgCertificate, 7, 7, 283, 197, 'PNG');
            //name
            $pdf->SetXY(85, 103);
            $pdf->SetFont('Arial', '', 20);
            $pdf->Cell(160, 10, $fullName, 0);
            //date
            $pdf->SetXY(245, 151);
            $pdf->SetFont('Arial', '', 16);
            $pdf->Cell(35, 10, $date, 0);

            $pdf->Output('D', $fullName . '.pdf');
        }
    }


}
