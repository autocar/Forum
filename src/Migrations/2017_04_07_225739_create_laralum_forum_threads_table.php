<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaralumForumThreadsTable extends Migration {

	public function up()
	{
		Schema::create('laralum_forum_threads', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('category_id');
			$table->string('title');
			$table->string('description');
			$table->text('content');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('laralum_forum_threads');
	}
}
