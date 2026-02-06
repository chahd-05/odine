<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['title', 'url', 'category_id', 'user_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function Tag(){
        return $this->belongsToMany(Tag::class);
    }
}

