<?php

namespace App\Http\Controllers;

use App\Applications\Interfaces\IChartAreaServices;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    protected IChartAreaServices $ChartServices;
    public function __construct(IChartAreaServices $ChartServices)
    {
        $this->ChartServices = $ChartServices;
    }

    public function area()
    {
        try
        {
         $resp = $this->ChartServices->SelectArea();
 
         return response()->json($resp);
        }
        catch(Exception $ex)
        {
         return response()->json($ex->getMessage());
        }
    }

    public function chart(Request $req)
    {
       try
       {
        $resp = $this->ChartServices->Chart($req);

        return response()->json($resp);
       }
       catch(Exception $ex)
       {
        return response()->json($ex->getMessage());
       }
    }
}
