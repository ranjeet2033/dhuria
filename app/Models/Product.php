<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $images = json_decode($this->images, true);
        if (!is_array($images)) {
            $images = !empty($this->images) ? [$this->images] : [];
        }
        $firstImg = count($images) > 0 ? $images[0] : null;
        if ($firstImg && (str_starts_with($firstImg, 'http://') || str_starts_with($firstImg, 'https://'))) {
            return $firstImg;
        }
        if ($firstImg && file_exists(public_path($firstImg))) {
            return asset($firstImg);
        }
        return asset('images/logo.jpeg');
    }

    public function getAllImageUrlsAttribute(): array
    {
        $images = json_decode($this->images, true);
        if (!is_array($images)) {
            $images = !empty($this->images) ? [$this->images] : [];
        }
        $urls = [];
        foreach ($images as $img) {
            if (empty($img)) continue;
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                $urls[] = $img;
            } elseif (file_exists(public_path($img))) {
                $urls[] = asset($img);
            }
        }
        if (empty($urls)) {
            $urls = [$this->primary_image_url];
        }
        $supplements = [
            asset('images/card_diabetes_pro.jpg'),
            asset('images/card_liver_detox.jpg'),
            asset('images/card_pilecurex.jpg'),
            asset('images/card_paachan_sathi.jpg')
        ];
        foreach ($supplements as $sup) {
            if (count($urls) >= 4) break;
            if (!in_array($sup, $urls)) {
                $urls[] = $sup;
            }
        }
        return $urls;
    }
}
