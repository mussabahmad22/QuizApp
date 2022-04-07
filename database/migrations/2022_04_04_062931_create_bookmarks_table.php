<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->nullable()->default(NULL);
            $table->foreignId('cat_id')->constrained('categouries')->nullable()->default(NULL);
            $table->foreignId('exercise_id')->constrained('exercises')->nullable()->default(NULL);
            $table->foreignId('question_id')->constrained('questions')->nullable()->default(NULL);
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
        Schema::dropIfExists('bookmarks');
        
    }
}
