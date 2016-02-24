<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'authors';

    /**
     * fillable fields
     */
    protected $fillable = ['first_name'
                          ,'last_name'];

    protected $appends = ['author_name'];

    /**
     * relations; one-to-one
     */
    public function book()
    {
        return $this->hasOne('\App\Book');
    }

    /**
     * concat first and last name.
     * @return string
     *
     * function, authorName, superseded by getAuthorNameAttribute(), which uses the Imaginary Attribute 'author_name'
     * as defined in $appends above.
     *
     */
    public function authorName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAuthorNameAttribute()
    {
        return $this-> attributes['first_name']. ' ' . $this-> attributes['last_name'];

   }
}
