<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'user_id',
        'total_price',
        'sale_date',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    
public function products()
{
    return $this->belongsToMany(\App\Models\Product::class, 'sale_products')
        ->withPivot('quantity')
        ->withTimestamps();
}
}
