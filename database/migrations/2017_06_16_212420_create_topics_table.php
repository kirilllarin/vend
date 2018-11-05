<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->boolean('is_public')->default(0);
            $table->boolean('is_archived')->default(0);
            $table->integer('last_message')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('message_count')->unsigned()->default(0);
            $table->integer('file_count')->unsigned()->default(0);
            $table->integer('event_count')->unsigned()->default(0);
            $table->integer('account_id')->index()->unsigned();
            $table->timestamps();
        });

        Schema::create('topic_user', function (Blueprint $table) {
            $table->integer('topic_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('role')->unsigned()->default(0);
            $table->tinyInteger('notification_level')->unsigned()->default(0);
            $table->primary(['topic_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topics');
        Schema::dropIfExists('topic_user');
    }
}
