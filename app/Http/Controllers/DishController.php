<?php

namespace App\Http\Controllers;

use App\Dish;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DishController extends Controller
{
    private $dish;

    public function __construct(Dish $dish)
    {
        $this->dish = $dish;
    }

    public function filter(Request $request)
    {
        try {
            $dishes = DB::table('dishes')
                ->join('uses', 'dishes.use_Id', '=', 'uses.id')
                ->join('dish_types', 'dishes.dishType_Id', '=', 'dish_types.id')
                ->select('dishes.*', 'uses.name as use_Name', 'dish_types.name as dishType_Name')
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $dishes,
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
            $dish = DB::table('dishes')->where('code', $request->get('code'))->first();
            if ($dish) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Món ăn đã tồn tại'
                ], 500);
            }
            $dish = $this->dish->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'make' => $request->get('make'),
                'petition' => $request->get('petition'),
                'total' => $request->get('total'),
                'dishType_Id' => $request->get('dishType_Id'),
                'use_Id' => $request->get('use_Id'),
                'status' => $request->get('status'),
                'pictureUrl' => $request->get('pictureUrl')
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $dish
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
            $dish = DB::table('dishes')->where('id', $id);
            if (!$dish) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $dish = DB::table('dishes')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'make' => $request->get('make'),
                    'petition' => $request->get('petition'),
                    'total' => $request->get('total'),
                    'dishType_Id' => $request->get('dishType_Id'),
                    'use_Id' => $request->get('use_Id'),
                    'status' => $request->get('status'),
                    'pictureUrl' => $request->get('pictureUrl'),
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
            $dish = DB::table('dishes')->find($id);
            if ($dish) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $dish
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
                DB::table('dishes')->whereIn('id', $listId)->delete();
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
