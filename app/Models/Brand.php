<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    use Translatable;


    protected $with = ['translations'];

    protected $fillable = ['is_active','photo'];

    protected $cast =[
        'is_active' => 'boolean',
    ];

    protected $translatedAttributes = ['name'];


    public function scopeActive($query){
        return $query -> where('is_active',1);
    }


    public function getActive(){
        return  $this -> is_active  == 0 ?  'غير مفعل'   : 'مفعل' ;
    }


    public function  getPhotoAttribute($val){
        return ($val !== null) ? asset('assets/images/brands/' . $val) : "";
    }

}
