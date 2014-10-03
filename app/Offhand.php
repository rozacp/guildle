<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Offhand extends Model {

	protected $fillable =
	[
		'character_id',
		'item_id',
		'item_name',
		'item_icon',
		'forg',
		'upgd',
		'sock',
		'ench',
		'pcs',
		'rand',
		'gems'
	];

	protected $table = 'offhands';

	public function character()
	{
		return $this->belongsTo('Guildle\Character');
	}


}
