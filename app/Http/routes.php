<?php

use \Illuminate\Database\Schema\Blueprint;
use \App\Book;
use \App\Publisher;
use \App\Author;

Route::get('/', function () {
    return view('welcome');
});

/**
 *  creating schema's
 */
Route::get('create_book_table', function() {

    $tableToCreate='books';
    $tableToCreate01='authors';

    // create the schema table
    // - first drop it if it exists.
    if (Schema::hasTable($tableToCreate01))
    {
        schema::drop($tableToCreate01);
    }

    // create the schema table
    // - first drop it if it exists.
    if (Schema::hasTable($tableToCreate))
    {
        schema::drop($tableToCreate);
    }

    Schema::create($tableToCreate, function(Blueprint $table)
    {
        $table->increments('id');
        $table->string('title', 30);
        $table->integer('page_count');
        $table->decimal('price', 5, 2);
        $table->text('description');
        $table->timestamps();
    });

    return "<h1>table '$tableToCreate' has been created or rebuilt</h1>";
});

Route::get('delete_book_table', function() {

    $tableToCreate='books';

    // create the schema table
    // - first drop it if it exists.
    if (Schema::hasTable($tableToCreate))
    {
        schema::drop($tableToCreate);
    }


    return "<h1>table '$tableToCreate' has been deleted</h1>";
});

Route::get('create_book_table_2', function() {

    $tableToCreate='authors';

    $exception = DB::transaction(function() use ($tableToCreate) {
        try {
            // create the schema table
            // - first drop it if it exists.
            if (Schema::hasTable($tableToCreate))
            {
                schema::drop($tableToCreate);
            }

            Schema::create($tableToCreate, function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('first_name');
                $table->string('last_name');
                $table->timestamps();
            });

            $tableToCreate01='books';

            Schema::table($tableToCreate01, function(Blueprint $table) use($tableToCreate)
            {
                $table->index('title');
                $table->integer('author_id')->unsigned();
                $table->foreign('author_id')->references('id')->on($tableToCreate);
            });
        } catch (Exception $e) {
            echo "<h1>table '$tableToCreate' failed creating.</h1>";
            echo $e;
        }
    });

    if (is_null($exception)) {
        echo "<h1>table '$tableToCreate' has been created or rebuilt</h1>";
    }
});


/**
 * creating records
 */
Route::get('publisher_create', function() {

    $publisher = new \App\Publisher;

    $publisher->name = 'Random House';

    $publisher->save();

    echo 'publisher: ' . $publisher->name . " with key of " . $publisher->id . ' created';
});

Route::get('author_create', function() {
    $author = new \App\Author;

    $author->first_name = 'John';
    $author->last_name = 'Steinbeck';

    $author->save();

    echo 'author: ' . $author->first_name . " " . $author->last_name . " with key of " . $author->id . ' created';
});

Route::get('book_create', function() {
    $book = new \App\Book;

    $book->title = 'My First Book!';
    $book->page_count = 230;
    $book->price = 10.50;
    $book->description = 'A very original lorem ipsum dolor sit amet...';
    $book->author_id = 1;
    $book->publisher_id = 1;

    $book->save();

    echo 'book: ' . $book->id . 'created';
});

Route::get('book_create_2', function() {
    $book = new Book;

    $book->title = 'My Second Book!';
    $book->page_count = 122;
    $book->price = 9.50;
    $book->description = 'Another very original lorem ipsum dolor sit amet...';
    $book->author_id = 1;
    $book->publisher_id = 1;

    $book->save();

    echo 'book: ' . $book->id . 'created';
});

Route::get('book_create_3', function() {

    $author = Author::where('first_name', '=', 'John')->where('last_name', '=', 'Steinbeck')->first();
    $publisher = Publisher::where('name', '=', 'Random House')->first();

    $book = new Book;

    $book->title = 'My third Book!';
    $book->page_count = 150;
    $book->price = 19.99;
    $book->description = 'Third very original lorem ipsum dolor sit amet...';
    $book->author_id = $author->id;
    $book->publisher_id = $publisher->id;

    $book->save();

    // $book->id maybe empty as the BookObserver creating method may have prevent the save().
    if (!empty($book->id)) {
        echo 'book: ' . $book->id . ' created';
    }

});


/**
 * reading records
 */

Route::get('book_get_all', function() {
    return dump(\App\Book::all());
});

Route::get('book_get_id/{id}', function($id) {
    return dump(\App\Book::find($id));
});

Route::get('book_get_where_first', function() {
    $result = Book::where('page_count', '<', 1000)->first();
    return dump($result);
});

Route::get('book_get_where_all', function() {
    $result = Book::where('page_count', '<', 1000)->get();
    return dump($result);
});

Route::get('book_get_where_specific', function() {
    $result = Book::where('page_count', '<', 1000)
        ->where('title', '=', 'My First Book!')->get();
    return dump($result);
});

Route::get('book_get_where_iterate', function() {

    $results = Book::where('page_count', '<', 100000)->get();

    if (count($results) > 0)
    {
        foreach($results as $book)
        {
            echo 'book: ' . '- id: - ' . $book->id . '- title - ' . $book->title . '- Pages: ' . $book->page_count . '</br>';
            echo '  - ' . 'author is: ' . $book->author->author_name . '</br>';
        }
    }
    else
    {
        echo 'No Results found';
    }
});

/**
 * update records
 */
Route::get('book_update_id/{id}/title/{newTitle}/pageCount/{pageCount}', function($id, $title, $pageCount) {

    $book = Book::find($id);

    if (count($book) > 0)
    {
        $book->title = $title;
        $book->page_count = $pageCount;

        $book->save();

        echo 'book saved';
    }
    else
    {
        echo 'Book not found';
    }
});

/**
 * delete records
 */
Route::get('book_delete_id/{id}', function($id) {

    $book = Book::find($id);

    if (count($book) > 0)
    {
        $book = Book::find($id)->delete();

        echo 'book deleted';
    }
    else
    {
        echo 'Book not found';
    }
});




