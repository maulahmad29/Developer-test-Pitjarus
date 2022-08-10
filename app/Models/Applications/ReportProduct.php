<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportProduct extends Model
{
    use HasFactory;

    protected $table = 'report_product';
    protected $primaryKey = 'report_id';

    public function brand() {
        return $this->hasOneThrough(
            ProductBrand::class,
            Product::class,
            'product_id',
            'brand_id',
            'product_id',
            'brand_id'
        );
    }

    public function store()
    {
        return $this->hasMany(
            Store::class, 'store_id', 'store_id'
        );
    }

   
}
