<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    use HasFactory;

    protected $table = "products_language";

    protected $fillable = [
        'product_id',
        'language',
        'title',
        'short_description',
        'description'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
