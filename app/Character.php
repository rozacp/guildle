<?php namespace Guildle;

use Illuminate\Database\Eloquent\Model;

class Character extends Model {

    protected $fillable =
    [
    	'user_id',
    	'zone',
    	'realm',
    	'name',
    	'level',
    	'guild',
    	'guildRealm',
    	'faction',
    	'char_race',
    	'char_class',
    	'specfirst',
    	'specsecond',
    	'specactive',
    	'profnamefirst',
    	'profrankfirst',
    	'profnamesecond',
    	'profranksecond',
    	'ilevel',
    	'health',
    	'str',
    	'agi',
    	'int',
    	'spr',
    	'spellPower',
    	'attackPower',
    	'crit',
    	'haste',
    	'mastery',
    	'bonusArmor',
    	'multistrike',
    	'leech',
    	'versatility',
    	'avoidance',
    	'armor',
    	'dodge',
    	'parry',
    	'block',
    	'avatar',
    	'avatarbig',
    	'armory',
    	'lastmodified'
    ];

	protected $table = 'characters';


	public function user()
	{
		return $this->belongsTo('Guildle\User');
	}

	public function glyphs()
	{
	    return $this->hasMany('Guildle\Glyph');
	}

	public function talents()
	{
	    return $this->hasMany('Guildle\Talent');
	}

	public function progressions()
	{
	    return $this->hasMany('Guildle\Progression');
	}

	public function audit()
	{
		return $this->hasOne('Guildle\Audit');
	}

	public function back()
	{
		return $this->hasOne('Guildle\Back');
	}

	public function chest()
	{
		return $this->hasOne('Guildle\Chest');
	}

	public function feet()
	{
		return $this->hasOne('Guildle\Feet');
	}

	public function finger1()
	{
		return $this->hasOne('Guildle\Finger1');
	}

	public function finger2()
	{
		return $this->hasOne('Guildle\Finger2');
	}

	public function hands()
	{
		return $this->hasOne('Guildle\Hands');
	}

	public function head()
	{
		return $this->hasOne('Guildle\Head');
	}

	public function legs()
	{
		return $this->hasOne('Guildle\Legs');
	}

	public function mainhand()
	{
		return $this->hasOne('Guildle\Mainhand');
	}

	public function neck()
	{
		return $this->hasOne('Guildle\Neck');
	}

	public function offhand()
	{
		return $this->hasOne('Guildle\Offhand');
	}

	public function shoulder()
	{
		return $this->hasOne('Guildle\Shoulder');
	}

	public function trinket1()
	{
		return $this->hasOne('Guildle\Trinket1');
	}

	public function trinket2()
	{
		return $this->hasOne('Guildle\Trinket2');
	}

	public function waist()
	{
		return $this->hasOne('Guildle\Waist');
	}

	public function wrist()
	{
		return $this->hasOne('Guildle\Wrist');
	}

	public function shirt()
	{
		return $this->hasOne('Guildle\Shirt');
	}

	public function tabard()
	{
		return $this->hasOne('Guildle\Tabard');
	}
}
