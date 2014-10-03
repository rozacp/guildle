<?php namespace Guildle\Providers;

use Illuminate\Foundation\Support\Providers\FilterServiceProvider as ServiceProvider;

class FilterServiceProvider extends ServiceProvider {

	/**
	 * The filters that should run before all requests.
	 *
	 * @var array
	 */
	protected $before = [
		'Guildle\Http\Filters\MaintenanceFilter',
	];

	/**
	 * The filters that should run after all requests.
	 *
	 * @var array
	 */
	protected $after = [
		//
	];

	/**
	 * All available route filters.
	 *
	 * @var array
	 */
	protected $filters = [
		'auth' => 'Guildle\Http\Filters\AuthFilter',
		'auth.basic' => 'Guildle\Http\Filters\BasicAuthFilter',
		'csrf' => 'Guildle\Http\Filters\CsrfFilter',
		'guest' => 'Guildle\Http\Filters\GuestFilter',
	];

}
