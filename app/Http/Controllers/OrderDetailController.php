<?php

namespace App\Http\Controllers;

use App\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller
{
    private $orders;
    private $tableName = 'order_details';
    private $table_dish = 'dishes';

    public function __construct(OrderDetail $orders)
    {
        $this->orders = $orders;
    }

    public function filter(Request $request)
    {
        try {
            $orders = DB::table($this->tableName)->get();
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
                    'message' => 'Chi tiết phiếu đặt bàn đã tồn tại'
                ], 500);
            }
            $orders = $this->orders->create([
                'code' => $request->get('code'),
                'status' => $request->get('status'),
                'quantity'=>$request->get('quantity'),
                'discount'=>$request->get('discount'),
                'order_Id'=>$request->get('order_Id'),
                'dish_Id'=>$request->get('dish_Id'),
                'total'=>$request->get('total'),
            ]);
            $this->updateTotalOrder($request->get('order_Id'));
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
    public function updateTotalOrder($order_Id){
        $count_total = DB::table($this->tableName)->where('order_Id', '=', $order_Id)
            ->sum('total');
        $dishes = DB::table($this->table_dish)->where('id', $order_Id);
        $dishes->update([
            'total'=>$count_total
        ]);
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
                    'status' => $request->get('status'),
                    'quantity'=>$request->get('quantity'),
                    'discount'=>$request->get('discount'),
                    'order_Id'=>$request->get('order_Id'),
                    'dish_Id'=>$request->get('dish_Id'),
                    'total'=>$request->get('total'),
                    'updated_at' => Carbon::now()
                ]);
                $this->updateTotalOrder($request->get('order_Id'));
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


                foreach ($listId as $item) {

                    $dish = DB::table($this->tableName)->where('id', '=',$item)->get();

                    DB::table($this->tableName)->where('id', $item)->delete();
                    //Cập nhật thành tiền khi cập nhật chi tiết phiếu đặt bàn
                    $total = DB::table($this->tableName)->where('dish_Id', '=', $dish[0]->dish_Id)
                        ->sum('total');
                    $order = DB::table($this->table_dish)->where('id', $dish[0]->dish_Id);
                    if ($total == null){
                        $total = 0;
                    }
                    $dish->update([
                        'total' => $total
                    ]);
                }
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
