<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected static function booted()
    {
        static::saving(function ($category) {
            if ($category->isDirty('name')) {
                $slug = Str::slug($category->name);
                $originalSlug = $slug;
                $count = 1;

                while (static::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $category->slug = $slug;
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
