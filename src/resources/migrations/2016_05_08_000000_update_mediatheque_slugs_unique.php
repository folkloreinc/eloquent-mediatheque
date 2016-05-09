<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediathequeSlugsUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tables = ['pictures', 'videos', 'texts', 'metadatas', 'documents'];
		foreach($tables as $table)
		{
			$tableName = config('mediatheque.table_prefix').$table;
			Schema::table($tableName, function(Blueprint $table) use ($tableName)
			{
				//Drop unique
				$uniqueName = $tableName.'_slug_unique';
				$uniqueExists = DB::select(
					DB::raw('SHOW KEYS FROM '.$tableName.' WHERE Key_name="'.$uniqueName.'"')
				);
				if($uniqueExists)
				{
				    $table->dropUnique($uniqueName);
				}
				
				//Add index
				$indexName = $tableName.'_slug_index';
				$indexExists = DB::select(
					DB::raw('SHOW KEYS FROM '.$tableName.' WHERE Key_name="'.$indexName.'"')
				);
				if(!$indexExists)
				{
				    $table->index('slug');
				}
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$tables = ['pictures', 'videos', 'texts', 'metadatas', 'documents'];
		foreach($tables as $table)
		{
			$tableName = config('mediatheque.table_prefix').$table;
			Schema::table($tableName, function(Blueprint $table) use ($tableName)
			{
				//Add unique
				$uniqueName = $tableName.'_slug_unique';
				$uniqueExists = DB::select(
					DB::raw('SHOW KEYS FROM '.$tableName.' WHERE Key_name="'.$uniqueName.'"')
				);
				if(!$uniqueExists)
				{
				    $table->unique('slug');
				}
				
				//Drop index
				$indexName = $tableName.'_slug_index';
				$indexExists = DB::select(
					DB::raw('SHOW KEYS FROM '.$tableName.' WHERE Key_name="'.$indexName.'"')
				);
				if($indexExists)
				{
				    $table->dropIndex($indexName);
				}
			});
		}
	}

}
