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
			$table->string('guild')->nullable();
			$table->string('guildRealm')->nullable();
			// $table->boolean('guildmaster')->default(FALSE);
			$table->string('faction', 10)->nullable();
			$table->string('char_race', 15);
			$table->string('char_class', 15);
			$table->string('specfirst', 15)->nullable();
			$table->string('specsecond', 15)->nullable();
			$table->string('specactive', 15)->nullable();
			$table->string('profnamefirst')->nullable();
			$table->integer('profrankfirst')->nullable();
			$table->string('profnamesecond')->nullable();
			$table->integer('profranksecond')->nullable();
			$table->integer('ilevel');
			$table->integer('health');
			$table->integer('str');
			$table->integer('agi');
			$table->integer('int');
			$table->integer('spr');
			$table->integer('spellPower');
			$table->integer('attackPower');
			$table->float('crit');
			$table->float('haste');
			$table->float('mastery');
			$table->integer('bonusArmor');
			$table->float('multistrike');
			$table->float('leech');
			$table->float('versatility');
			$table->float('avoidance');
			$table->integer('armor');
			$table->float('dodge');
			$table->float('parry');
			$table->float('block');
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
