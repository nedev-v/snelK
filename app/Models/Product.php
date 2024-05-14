<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable = ["price"];
    public $timestamps = false;

    public function translations(){
        return $this->hasMany(ProductTranslation::class);
    }
}
