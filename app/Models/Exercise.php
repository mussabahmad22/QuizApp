<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    public function category(){
        return $this->hasMany('App\Models\Categoury','id','categoury_id');
    }
}
