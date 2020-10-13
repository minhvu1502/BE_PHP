<?php

namespace App\Http\Controllers;

use App\InvoiceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceDetailController extends Controller
{
    private $invoiceDetail;

    public function __construct(InvoiceDetail $invoiceDetail)
    {
        $this->invoiceDetail = $invoiceDetail;
    }

    public function filter(Request $request)
    {
        try {
            $invoice_details = DB::table('invoice_details')
                ->join('employees','invoice_details.employee_Id','=','employees.id')
                ->join('providers','invoice_details.provider_Id','=','providers.id')
                ->select('invoice_details.*','employees.name as employee_Name','providers.name as provider_Name')
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $invoice_details,
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
            $invoice_details = DB::table('invoice_details')->where('code', $request->get('code'))->first();
            if ($invoice_details) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Chi tiết hóa đơn nhập đã tồn tại'
                ], 500);
            }
            $invoice_details = $this->invoice_details->create([
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
                'data' => $invoice_details
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
            $invoice_details = DB::table('invoice_details')->where('id', $id);
            if (!$invoice_details) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể cập nhật'
                ]);
            } else {
                $invoice_details = DB::table('invoice_details')->where('id', $id)->update([
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
            $invoice_details = DB::table('invoice_details')->find($id);
            if ($invoice_details) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lấy dữ liệu thành công',
                    'data' => $invoice_details
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
                DB::table('invoice_details')->whereIn('id', $listId)->delete();
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
