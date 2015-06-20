<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeAudiosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('mediatheque.table_prefix').'audios', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('slug');
			$table->string('name');
			$table->string('source');
			$table->string('url');
			$table->string('embed');
			$table->string('filename');
			$table->string('mime',50);
			$table->integer('size');
			$table->integer('duration')->unsigned();
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
		Schema::drop(config('mediatheque.table_prefix').'audios');
	}

}
