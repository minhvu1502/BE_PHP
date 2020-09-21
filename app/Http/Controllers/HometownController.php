<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hometown;
use Illuminate\Support\Facades\DB;

class HometownController extends Controller
{
    //
    private $hometown;

    public function __construct(Hometown $hometown)
    {
        $this->hometown = $hometown;
    }

    public function filter()
    {
        try {
            $hometowns = DB::table('hometowns')->get();
            return response()->json([
                'status' => 200,
                'data' => $hometowns,
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
            $hometown = DB::table('hometowns')->where('code', $request->get('code'))->first();
            if ($hometown) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Quê đã tồn tại'
                ], 500);
            }
            $hometown = $this->hometown->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status')
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $hometown
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
            $hometown = DB::table('hometowns')->where('id',$id);
            if (!$hometown) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            }
            else {
                $hometown = DB::table('hometowns')->where('id', $id)->update([
                    'name'=>$request->get('name'),
                    'status'=>$request->get('status')
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật thành công',
                    'data' => [
                        'id'=>$id,
                        'name'=>$request->get('name')
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
            $hometown = DB::table('hometowns')->find($id);
            if ($hometown) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $hometown
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
                DB::table('hometowns')->whereIn('id', $listId)->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>'Xóa dữ liệu thành công',
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
