<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'weight',
        'image',
        'image2',
        'id_cut',
    ];

    public function cut()
    {
        return $this->belongsTo(Cut::class, 'id_cut');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products', 'id_product', 'id_order')
            ->withPivot(['quantity', 'unit_price']);
    }


    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_product');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'product_user')->withPivot('quantity', 'unit_price');
    // }
}
