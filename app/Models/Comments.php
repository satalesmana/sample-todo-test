<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table 		= "comments";
    protected $fillable 	= ['body','commentable_id','commentable_type'];
    

    public function commentable()
    {
        return $this->morphTo();
    }
}
