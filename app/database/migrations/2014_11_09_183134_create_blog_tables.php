<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('authors', function($table)
		{
		    $table->increments('id');
		    $table->string('name', 64);
		    $table->timestamps();
		});
		
		// Topics table implements one-to-many relationship with author
		Schema::create('topics', function($table)
		{
		    $table->increments('id');
		    $table->string('topic_name', 64);
		    $table->string('topic_content', 128);
		    $table->integer('author_id')->unsigned();
		    $table->foreign('author_id')->references('id')->on('authors');
		    $table->timestamps();
		});

		// Replies table implements one-to-many relationship with author		
	        Schema::create('replies', function($table)
		{
		    $table->increments('id');
		    $table->string('content', 128);
		    $table->integer('author_id')->unsigned();
		    $table->foreign('author_id')->references('id')->on('authors');
		    $table->timestamps();
		});	
		
		// Comments table implements one-to-many relationship with author		
	        Schema::create('comments', function($table)
		{
		    $table->increments('id');
		    $table->string('content', 128);
		    $table->integer('author_id')->unsigned();
		    $table->foreign('author_id')->references('id')->on('authors');
		    $table->timestamps();
		});
		
		// Pivot table.  Associate topic and replies
		Schema::create('reply_topic', function($table)
		{
		    $table->integer('topic_id')->unsigned();
		    $table->foreign('topic_id')->references('id')->on('topics');
		    $table->integer('reply_id')->unsigned();
		    $table->foreign('reply_id')->references('id')->on('replies');
		});

		// Pivot table.  Associate replies and comments		
		Schema::create('comment_reply', function($table)
		{
		    $table->integer('reply_id')->unsigned();
		    $table->foreign('reply_id')->references('id')->on('replies');
		    $table->integer('comment_id')->unsigned();
		    $table->foreign('comment_id')->references('id')->on('comments');
		});        
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('reply_topic');
		Schema::drop('comment_reply');
		Schema::drop('topics');
		Schema::drop('replies');
		Schema::drop('comments');
		Schema::drop('authors');
	}

}
