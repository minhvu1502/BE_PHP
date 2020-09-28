<?php

namespace App\Http\Controllers;

use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    private $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function filter(Request $request)
    {
        try {
            $employee = DB::table('employees')
                ->join('hometowns', 'employees.Hometown_Id', '=', 'hometowns.id')
                ->select('employees.*', 'hometowns.name as Hometown_Name')
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $employee,
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
            $employee = DB::table('employees')->where('code', $request->get('code'))->first();
            if ($employee) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Nhân viên đã tồn tại'
                ], 500);
            }
            $employee = $this->employee->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'sex' => $request->get('sex'),
                'dateOfBirth' => $request->get('dateOfBirth'),
                'phone' => $request->get('phone'),
                'address' => $request->get('address'),
                'email' => $request->get('email'),
                'Hometown_Id' => $request->get('Hometown_Id'),
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $employee
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
            $employee = DB::table('employees')->where('id', $id);
            if (!$employee) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $employee = DB::table('employees')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'status' => $request->get('status'),
                    'sex' => $request->get('sex'),
                    'dateOfBirth' => $request->get('dateOfBirth'),
                    'phone' => $request->get('phone'),
                    'Hometown_Id' => $request->get('Hometown_Id'),
                    'address' => $request->get('address'),
                    'email' => $request->get('email'),
                    'updated_at' => Carbon::now()
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
            $employee = DB::table('employees')->find($id);
            if ($employee) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $employee
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
                DB::table('employees')->whereIn('id', $listId)->delete();
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
