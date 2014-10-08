<?php namespace Guildle\Http\Controllers;

use Guildle\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Guildle\Harvester\ChartoDb as ChartoDb;

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

			$user = User::where('accountId', $response['accountId'])->first();

			if (!$user)
			{
				$user = User::create($response);
				Auth::login($user);

				$this->chartodb->saveCharacters($user);
				$this->chartodb->updateCharacters($user);

				return $this->showUserData($user);
			}

			$user->fill($response);
			$user->save();
			Auth::login($user);

			$this->chartodb->saveCharacters($user);
			$this->chartodb->updateCharacters($user);

			return redirect(route('home'));
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

	public function showUserData($user)
	{
		return View('userdata');
	}

	public function saveUserData()
	{
		// save user form data into db, logs in user

		return redirect(route('home'));
	}
}
