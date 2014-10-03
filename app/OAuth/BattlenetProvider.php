<?php namespace Guildle\OAuth;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BattlenetProvider extends AbstractProvider implements ProviderInterface {

	protected $scopes = ['wow.profile'];

	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase('https://eu.battle.net/oauth/authorize', $state);
	}

	protected function getTokenUrl()
	{
		return 'https://eu.battle.net/oauth/token';
	}

	protected function getUserByToken($token) {}

	protected function mapUserToObject(array $user) {}

	protected function getTokenFields($code)
	{
		return
		[
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'code' => $code,
			'redirect_uri' => $this->redirectUrl,
			'grant_type' => 'authorization_code',
			'scope' => $this->scopes[0]
		];
	}

	public function getUser()
	{
		$response = $this->getHttpClient()->post($this->getTokenUrl(),
			[
				'headers' => ['Accept' => 'application/json'],
				'body' => $this->getTokenFields($this->getCode())
			]);

		return json_decode($response->getBody(), TRUE);
	}

	public function getBattletag($token)
	{
		$response = $this->getHttpClient()->get('https://eu.api.battle.net/account/user/battletag?access_token=' . $token,
			[
				'headers' => ['Accept' => 'application/json']
			]);

		return json_decode($response->getBody(), TRUE);
	}

	public function getChars($zone, $token)
	{
		$response = $this->getHttpClient()->get('https://' . $zone . '.api.battle.net/wow/user/characters?access_token=' . $token,
			[
				'headers' => ['Accept' => 'application/json']
			]);

		return json_decode($response->getBody(), TRUE)['characters'];
	}
}