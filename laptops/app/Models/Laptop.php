<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'model', 
        'manufacturer',
        'procesor',
        'memmory',
        'drive',
        'grafic',
        'price',
    ];

    public function getId()
    {
        return $this->id;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }
}
