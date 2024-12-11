<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{

function page(Request $request){

		$auth = session('auth');
		if ($auth) {
		 return redirect()->route('dashboard');
		}

		$err = $request->input('err');

		return view('login',['err'=>$err]);

}

function sendForm(Request $request){

		$email = $request->input('email'); ;
		$email = strtolower($email);
		$pass = $request->input('pass'); ;

		$emailUser="nixusa@gmail.com";
		$passUser="DriveMax";

		if ($email==$emailUser AND $pass==$passUser) {
				session(['auth' => '1']);
				return redirect()->route('dashboard');
		}else{
				return redirect()->route('loginPage', ['err' => 1]);
		}

}


}
