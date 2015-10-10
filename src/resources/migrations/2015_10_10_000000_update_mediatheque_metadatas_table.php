<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediathequeMetadatasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(config('mediatheque.table_prefix').'metadatas', function(Blueprint $table)
		{
			$table->boolean('value_boolean')->after('value_datetime');
			$table->integer('value_integer')->after('value_boolean');
			$table->decimal('value_float', 11, 8)->after('value_integer');
			
			$table->index('value_boolean');
			$table->index('value_integer');
			$table->index('value_float');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table(config('mediatheque.table_prefix').'metadatas', function(Blueprint $table)
		{
			$table->dropColumn('value_boolean');
			$table->dropColumn('value_integer');
			$table->dropColumn('value_float');
		});
	}

}
