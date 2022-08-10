<?php

namespace App\Models\Applications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreArea extends Model
{
    use HasFactory;

    protected $table = 'store_area';
    protected $primaryKey = 'area_id';

    public function storecalc() {
        return $this->hasManyThrough(
            ReportProduct::class,
            Store::class,
            'area_id',
            'store_id',
            'area_id',
            'store_id'
        );
    }

 
}
