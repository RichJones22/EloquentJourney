<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TagsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * create tags table.
         */
        Schema::create('tags', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        /**
         * create book_tag pivot table
         */
        Schema::create('book_tag', function(Blueprint $table) {
            $table->increments('id');

            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')->references('id')->on('books');

            $table->integer('tag_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('book_tag');
        Schema::drop('tags');
    }
}
