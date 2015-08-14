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
			$table->increments('userId');
			$table->string('firstName', 100)->nullable();
			$table->string('lastName', 100)->nullable();
			$table->string('contactNo', 15)->nullable();
			$table->timestamp('dob')->nullable();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->string('avatarUrl', 100);
			$table->boolean('isActive')->default(1);
			$table->integer('roleId')->unsigned()->index();
			$table->integer('collegeId')->unsigned();
			$table->integer('deptId')->unsigned();
			$table->foreign('roleId')
				->references('roleId')
				->on('roles');
			$table->foreign('deptId')->references('deptId')->on('departments');
			$table->foreign('collegeId')->references('collegeId')->on('colleges');
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
