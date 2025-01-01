<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = [
        'name', 'description', 'price', 'stock', 'category_id'
    ];

    protected $dates = ['deleted_at']; 

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
// في موديول Product
public function averageRating()
{
    return $this->reviews()->avg('rating');
}

 
}
