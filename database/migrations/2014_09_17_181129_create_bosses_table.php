<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBossesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bosses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('raids_id')->unsigned();
			$table->foreign('raids_id')->references('id')->on('raids');
			$table->string('boss');
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
		Schema::drop('bosses');
	}

}
