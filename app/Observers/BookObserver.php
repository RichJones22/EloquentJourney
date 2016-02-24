<?php
namespace App\Observers;

use \App\Book;
use \App\User;

use \Illuminate\Support\Facades\Mail;

/**
 * Created by PhpStorm.
 * User: rich jones
 * Date: 11/1/15
 * Time: 6:56 AM
 */
class BookObserver
{

    private $cantMessage;

    public function creating($book) {
        if ($book->title == 'My third Book!') {
            $cantMessage = "you can't create  " . $book->title . PHP_EOL;
            echo $cantMessage;
        }

        // now send an email letting the user know
        Mail::raw($cantMessage, function($message)
        {
            $user = User::firstOrNew(array());

            $message->subject('book creation issue');
            $message->from($user->email, 'Laravel');
            $message->to($user->email);
        });

        // returning false here prevents the book save() operation from happening.
        return false;
    }

    public function created($book) {
        echo "" . PHP_EOL;
        echo $book->title . " was created." . PHP_EOL;
        echo "" . PHP_EOL;
    }

}