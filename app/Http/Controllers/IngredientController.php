<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngredientController extends Controller
{
    private $ingredient;

    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    public function filter(Request $request)
    {
        try {
            $ingredients = DB::table('ingredients')->get()->all();
            return response()->json([
                'status' => 200,
                'data' => $ingredients,
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
            $ingredient = DB::table('ingredients')->where('code', $request->get('code'))->first();
            if ($ingredient) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Nguyên liệu đã tồn tại'
                ], 500);
            }
            $ingredient = $this->ingredient->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'quantity' => $request->get('quantity'),
                'importPrice' => $request->get('importPrice'),
                'price' => $request->get('price'),
                'requirement' => $request->get('requirement'),
                'unit' => $request->get('unit'),
                'contraindication' => $request->get('contraindication'),
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $ingredient
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
            $ingredient = DB::table('ingredients')->where('id', $id);
            if (!$ingredient) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $ingredient = DB::table('ingredients')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'status' => $request->get('status'),
                    'quantity' => $request->get('quantity'),
                    'importPrice' => $request->get('importPrice'),
                    'price' => $request->get('price'),
                    'requirement' => $request->get('requirement'),
                    'unit' => $request->get('unit'),
                    'contraindication' => $request->get('contraindication'),
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật thành công',
                    'data' => [
                        'id' => $id,
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
            $ingredient = DB::table('ingredients')->find($id);
            if ($ingredient) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $ingredient
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
                DB::table('ingredients')->whereIn('id', $listId)->delete();
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
