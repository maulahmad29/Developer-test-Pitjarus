<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    use HasFactory;

    protected $table = 'product_brand';
    protected $primaryKey = 'brand_id';

    public function report()
    {
        return $this->hasManyThrough(
            ReportProduct::class,
            Product::class,
            'product_id',
            'product_id',
            'brand_id',
            'brand_id'
        );
    }

    public function product()
    {
        return $this->hasMany(
            Product::class, 'brand_id', 'brand_id'
        );
    }


}
