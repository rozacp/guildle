<?php namespace Guildle\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller {

	public function home()
	{
		if (Auth::check())
		{
			$msg = 'Welcome back, ' . Auth::user()->battletag;
		}
		else
		{
			$msg = 'Hi guest. ' . link_to('login', 'Login With Battle.net!');
		}

		return View('home')->with('msg', $msg);
	}

	public function showContact()
	{
		return View('contact');
	}

	public function sendContact()
	{
		return 'probably mail the contact request';
	}
}