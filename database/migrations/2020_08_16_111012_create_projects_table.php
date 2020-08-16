<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('label');
            $table->string('git_repo_ssh_url');
            $table->string('branch_to_pull')->default('master');
            $table->string('server_user')->default('root');
            $table->string('server_address');
            $table->string('server_directory');
            $table->mediumText('before_commands')->nullable();
            $table->mediumText('after_commands')->nullable();
            $table->timestamps();
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
    }
}
