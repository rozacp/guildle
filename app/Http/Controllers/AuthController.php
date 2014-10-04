<?php namespace Guildle\Http\Controllers;

use Guildle\Character;
use Guildle\Harvester\ChartoDb;
use Guildle\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class AuthController extends Controller
{
	public function __construct(SocialiteFactory $socialite, ChartoDb $chartodb)
	{
		$this->socialite = $socialite;
		$this->chartodb = $chartodb;
	}

	public function login()
	{
		if (Input::get('code') == NULL)
		{
			return $this->socialite->driver('battlenet')->redirect();
		}
		else
		{
			$response = $this->socialite->driver('battlenet')->getUser();
			$response['battletag'] = $this->battletag($response['access_token']);

			$user = User::where('accountId', '=', $response['accountId'])->first();

			if (!$user)
			{
				$user = User::create($response);
				Auth::login($user);

				return redirect(route('userdata'));
			}

			$user = $user->fill($response);
			$user->save();
			Auth::login($user);
			sleep(5);
			$this->chartodb->saveNewCharacters();

			// return redirect(route('home'));
		}
	}


	private function battletag($access_token)
	{
		return $this->socialite->driver('battlenet')->getBattletag($access_token)['battletag'];
	}

	public function logout()
	{
		Auth::logout();
		return redirect(route('home'));
	}

	public function showUserData()
	{
		return View('userdata');
	}

	public function saveUserData()
	{
		// save user form data into db, logs in user

		$this->chartodb->saveNewCharacters();

		return redirect(route('home'));
	}

}
