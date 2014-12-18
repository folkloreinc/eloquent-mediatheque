<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequePicturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'pictures', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug');
			$table->string('filename');
			$table->string('original');
			$table->string('mime',50);
			$table->integer('size');
			$table->smallInteger('width')->unsigned();
			$table->smallInteger('height')->unsigned();
			$table->timestamps();
			
			$table->index('filename');
			$table->index('original');
			$table->unique('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'pictures');
	}

}
