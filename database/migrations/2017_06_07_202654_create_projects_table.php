<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('is_archive')->index()->default(false);
            $table->integer('account_id')->index()->unsigned();
            $table->timestamps();
        });

        Schema::create('project_user', function (Blueprint $table) {
            $table->integer('project_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->primary(['project_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_user');
    }
}
