<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model {

	protected $fillable =
	[
		'character_id',
		'issues',
		'empty_glyphs',
		'unspent_talents',
		'unenchanted_items',
		'empty_sockets',
		'inappropriate_armor',
		'no_extra_sockets',
		'no_blacksmith_sockets',
		'no_enchanter_enchants',
		'no_engineer_enchants',
		'no_scribe_enchants',
		'no_jewelcrafter_gems',
		'no_leatherworker_enchants'
	];

	protected $table = 'audits';

	public function character()
	{
		return $this->belongsTo('Guildle\Character');
	}
}
