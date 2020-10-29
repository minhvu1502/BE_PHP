<?php

namespace App\Http\Controllers;

use App\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    private $invoices;

    public function __construct(Invoice $invoices)
    {
        $this->invoices = $invoices;
    }

    public function filter(Request $request)
    {
        try {
            $invoices = DB::table('invoices')
                ->join('employees','invoices.employee_Id','=','employees.id')
                ->join('providers','invoices.provider_Id','=','providers.id')
                ->select('invoices.*','employees.name as employee_Name','providers.name as provider_Name')
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $invoices,
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
            $invoices = DB::table('invoices')->where('code', $request->get('code'))->first();
            if ($invoices) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Hóa đơn nhập đã tồn tại'
                ], 500);
            }
            $invoices = $this->invoices->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'employee_Id'=>$request->get('employee_Id'),
                'import_Day'=>$request->get('import_Day'),
                'provider_Id'=>$request->get('provider_Id'),
                'total'=>$request->get('total'),
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Tạo mới thành công',
                'data' => $invoices
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
            $invoices = DB::table('invoices')->where('id', $id);
            if (!$invoices) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $invoices = DB::table('invoices')->where('id', $id)->update([
                    'name' => $request->get('name'),
                    'status' => $request->get('status'),
                    'employee_Id'=>$request->get('employee_Id'),
                    'import_Day'=>$request->get('import_Day'),
                    'provider_Id'=>$request->get('provider_Id'),
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
            $invoices = DB::table('invoices')->find($id);
            if ($invoices) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $invoices
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
                DB::table('invoices')->whereIn('id', $listId)->delete();
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
