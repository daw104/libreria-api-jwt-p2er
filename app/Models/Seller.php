<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    //atributos
    protected $fillable = [
        'dni',
        'account_number',
        'user_id'
    ];

    //funcion para los usarios
    public function user(){
        return $this->belongsTo('App\Models\User');
    }





}
