<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeAudiblesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'audibles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('audio_id')->unsigned();
			$table->morphs('audible');
			$table->string('audible_position',50);
			$table->smallInteger('audible_order');
			$table->timestamps();

			$table->index('audible_id');
			$table->index('audible_type');
			$table->index('audible_position');
			$table->index('audible_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'audibles');
	}

}
