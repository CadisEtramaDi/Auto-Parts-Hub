<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'image',
        'description',
        'parent_id'
    ];

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all products in this category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the full path for category images
     */
    public function getImagePathAttribute()
    {
        if ($this->image) {
            return 'images/' . ($this->type === 'motor' ? 'Motor Parts' : 'Vehicle Parts') . '/' . $this->image;
        }
        return null;
    }
}