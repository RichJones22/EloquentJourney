<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    /**
     *  table used by this model
     */
    protected $table = 'publishers';

    /**
     * fillable fields
     */
    protected $fillable = ['name'];

    /**
     * relations; one-to-one
     */
    public function book()
    {
        return $this->hasOne('\App\Book');
    }
}
