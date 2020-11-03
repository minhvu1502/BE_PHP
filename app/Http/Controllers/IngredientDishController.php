<?php

namespace App\Http\Controllers;

use App\Ingredient_Dish;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientDishController extends Controller
{
    private $ingredient_dishes;

    public function __construct(Ingredient_Dish $ingredient_dishes)
    {
        $this->ingredient_dishes = $ingredient_dishes;
    }

    public function filter(Request $request)
    {
        try {
            $ingredient_dishes = DB::table('ingredient_dishes')
                ->join('ingredients','ingredient_dishes.ingredient_Id','=','ingredients.id')
                ->join('dishes','ingredient_dishes.dish_Id','=','dishes.id')
                ->select('ingredient_dishes.*','employees.name as employee_Name','providers.name as provider_Name')
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $ingredient_dishes,
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
            $ingredient_dishes = DB::table('ingredient_dishes')->where('code', $request->get('code'))->first();
            if ($ingredient_dishes) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Món ăn-nguyên liệu đã tồn tại'
                ], 500);
            }
            $ingredient_dishes = $this->ingredient_dishes->create([
                'code' => $request->get('code'),
                'status' => $request->get('status'),
                'ingredient_Id'=>$request->get('ingredient_Id'),
                'dish_Id'=>$request->get('dish_Id'),
                'quantity'=>$request->get('quantity'),
            ]);
            $this->updateQuantityIngredient($request->get('ingredient_Id'), $request->get('quantity'));
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $ingredient_dishes
            ], 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'status' => 500,
                'message' => $e
            ], 500);
        }
    }

    public function updateQuantityIngredient(int $id, int $quantity){
        $count_quantity = DB::table('ingredients')->where('id','=',$id)->sum('quantity');
        $count_quantity = $count_quantity - $quantity;
        $update = DB::table('ingredients')->update([
            'quantity'=>$count_quantity
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $ingredient_dishes = DB::table('ingredient_dishes')->where('id', $id);
            if (!$ingredient_dishes) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $ingredient_dishes = DB::table('ingredient_dishes')->where('id', $id)->update([
                    'status' => $request->get('status'),
                    'ingredient_Id'=>$request->get('ingredient_Id'),
                    'dish_Id'=>$request->get('dish_Id'),
                    'quantity'=>$request->get('quantity'),
                    'updated_at' => Carbon::now()
                ]);
                $this->updateQuantityIngredient($request->get('ingredient_Id'), $request->get('quantity'));
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
            $ingredient_dishes = DB::table('ingredient_dishes')->find($id);
            if ($ingredient_dishes) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $ingredient_dishes
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
                DB::table('ingredient_dishes')->whereIn('id', $listId)->delete();
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
