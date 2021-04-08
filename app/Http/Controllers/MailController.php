<?php

namespace App\Http\Controllers;

use App\Mail\FirstMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        $message = (new FirstMail())->onQueue('emails');
        Mail::to('abc@abc.com')->queue($message);

        return 'check you email!!';
    }
}
