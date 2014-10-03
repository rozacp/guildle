<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model {

	protected $fillable =
	[
		'character_id',
		'spec',
		'tier',
		'talent_id',
		'talent_name',
		'talent_icon'
	];

	protected $table = 'talents';

	public function character()
	{
		return $this->belongsTo('Guildle\Character');
	}

}
