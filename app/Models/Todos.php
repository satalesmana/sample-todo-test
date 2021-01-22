<?php

namespace App\Models;
use App\Models\Comments;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todos extends Model
{
    protected $table 		= "todos";
    protected $fillable 	= ['name','is_done'];

    public function comments()
    {
        return $this->morphMany(Comments::class, 'commentable');
    }
}
