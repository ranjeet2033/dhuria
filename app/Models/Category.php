<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->where('is_active', true);
    }

    public function allChildren()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('allChildren');
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/card_diabetes_pro_hd.jpg');
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        return asset('images/card_diabetes_pro_hd.jpg');
    }
}
