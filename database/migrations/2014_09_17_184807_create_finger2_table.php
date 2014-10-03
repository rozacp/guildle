<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinger2Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finger2', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('character_id')->unsigned();
			$table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade')->onUpdate('cascade');
			$table->integer('item_id');
			$table->string('item_name');
			$table->string('item_icon');
			$table->integer('forg')->nullable();
			$table->integer('upgd')->nullable();
			$table->boolean('sock');
			$table->integer('ench')->nullable();
			$table->string('pcs')->nullable();
			$table->integer('rand')->nullable();
			$table->string('gems')->nullable();
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
		Schema::drop('finger2');
	}

}
