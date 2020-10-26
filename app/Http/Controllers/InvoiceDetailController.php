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
                ->join('invoices', 'invoice_details.invoice_Id', '=', 'invoices.id')
                ->join('ingredients', 'invoice_details.ingredient_Id', '=', 'ingredients.id')
                ->select('invoice_details.*', 'invoices.name as invoice_Name', 'ingredients.name as ingredient_Name')
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
            $invoice_details = $this->invoiceDetail->create([
                'code' => $request->get('code'),
                'name' => $request->get('name'),
                'status' => $request->get('status'),
                'total' => $request->get('total'),
                'price' => $request->get('price'),
                'invoice_Id' => $request->get('invoice_Id'),
                'quantity' => $request->get('quantity'),
                'discount' => $request->get('discount'),
                'ingredient_Id' => $request->get('ingredient_Id'),
            ]);

            //Cập nhật giá nhập nguyên liệu, số lượng khi thêm mới chi tiết hóa đơn nhập
            $newPrice = DB::table('invoice_details')->where('invoice_Id', '=', $request->get('invoice_Id'))
                ->orderByDesc('created_at')
                ->take(1)
                ->get('price');
            $newPrice = $newPrice[0]->price;
            $count_quantity = DB::table('invoice_details')->where('ingredient_Id', '=', $request->get('ingredient_Id'))
                ->sum('quantity');
            $invoice = DB::table('ingredients')->where('id', $request->get('ingredient_Id'));
            $invoice->update([
                'quantity' => $count_quantity,
                'importPrice' => $newPrice
            ]);

            //Cập nhật thành tiền khi thêm mới chi tiết hóa đơn nhập
            $total = DB::table('invoice_details')->where('invoice_Id', '=', $request->get('invoice_Id'))
                ->sum('total');
            $invoice = DB::table('invoices')->where('id', $request->get('invoice_Id'));
            $invoice->update([
                'total' => $total
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
                    'total' => $request->get('total'),
                    'price' => $request->get('price'),
                    'invoice_Id' => $request->get('invoice_Id'),
                    'quantity' => $request->get('quantity'),
                    'discount' => $request->get('discount'),
                    'ingredient_Id' => $request->get('ingredient_Id'),
                    'updated_at' => Carbon::now()
                ]);

                //Cập nhật giá nhập nguyên liệu, số lượng khi thêm mới chi tiết hóa đơn nhập
                $newPrice = DB::table('invoice_details')->where('invoice_Id', '=', $request->get('invoice_Id'))
                    ->orderByDesc('updated_at')
                    ->take(1)
                    ->get('price');
                $newPrice = $newPrice[0]->price;
                $count_quantity = DB::table('invoice_details')->where('ingredient_Id', '=', $request->get('ingredient_Id'))
                    ->sum('quantity');
                $invoice = DB::table('ingredients')->where('id', $request->get('ingredient_Id'));
                $invoice->update([
                    'quantity' => $count_quantity,
                    'importPrice' => $newPrice
                ]);

                //Cập nhật thành tiền khi cập nhật chi tiết hóa đơn nhập
                $total = DB::table('invoice_details')->where('invoice_Id', '=', $request->get('invoice_Id'))
                    ->sum('total');
                $invoice = DB::table('invoices')->where('id', $request->get('invoice_Id'));
                $invoice->update([
                    'total' => $total
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật thành công',
                    'data' => [
                        'id' => $id,
                        'code' => $request->get('code'),
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

                foreach ($listId as $item) {

                    $invoiceDetail = DB::table('invoice_details')->where('id', '=',$item)->get();

                    DB::table('invoice_details')->where('id', $item)->delete();

                    //Cập nhật giá nhập nguyên liệu, số lượng khi thêm mới chi tiết hóa đơn nhập

                    $newPrice = DB::table('invoice_details')->where('invoice_Id', '=', $invoiceDetail[0]->invoice_Id)
                        ->orderByDesc('updated_at')
                        ->take(1)
                        ->get('price');
                    $newPrice = $newPrice[0]->price;
                    $count_quantity = DB::table('invoice_details')->where('ingredient_Id', '=', $invoiceDetail[0]->ingredient_Id)
                        ->sum('quantity');
                    $invoice = DB::table('ingredients')->where('id', $invoiceDetail[0]->ingredient_Id);
                    $invoice->update([
                        'quantity' => $count_quantity,
                        'importPrice' => $newPrice
                    ]);

                    //Cập nhật thành tiền khi cập nhật chi tiết hóa đơn nhập
                    $total = DB::table('invoice_details')->where('invoice_Id', '=', $invoiceDetail[0]->invoice_Id)
                        ->sum('total');
                    $invoice = DB::table('invoices')->where('id', $invoiceDetail[0]->invoice_Id);
                    $invoice->update([
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
