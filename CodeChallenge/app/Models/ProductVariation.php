<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    public $table = 'product_variations';
    protected $fillable=['product_id','variation_id','quantity','isavailable','addtion_price'];

}
