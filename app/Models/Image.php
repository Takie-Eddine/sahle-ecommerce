<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'photo', 'created_at', 'updated_at'];

    //

    public function getPhotoAttribute($val)
    {

        return $val ? asset('assets/images/products/'.$val) : '';
    }

}
