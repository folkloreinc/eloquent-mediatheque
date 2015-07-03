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
			$table->longText('value');
			$table->timestamps();
			
			$table->index('type');
			$table->index('name');
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
		Schema::drop(config('mediatheque.table_prefix').'metadatas');
	}

}
