<?php

namespace App\Models;

use App\Models\Link;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function link(){
        return $this->belongsToMany(Link::class);
    }
}


