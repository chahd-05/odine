<?php

namespace App\Models;

use App\Models\Link;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function links(){
        return $this->hasMany(Link::class);
    }
}
