<?php

namespace App\Http\Controllers;

use App\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function filter(Request $request)
    {
        try {
            $table = Table::all();
            return response()->json([
                'status' => 200,
                'data' => $table,
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
            $table = DB::table('tables')->where('code', $request->get('code'))->first();
            if ($table) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Bàn đã tồn tại'
                ], 500);
            }
            $table = $this->table->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'TableType_Id'=>$request->get('TableType_Id')
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $table
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
            $table = DB::table('tables')->where('id', $id);
            if (!$table) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $table = DB::table('tables')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'status' => $request->get('status'),
                    'TableType_Id'=> $request->get('TableType_Id'),
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
            $table = DB::table('tables')->find($id);
            if ($table) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $table
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
                DB::table('tables')->whereIn('id', $listId)->delete();
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
