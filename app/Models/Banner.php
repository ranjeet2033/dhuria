<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/floramade_slider_pilecurex.jpg');
        }
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }
        return asset($this->image_path);
    }
}
