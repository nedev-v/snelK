<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable = ["price", "has_milk", "image_path"];

    protected $casts = [
        'has_milk' => 'boolean',
    ];
    public $timestamps = false;

    public function translations(){
        return $this->hasMany(ProductTranslation::class);
    }
}
