<?php namespace  Guildle\Providers;

use Guildle\OAuth\OAuthManager;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('Laravel\Socialite\Contracts\Factory', function($app)
		{
			return new OAuthManager($app);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Laravel\Socialite\Contracts\Factory'];
	}
}
