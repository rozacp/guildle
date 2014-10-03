<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('character_id')->unsigned();
			$table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade')->onUpdate('cascade');
			$table->integer('issues');
			$table->integer('empty_glyphs');
			$table->integer('unspent_talents');
			$table->integer('unenchanted_items');
			$table->integer('empty_sockets');
			$table->integer('inappropriate_armor');
			$table->integer('no_extra_sockets');
			$table->integer('no_blacksmith_sockets');
			$table->integer('no_enchanter_enchants');
			$table->integer('no_engineer_enchants');
			$table->integer('no_scribe_enchants');
			$table->integer('no_jewelcrafter_gems');
			$table->integer('no_leatherworker_enchants');
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
		Schema::drop('audits');
	}

}
