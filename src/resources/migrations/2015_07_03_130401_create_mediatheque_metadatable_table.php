<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeMetadatableTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('mediatheque.table_prefix').'metadatable', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('metadata_id')->unsigned();
			$table->morphs('metadatable');
			$table->string('metadatable_position',50);
			$table->integer('metadatable_order')->unsigned();
			$table->timestamps();

			$table->index('metadata_id');
			$table->index('metadatable_id');
			$table->index('metadatable_type');
			$table->index('metadatable_position');
			$table->index('metadatable_order');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(config('mediatheque.table_prefix').'metadatable');
	}

}
