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
$router->get('userdata', array('as' => 'userdata', 'uses' => 'AuthController@showUserData'));
$router->post('userdata', array('as' => 'userdata', 'uses' => 'AuthController@saveUserData'));


$router->get('test', function(ChartoDb $chartodb, Harvester $harvester)
{

	$chartodb->updateNewCharacters(Auth::user());

	// $harvester->setParams('eu', 'haomarush', 'weider');
	// dd($harvester->gear());


	// $characters = Guildle\User::find(1)->characters()->get();

	// $characters->each(function($character) {

	// 	$talents = $character->talents()->get();

	// 	$talents->each(function($talent) {

	// 		var_dump($talent->talent_name);

	// 	});
	// });
});

// /
// /login
// /player
// /character
// /guild
// /application
// /faq
// /contact