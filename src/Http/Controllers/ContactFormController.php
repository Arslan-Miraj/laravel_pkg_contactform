<?php

namespace Arslan\Contactform\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Arslan\Contactform\Mail\InquiryEmail;
use Arslan\Contactform\Models\Contact;
use Illuminate\Routing\Controller as BaseController;

class ContactFormController extends BaseController {

    public function create(){
        return view('contactform::create');
        // contactform is package name from 'ContactFormServiceProvider' & 'create' is view file in it.
    }


    public function store(Request $request){
        // return $request->all();
        $validation = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'subject' => 'required',
        ]);
        Contact::create($validation);

        $admin_email = \config('contactform.admin_email');
        if($admin_email === null || $admin_email === ''){
            echo "Email is not Set";
        }else{
            Mail::to($admin_email)->send(new InquiryEmail($validation));
        }
        return back()->with('success', 'Message sent, Wait for response');
    }
}