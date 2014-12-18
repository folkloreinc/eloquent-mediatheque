<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeTextsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'texts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug');
			$table->text('content');
			$table->string('fields');
			$table->boolean('is_json');
			$table->timestamps();
			
			$table->unique('slug');
			$table->index('content');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'texts');
	}

}
