<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequePicturablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'picturables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('picture_id')->unsigned();
			$table->morphs('picturable');
			$table->string('picturable_position',50);
			$table->integer('picturable_order')->unsigned();
			$table->timestamps();

			$table->index('picture_id');
			$table->index('picturable_id');
			$table->index('picturable_type');
			$table->index('picturable_position');
			$table->index('picturable_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'picturables');
	}

}
