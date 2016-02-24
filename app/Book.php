<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'books';

    /**
     * fillable fields
     */
    protected $fillable = ['title', 'page_count', 'price', 'description'];

    /**
     * publisher; one-to-one.
     */
    public function publisher()
    {
        return $this->belongsTo('\App\Publisher');
    }

    /**
     * Authors; one-to-one.
     */
    public function author()
    {
        return $this->belongsTo('\App\Author');
    }
}
