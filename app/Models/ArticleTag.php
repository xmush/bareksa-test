<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    public $timestamps = true;

    protected $fillable = ['article_id', 'tag_id'];

    use HasFactory;
}
