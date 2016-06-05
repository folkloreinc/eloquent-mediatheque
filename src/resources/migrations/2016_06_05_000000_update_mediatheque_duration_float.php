<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeDurationFloat extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(config('mediatheque.table_prefix').'videos', function(Blueprint $table)
		{
			$table->float('duration')->change();
		});
		
		Schema::table(config('mediatheque.table_prefix').'audios', function(Blueprint $table)
		{
			$table->float('duration')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(config('mediatheque.table_prefix').'videos', function(Blueprint $table)
		{
			$table->integer('duration')->change();
		});
		
		Schema::table(config('mediatheque.table_prefix').'audios', function(Blueprint $table)
		{
			$table->integer('duration')->change();
		});
	}

}
