<?php

namespace App\Applications\Services;

use App\Applications\Interfaces\IChartAreaServices;
use App\Models\Applications\Product;
use App\Models\Applications\ProductBrand;
use App\Models\Applications\ReportProduct;
use App\Models\Applications\StoreArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartAreaServices implements IChartAreaServices
{
    public function SelectArea()
    {
        $db = StoreArea::get();

        return $db;
    }

    public function Chart(Request $req)
    {

        if (count($req->area) == 0) {
            return $data = [
                'status' => 1,
                'message' => 'area belum di pilih'
            ];
        }

        if (!$req->endDate) {
            return $data = [
                'status' => 1,
                'message' => 'Tanggal sampai dengan belum disi'
            ];
        }
        if (!$req->startDate) {
            return $data = [
                'status' => 1,
                'message' => 'Tanggal mulai belum disi'
            ];
        }

        $chart = [];
        $table = [];
        $brandFlag = ProductBrand::get();

        foreach ($req->area as $row) {
            $storeArea = StoreArea::with(['storecalc' => function ($q) use ($req) {
                $q->whereDate('tanggal', '>=', $req->startDate);
                $q->whereDate('tanggal', '<=', $req->endDate);
            }, 'storecalc.brand.report'])
                ->find($row);

            $countRow = count($storeArea->storecalc);
            $sumCompliance = $storeArea->storecalc->sum('compliance');
            $calcVal = $sumCompliance / $countRow * 100;


            $obj = [
                'area' => $storeArea,
                'precentage' => number_format($calcVal, 1)
            ];

            array_push($chart, $obj);

            $dbBrand = ProductBrand::get();

            $brandObj = [];
            foreach ($dbBrand as $brand) {
                $reportRow = [];
                $reportSum = [];
                foreach ($storeArea->storecalc as $r => $rows) {
                    if ($rows->brand->brand_id == $brand->brand_id) {
                        array_push($reportRow, $rows);
                        array_push($reportSum, $rows->compliance);
                    }

                }

                $numRow = count($reportRow);
                $numSum = array_sum($reportSum);
                $report = $numSum / $numRow * 100;

                $brand = [
                    'brand' => $brand->brand_id,
                    'report' => number_format($report, 1)
                ];

                array_push($brandObj, $brand);
            }

            array_push($table, $brandObj);
            

        }

        return $data = [
            'chart' => $chart,
            'brand' => [
                'name' => $brandFlag,
                'table' => $table
            ]
        ];
    }
}
