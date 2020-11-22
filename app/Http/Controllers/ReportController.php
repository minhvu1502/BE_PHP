<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function ReportMonth($month, $year){
        return DB::table('orders')
            ->whereMonth('orderDate', $month)
            ->whereYear('orderDate', $year)
            ->join('order_details', 'order_details.order_id', '=', 'orders.id')
            ->join('dishes', 'dishes.id', '=', 'order_details.dish_id')
            ->groupBy('orderDate')
            ->select(DB::raw('sum(order_details.quantity*dishes.total) as Total'))
            ->get();
    }
    public function GetReportMonth(Request $request){
        try {
            $query = $this->ReportMonth($request->get('month'), $request->get('year'));
            return response()->json([
                'status' => 200,
                'data' => $query,
                'message' => 'Lấy dữ liệu thành công'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e
            ], 500);
        }
    }
}
