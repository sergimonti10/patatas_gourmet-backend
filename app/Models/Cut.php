<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cut extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'weight',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
