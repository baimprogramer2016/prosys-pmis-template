<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index(request $request){

        $name  = $request->name;
        $email = $request->email;
        $status = $request->status;
        $description = $request->description;
      
        if($status == 'new'){
            $subject = "Pemberitahuan Proses Cek";
            $body = "Anda Memiliki Dokument yang harus di Cek";
        }elseif($status == 'check'){
            $subject = "Pemberitahuan Proses Review";
            $body = "Anda Memiliki Dokument yang harus di Review";
        }elseif($status == 'review'){
            $subject = "Pemberitahuan Proses Approve";
            $body = "Anda Memiliki Dokument yang harus di Approve";
        }
      
        $data = [
            'subject' => $subject,
            'name' => $name,
            'description' => $description,
            "body" => $body
     ];


    Mail::to($email)->send(new SendEmail($data));
    }
}
