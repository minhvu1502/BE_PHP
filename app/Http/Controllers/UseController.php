<?php

namespace App\Http\Controllers;

use App\uses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UseController extends Controller
{
    private $uses;

    public function __construct(uses $uses)
    {
        $this->uses = $uses;
    }

    public function filter(Request $request)
    {
        try {
            $uses = uses::all();
            return response()->json([
                'status' => 200,
                'data' => $uses,
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
            $uses = DB::table('uses')->where('code', $request->get('code'))->first();
            if ($uses) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Công dụng đã tồn tại'
                ], 500);
            }
            $uses = $this->uses->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'description'=>$request->get('description')
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $uses
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
            $uses = DB::table('uses')->where('id', $id);
            if (!$uses) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $uses = DB::table('uses')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'status' => $request->get('status'),
                    'description'=> $request->get('description'),
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
            $uses = DB::table('uses')->find($id);
            if ($uses) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $uses
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
                DB::table('uses')->whereIn('id', $listId)->delete();
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
