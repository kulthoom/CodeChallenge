<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
   use HasFactory, Notifiable,SoftDeletes;
    protected $fillable=['name','sku','status',/*'variations',*/ 'price', 'currency','quantity'];

    public function variantion()
    {
        return $this->belongsToMany(Variation::class,'product_variations');
    }
}
