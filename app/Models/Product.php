<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{

    use HasFactory;
    protected $fillable = [
        'title',
        'price',
        'description',
        'quantity',
        'image',
    ];

    public function orders(){
        return $this->belongsToMany('App\Models\Order')->withPivot('quantity')->withTimestamps();
    }

}
