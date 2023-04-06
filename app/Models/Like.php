<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'visitor',
        'articles_id',
        'status',
    ];

    public function article()
    {
        return $this->belongsToMany(Article::class);
    }
}
