<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediathequeTextsTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('mediatheque.table_prefix').'texts_translations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('text_id')->unsigned();
			$table->string('locale',5);
			$table->text('content');
			$table->timestamps();
			
			$table->index('text_id');
			$table->index('locale');
            
            $table->unique(['text_id','locale']);
            $table->foreign('text_id')
                    ->references('id')
                    ->on(config('mediatheque.table_prefix').'texts')
                    ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(config('mediatheque.table_prefix').'texts_translations');
	}

}
