<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['titleEvent', 'subtitleEvent', 'contentEvent', 'pictureEvent'];

    public function site()
    {
        //Relation many to many avec site
        return $this->belongsToMany('App\Models\Site');
    }
}
