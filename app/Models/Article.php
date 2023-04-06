<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;

    public function likes()
    {
        return $this->hasMany(Like::class, 'articles_id', 'id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'articles_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'articles_id', 'id');
    }

    public function views()
    {
        return $this->hasMany(View::class, 'articles_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class, 'articles_id', 'id');
    }
}
