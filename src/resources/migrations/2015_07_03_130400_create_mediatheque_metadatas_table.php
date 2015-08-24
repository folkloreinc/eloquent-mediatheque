<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeMetadatasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('mediatheque.table_prefix').'metadatas', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug');
			$table->string('type');
			$table->string('name');
			$table->string('value');
			$table->longText('value_text');
			$table->date('value_date');
			$table->time('value_time');
			$table->datetime('value_datetime');
			$table->timestamps();
			
			$table->index('type');
			$table->index('name');
			$table->unique('slug');
			$table->index('value');
			$table->index('value_date');
			$table->index('value_time');
			$table->index('value_datetime');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(config('mediatheque.table_prefix').'metadatas');
	}

}
