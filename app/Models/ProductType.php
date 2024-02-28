<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class ProductType extends Model
{
    use HasFactory,SoftDeletes,Uuid;

     public function getKeyType()
    {
        return 'string';
    }
}
