<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeTextsLocalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(Config::get('eloquent-mediatheque::table_prefix').'texts_locales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('text_id')->unsigned();
			$table->string('locale',5);
			$table->text('content');
			$table->string('fields');
			$table->boolean('is_json');
			$table->timestamps();
			
			$table->index('text_id');
			$table->index('locale');
			$table->index('text');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('eloquent-mediatheque::table_prefix').'texts_locales');
	}

}
