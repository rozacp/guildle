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
			$table->string('name', 60)->nullable();
			$table->integer('age')->nullable();
			$table->string('gender', 6)->nullable();
			$table->string('country', 40)->nullable();
			$table->text('system_specs')->nullable();
			$table->string('connection')->nullable();
			$table->integer('fps')->nullable();
			$table->string('youtube', 60)->nullable();
			$table->string('twitch', 60)->nullable();
			$table->string('email')->nullable();
			$table->boolean('premium')->default(FALSE);
			$table->boolean('admin')->default(FALSE);
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
