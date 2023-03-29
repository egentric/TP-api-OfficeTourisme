<?php

namespace App\Models;

use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Site extends Model
{
    use HasFactory;
    protected $fillable = ['nameSite', 'descriptionSite', 'emailSite', 'websiteSite', 'phoneSite', 'addressSite', 'zipSite', 'citySite', 'longitudeDegSite', 'latitudeDegSite', 'pictureSite', 'user_id', 'type_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function event()
    {
        //Relation many to many avec évenement
        return $this->belongsToMany('App\Models\Event');
    }
}
