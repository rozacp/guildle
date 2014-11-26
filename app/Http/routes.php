<?php
use Carbon\Carbon;
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

// Route::when('*', 'csrf', array('post', 'put', 'delete'));

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
	// $chartodb->updateCharacters(Guildle\User::find(1));

	$harvester->setParams('eu', 'haomarush', 'veider');
	// $harvester->setParams('eu', 'Ravenholdt', 'huntard');
	// $harvester->setParams('eu', 'Kazzak', 'dex');
	// $harvester->setParams('eu', 'Kazzak', 'poyo');
	return $harvester->charData();

	// $char = Guildle\Character::where('zone', 'eu')->where('realm', 'haomarush')->where('name', 'Weidah')->first();
	// $chartodb->writeCharacter($char);

});

// /
// /login
// /player
// /character
// /guild
// /application
// /faq
// /contact