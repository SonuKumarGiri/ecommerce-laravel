<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'status',
        'image',
    ];

    protected static function booted()
    {
        static::saving(function ($product) {
            if ($product->isDirty('name')) {
                $slug = Str::slug($product->name);
                $originalSlug = $slug;
                $count = 1;

                while (static::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $product->slug = $slug;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
