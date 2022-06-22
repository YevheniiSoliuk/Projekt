<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'type',
        'name', 
        'color_text',
        'color',
        'price',
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
