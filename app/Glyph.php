<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Glyph extends Model {

	protected $fillable =
	[
		'character_id',
		'spec',
		'type',
		'glyph_id',
		'glyph_name',
		'glyph_icon'
	];

	protected $table = 'glyphs';

	public function character()
	{
		return $this->belongsTo('Guildle\Character');
	}

}
