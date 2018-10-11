<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends \Spatie\MediaLibrary\Models\Media
{
    protected $appends = [
        'url'
    ];

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }
}
