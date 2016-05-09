<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeDocuments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('mediatheque.table_prefix').'documents', function(Blueprint $table)
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
			$table->integer('pages')->unsigned();
			$table->timestamps();
			
			$table->index('filename');
			$table->index('name');
			$table->index('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(config('mediatheque.table_prefix').'documents');
	}

}
