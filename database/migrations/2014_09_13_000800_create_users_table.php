<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('access_token');
            $table->integer('expires_in');
			$table->integer('accountId')->unique();
			$table->string('battletag', 20)->unique();
			$table->string('name', 60);
			$table->integer('age');
			$table->string('gender', 6);
			$table->string('country', 40);
			$table->text('system_specs');
			$table->string('connection');
			$table->integer('fps');
			$table->string('youtube', 60);
			$table->string('twitch', 60);
			$table->string('email');
			$table->boolean('isadmin')->default(FALSE);
            $table->rememberToken();
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
		Schema::drop('users');
	}

}
