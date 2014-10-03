<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

use Guildle\Harvester\Harvester;
use Guildle\Harvester\ChartoDb;
use Illuminate\Support\Facades\Auth;

$router->get('/', array('as' => 'home', 'uses' => 'PagesController@home'));
$router->get('contact', array('as' => 'contact', 'uses' => 'PagesController@showContact'));
$router->post('contact', array('as' => 'contact', 'uses' => 'PagesController@sendContact'));
$router->resource('faq', 'FaqController');
$router->get('login', array('as' => 'login', 'uses' => 'AuthController@login'));
$router->get('logout', array('as' => 'logout', 'uses' => 'AuthController@logout'));
$router->get('validate', array('as' => 'validate', 'uses' => 'AuthController@validate'));
$router->get('userdata', array('as' => 'userdata', 'uses' => 'AuthController@userData'));
$router->post('userdata', array('as' => 'userdata', 'uses' => 'AuthController@saveuserData'));


$router->get('test', function(ChartoDb $character)
{

	dd(Guildle\Boss::all());
	// return Auth::user();


});


// /
// /login
// /player
// /character
// /guild
// /application
// /faq
// /contact