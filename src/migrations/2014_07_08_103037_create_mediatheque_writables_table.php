<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeWritablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'writables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('text_id')->unsigned();
			$table->morphs('writable');
			$table->string('writable_position',50);
			$table->integer('writable_order')->unsigned();
			$table->timestamps();

			$table->index('text_id');
			$table->index('writable_id');
			$table->index('writable_type');
			$table->index('writable_position');
			$table->index('writable_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'writables');
	}

}
