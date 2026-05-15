<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    // app/Models/Listing.php
   protected $fillable = [
    'user_id', 
    'category_id', 
    'title', 
    'description', 
    'price', 
    'location', 
    'type', // <-- Doit être ici
    'cover_image'
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function category()
{
    return $this->belongsTo(Category::class);
}

public function messages()
{
    return $this->hasMany(Message::class);
}
}
