<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = true;

    protected $fillable = ['topic_id', 'title', 'description', 'status'];

    use HasFactory;

    public function topic() {

        return $this->belongsTo(Topic::class, 'topic_id', 'id');

    }

    public function tags() {

        return $this->hasMany(ArticleTag::class, 'article_id', 'id');

    }
}
