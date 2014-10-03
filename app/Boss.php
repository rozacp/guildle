<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Boss extends Model {

	protected $fillable =
	[
		'raids_id',
		'boss'
	];

	protected $table = 'bosses';

	public function raid()
	{
		return $this->belongsTo('Guildle\Raid');
	}
}
