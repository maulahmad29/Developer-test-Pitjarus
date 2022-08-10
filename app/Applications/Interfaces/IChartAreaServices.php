<?php

namespace App\Applications\Interfaces;

use Illuminate\Http\Request;

interface IChartAreaServices {
    public function Chart(Request $req);
    public function SelectArea();
}