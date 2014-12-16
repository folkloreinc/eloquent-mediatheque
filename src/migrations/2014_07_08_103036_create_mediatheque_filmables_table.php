<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeFilmablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'filmables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('video_id')->unsigned();
			$table->morphs('filmable');
			$table->string('filmable_position',50);
			$table->integer('filmable_order')->unsigned();
			$table->timestamps();

			$table->index('video_id');
			$table->index('filmable_id');
			$table->index('filmable_type');
			$table->index('filmable_position');
			$table->index('filmable_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'filmables');
	}

}
