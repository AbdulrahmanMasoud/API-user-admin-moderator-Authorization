<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    use HasFactory;
    public function users(){
        return $this->belongsTo(User::class);
    }
}
