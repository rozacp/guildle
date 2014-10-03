<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('characters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
			$table->string('zone', 3);
			$table->string('realm', 100);
			$table->string('name', 20);
			$table->integer('level');
			$table->string('guild');
			$table->string('guildRealm');
			// $table->boolean('guildmaster')->default(FALSE);
			$table->string('faction', 10);
			$table->string('char_race', 15);
			$table->string('char_class', 15);
			$table->string('specfirst', 15);
			$table->string('specsecond', 15);
			$table->string('specactive', 15);
			$table->string('profnamefirst');
			$table->integer('profrankfirst');
			$table->string('profnamesecond');
			$table->integer('profranksecond');
			$table->integer('ilevel');
			$table->integer('health');
			$table->integer('str');
			$table->integer('agi');
			$table->integer('int');
			$table->integer('spr');
			$table->integer('attackPower');
			$table->integer('rangedAttackPower');
			$table->float('mastery');
			$table->float('crit');
			$table->float('hit');
			$table->float('haste');
			$table->integer('spellPower');
			$table->float('spellCrit');
			$table->float('spellHit');
			$table->float('spellHaste');
			$table->integer('armor');
			$table->float('dodge');
			$table->float('parry');
			$table->float('block');
			$table->float('expertise');
			$table->float('rangedExpertise');
			$table->float('rangedCrit');
			$table->float('rangedHit');
			$table->float('rangedHaste');
			$table->string('avatar');
			$table->string('avatarbig');
			$table->string('armory');
			$table->string('lastmodified');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('characters');
	}

}
