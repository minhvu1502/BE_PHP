<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $orders;
    private $tableName = 'orders';

    public function __construct(Order $orders)
    {
        $this->orders = $orders;
    }

    public function filter(Request $request)
    {
        try {
            $orders = DB::table($this->tableName)
                ->join('tables','orders.table_Id' ,'=','tables.id')
                ->join('employees','orders.employee_Id' ,'=','employees.id')
                ->join('customers','orders.customer_Id' ,'=','customers.id')
                ->select('orders.*','employees.name as employee_Name','tables.name as table_Name', 'customers.name as customer_Name')
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $orders,
                'message' => 'Lấy dữ liệu thành công'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $orders = DB::table($this->tableName)->where('code', $request->get('code'))->first();
            if ($orders) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Phiếu đặt bàn đã tồn tại'
                ], 500);
            }
            $orders = $this->orders->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'employee_Id'=>$request->get('employee_Id'),
                'orderDate'=>$request->get('orderDate'),
                'useDate'=>$request->get('useDate'),
                'customer_Id'=>$request->get('customer_Id'),
                'table_Id'=>$request->get('table_Id'),
                'total'=>$request->get('total'),
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $orders
            ], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 500,
                'message' => $e
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $orders = DB::table($this->tableName)->where('id', $id);
            if (!$orders) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $orders = DB::table($this->tableName)->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'status' => $request->get('status'),
                    'employee_Id'=>$request->get('employee_Id'),
                    'orderDate'=>$request->get('orderDate'),
                    'useDate'=>$request->get('useDate'),
                    'customer_Id'=>$request->get('customer_Id'),
                    'table_Id'=>$request->get('table_Id'),
                    'total'=>$request->get('total'),
                    'updated_at' => Carbon::now()
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật thành công',
                    'data' => [
                        'id' => $id,
                        'code'=> $request->get('code'),
                        'name' => $request->get('name')
                    ]
                ], 200);
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 500,
                'message' => $e,
            ], 500);
        }
    }

    public function Detail($id)
    {
        try {
            $orders = DB::table($this->tableName)->find($id);
            if ($orders) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $orders
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không tìm thấy'
                ], 500);
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 500,
                'message' => $e
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $listId = $request->get('listId');
            $count = count($listId);
            if ($count > 0) {
                DB::table($this->tableName)->whereIn('id', $listId)->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Xóa dữ liệu thành công',
                    'data' => $listId
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Xóa dữ liệu không thành công'
                ], 500);
            }
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 500,
                'message' => $e
            ], 500);
        }
    }
}
