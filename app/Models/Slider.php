<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;


    protected $fillable = ['photo', 'created_at', 'updated_at'];


    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/images/sliders/' . $val) : "";
    }

}
