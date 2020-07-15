<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $fillable = ['text'];

    //se ejecuta al momento de crear una instancia y auth coge el id
    public static function boot() {
        parent::boot();

        static::creating(function ($comment) {
            $comment->user_id = Auth::id();
        });
    }

    //Relacion one to many
    public function user() {
        return $this->belongsTo('App\User');
    }
    //Relacion one to many
    public function article() {
        return $this->belongsTo('App\Article');
    }
}
