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
			$battletag = $this->socialite->driver('battlenet')->getBattletag($response['access_token'])['battletag'];
			$response['battletag'] = $battletag;

			$user = User::where('accountId', '=', $response['accountId'])->first();

			if (!$user)
			{
				$user = User::create($response);
				Auth::login($user); // premakn po saveuserData()

				return redirect(route('userdata'));
			}

			$user = $user->fill($response);
			$user->save();
			Auth::login($user);

			// $zones = ['eu', 'us', 'kr', 'tw'];

			// foreach ($zones as $zone)
			// {
			// 	$characters = $this->socialite->driver('battlenet')->getChars($zone, $response['access_token']);
			// }

			// $eu = $this->socialite->driver('battlenet')->getChars('eu', $response['access_token']);
			// $us = $this->socialite->driver('battlenet')->getChars('us', $response['access_token']);
			// $kr = $this->socialite->driver('battlenet')->getChars('kr', $response['access_token']);
			// $tw = $this->socialite->driver('battlenet')->getChars('tw', $response['access_token']);

			// dd([$eu, $us, $kr, $tw]);

			// User::find(Auth::user()->id)->characters()->delete();
			// $user->characters()->delete();

			// foreach($eu as $character)
			// {
			// 	if ($character['level'] > 10)
			// 	{
			// 		$char = new Character;
			// 		$char = $char->fill($character);
			// 		$char->zone = 'eu';
			// 		$user->characters()->save($char);
			// 	}
			// }

			// $this->chartodb->postLogin(Auth::user()->id);

			return redirect(route('home'));
		}
	}

	public function validate()
	{
		$battletag = $this->socialite->driver('battlenet')->getBattletag(Auth::user()->access_token)['battletag'];
		return $battletag;
	}

	public function logout()
	{
		Auth::logout();
		return redirect(route('home'));
	}

	public function userData()
	{
		return View('userdata');
	}

	public function saveuserData()
	{
		return 'save userdata';
	}

}
