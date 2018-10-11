<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyGalleryImage extends Model
{
    protected $fillable = ['company_id', 'file_path'];

}
