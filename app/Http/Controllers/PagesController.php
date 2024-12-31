<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
public function index(){
return view('pages.index');

}

public function showContactForm(){
    return view('pages.contact');
    }


    public function submitContactForm(Request $request){
    $data=[
   'name'=>$request ->name,
   'emai'=>$request ->email,
   'message'=>$request ->message,
    ];
    Mail::to('mayarfqutishat@gmail.com')->send(new VisitorContact($data));
}       
}
