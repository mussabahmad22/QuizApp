<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained('exercises')->nullable()->default(NULL);
            $table->longText('question_title');
            $table->string('path_title')->default("");
            $table->longText('option_1');
            $table->longText('statement_1');
            $table->longText('option_2');
            $table->longText('statement_2');
            $table->longText('option_3');
            $table->longText('statement_3');
            $table->longText('option_4');
            $table->longText('statement_4');
            $table->string('right_ans');
            $table->longText('right_ans_statement');
            $table->longText('question_review');
            $table->string('path_review')->default("");
            $table->string('yt_link');
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
        Schema::dropIfExists('questions');
    }
}
