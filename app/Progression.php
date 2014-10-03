<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Progression extends Model {

	protected $fillable =
	[
		'character_id',
		'raids_id',
		'bosses_id',
		'lrf',
		'normal',
		'heroic'
	];

	protected $table = 'progressions';

	public function character()
	{
		return $this->belongsTo('Guildle\Character');
	}
}
