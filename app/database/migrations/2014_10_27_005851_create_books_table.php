<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('books', function($table) {
	
		# Increments method will make a Primary, Auto-Incrementing field.
		# Most tables start off this way
		$table->increments('id');
	
		# This generates two columns: `created_at` and `updated_at` to
		# keep track of changes to a row
		$table->timestamps();
	
		# The rest of the fields...
		$table->string('title',255);
		$table->string('author',255);
		$table->integer('published');
		$table->string('cover',255);
		$table->string('purchase_link',255);
	
		# FYI: We're skipping the 'tags' field for now; more on that later.
	
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('books');
	}

}
