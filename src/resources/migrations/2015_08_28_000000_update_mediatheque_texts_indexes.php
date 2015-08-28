<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediathequeTextsIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(config('mediatheque.table_prefix').'texts', function(Blueprint $table)
		{
			$table->index('content');
			$table->index('fields');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(config('mediatheque.table_prefix').'texts', function(Blueprint $table)
		{
			$name = config('mediatheque.table_prefix').'texts';
			$table->dropIndexes($name.'_content_index');
			$table->index($name.'_fields_index');
		});
	}

}
