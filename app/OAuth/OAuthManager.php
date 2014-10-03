<?php namespace Guildle\OAuth;

use Illuminate\Support\Manager;
use Laravel\Socialite\Two\AbstractProvider as AbstractTwoProvider;
use Laravel\Socialite\Contracts;

class OAuthManager extends Manager implements Contracts\Factory {

	/**
	 * Get a driver instance.
	 */
	public function with($driver)
	{
		return $this->driver($driver);
	}

	/**
	 * Create Battle.net driver
	 */
	protected function createBattlenetDriver()
	{
		$config = $this->app['config']['services.battlenet'];

		return $this->buildProvider('Guildle\OAuth\BattlenetProvider', $config);
	}

	/**
	 * Build an OAuth 2 provider instance.
	 * @param  string  $provider
	 * @param  array  $config
	 * @return \Laravel\Socialite\Two\AbstractProvider
	 */
	protected function buildProvider($provider, $config)
	{
		return new $provider
		(
			$this->app['request'],
			$config['client_id'],
			$config['client_secret'],
			$config['redirect']
		);
	}

	/**
	 * Get the default driver name.
	 *
	 * @return string
	 */
	public function getDefaultDriver()
	{
		throw new \InvalidArgumentException("No Socialite driver was specified.");
	}

}