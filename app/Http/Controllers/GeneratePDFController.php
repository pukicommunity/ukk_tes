<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Borrow;
use App\Models\User;

class GeneratePDFController extends Controller
{
    public function userPDF(){
        $users = User::all();
        $userCount = User::count();
        $data = [
            'title' => "User Report PDF",
            'date' => date('d/m/Y'),
            'users' => $users,
            'userCount' => $userCount
        ];
        $date = date('d-m-Y');
        $pdf = PDF::loadView('pdf.user',$data);
        return $pdf->download('userpdf-'. $date.'.pdf');
    }
    public function borrowPDF(){
        $borrows = Borrow::all();
        $borrowCount = Borrow::count();
        $data = [
            'title' => "Borrow Report PDF",
            'date' => date('d-m-Y'),
            'borrows' => $borrows,
            'borrowCount' => $borrowCount,
        ];
        $date = date('d-m-Y');
        $pdf = PDF::loadView('pdf.borrow',$data);
        return $pdf->download('borrowpdf-'.$date.'.pdf');
    }
    public function returnPDF(){
        $returns = Borrow::whereNotNull('return_at')->get();
        $returnsCount = Borrow::whereNotNull('return_at')->count();
        $data = [
            'title' => "Borrow Report PDF",
            'date' => date('d-m-Y'),
            'returns' => $returns,
            'returnCount' => $returnsCount,
        ];
        $date = date('d-m-Y');
        $pdf = PDF::loadView('pdf.return',$data);
        return $pdf->download('returnpdf-'.$date.'.pdf');
    }
}
