<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    public $table = 'article';
    protected $appends = array('short_description');
    
    protected function getShortDescriptionAttribute(){
        return  preg_replace('/\s+?(\S+)?$/', '', substr($this->details, 0, 101));;
    }
    public function comment(){
        return $this->hasMany(Comment::class,"article_id","id")->orderBy('id', 'DESC');
    }

    public function tag(){
        return $this->hasMany(Tag::class,"article_id","id")->orderBy('id', 'DESC');
    }
}
