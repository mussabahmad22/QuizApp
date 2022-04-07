<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoury extends Model

{
    use HasFactory;
    protected $primarykey ="id";
    protected $fillable = ['name'];   

    public function getSubcat(){
        return $this->hasMany('App\Models\SubCategoury', 'subcat_id','id');
    }

}
