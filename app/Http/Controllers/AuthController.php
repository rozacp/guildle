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

			// after i get da tokn
			$battletagAndUserId = $this->battletagAndUserId($response['access_token']);

			$response['battletag'] = $battletagAndUserId['battletag'];
			$response['accountId'] = $battletagAndUserId['id'];

			$user = User::where('accountId', $response['accountId'])->first();

			if (!$user)
			{
				$user = User::create($response);  // sam en updateOrcreate ?

				Auth::login($user);

				// $this->chartodb->saveCharacters($user);

				return $this->showUserData($user); // spuci une funkcije spodaj in sam returni view
			}
			else
			{
				$user->fill($response);

				$user->save();

				Auth::login($user);

				$this->chartodb->saveCharacters($user);

				return redirect(route('home'));
			}
		}
	}

	private function battletagAndUserId($access_token)
	{
		return $this->socialite->driver('battlenet')->getBattletagAndUserId($access_token);
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
