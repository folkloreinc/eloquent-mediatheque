<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeDocumentables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('mediatheque.table_prefix').'documentables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('document_id')->unsigned();
			$table->morphs('documentable');
			$table->string('documentable_position',50);
			$table->integer('documentable_order')->unsigned();
			$table->timestamps();

			$table->index('document_id');
			$table->index('documentable_id');
			$table->index('documentable_type');
			$table->index('documentable_position');
			$table->index('documentable_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(config('mediatheque.table_prefix').'documentables');
	}

}
