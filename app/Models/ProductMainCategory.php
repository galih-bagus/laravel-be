<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ProductMainCategory extends Model
{
    use HasFactory,SoftDeletes,Uuid;

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

     public function getKeyType()
    {
        return 'string';
    }
}
