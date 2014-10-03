<?php namespace Guildle;

use Illuminate\Auth\UserTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Contracts\Auth\User as UserContract;
use Illuminate\Contracts\Auth\Remindable as RemindableContract;

class User extends Model implements UserContract, RemindableContract {

	use UserTrait, RemindableTrait;

	protected $fillable  =
	[
		'access_token',
		'expires_in',
		'accountId',
		'battletag',
		'name',
		'age',
		'gender',
		'country',
		'system_specs',
		'connection',
		'fps',
		'youtube',
		'twitch',
		'email'
	];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


	public function characters()
	{
		return $this->hasMany('Guildle\Character');
	}

	public function checkAccessToken()
	{
		// implement
	}

}
