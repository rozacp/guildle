<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('progressions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('character_id')->unsigned();
			$table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade')->onUpdate('cascade');
			$table->integer('raids_id')->unsigned();
			$table->foreign('raids_id')->references('id')->on('raids');
			$table->integer('bosses_id')->unsigned();
			$table->foreign('bosses_id')->references('id')->on('bosses');
			$table->integer('lrf');
			$table->integer('normal');
			$table->integer('heroic');
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
		Schema::drop('progressions');
	}

}
