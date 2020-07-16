<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Article extends Model
{
    protected $fillable=['title','body','category_id'];

    //se ejecuta al momento de crear una instancia y auth coge el id
    public static function boot() {
        parent::boot();

        static::creating(function ($article) {
            $article->user_id = Auth::id();
        });
    }

    //Relacion one to many
    public function comments() {
        return $this->hasMany('App\Comment');
    }
    //Relacion inversa one to many
    public function user() {
        return $this->belongsTo('App\User');
    }

}
