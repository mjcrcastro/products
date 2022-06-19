<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DB;

class PurchasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         return view('purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $editingMode = "create";
        return view('purchases.create', compact('editingMode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
        //
        $editingMode = "edit";
        return view('purchases.create', compact('editingMode','purchase'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function receivePurchasesJson(Request $request) {

        DB::beginTransaction(); //will rollback in case of failure

        $data = $request->json()->all();
        
        Log::info(print_r($data, true));
        
        if ($this->validPurchaseHeader($data)) {
            $newPurchase = $this->createPurchaseHeader($data);
            $newPurchase->save();
        } else {
            DB::rollback();
            return $this->jsonInvalidHeader($newPurchase);
        }

        foreach ($data['products'] as $purchaseDetail) {
            //add purchased products to the detail of the invoice
            if ($this->validPurchaseDetail($purchaseDetail)) {
                $purchaseDetail = $this->createInvoiceDetail($purchaseDetail, $newPurchase->id);
                $purchaseDetail->save();
            } else {
                DB::rollback();
                return $this->jsonInvalidDetail($purchaseDetail);
            }
        }
        DB::commit();
        return $this->jsonInvoiceStored($newPurchase);
    }

    private function createPurchaseHeader($data) {
        $newPurchase = new Purchase;
        $newPurchase->invoicenumber_mobile = $data['invoicenumber'];
        $newPurchase->customername = $data['customername'];
        $newPurchase->invoicedate = $data['invoicedate'];
        $newPurchase->invoicetotal = $data['invoicetotal'];

        return $newPurchase;
    }

    private function createInvoiceDetail($itemProduct, $purchaseId) {
        $purchaseDetail = new PurchaseDetail;
        $purchaseDetail->invoice_id = $purchaseId;
        $purchaseDetail->amount = $itemProduct['amount'];
        $purchaseDetail->price = $itemProduct['cost'];
        return $purchaseDetail;
    }

    private function validPurchaseHeader($data) {
        $purchaseValidator = Validator::make($data, [
                    'inovicenumber_mobile' => 'required',
                    'customername' => 'required',
                    'invoicedate' => 'required',
                    'invoicetotal' => 'required'
                        ]
        );

        return $purchaseValidator->fails();
    }

    private function validPurchaseDetail($purchaseDetail) {
        $purchaseDetailValidator = Validator::make($purchaseDetail, [
                    'barcode' => 'required',
                    'product_id' => 'required',
                    'amount' => 'required',
                    'price' => 'required'
                        ]
        );

        return $purchaseDetailValidator->fails();
    }

    private function jsonInvalidHeader($newPurchase) {
        return response()->json([
                    "message" => "Invalid Invoice Header",
                    "object" => $newPurchase,
                        ], 400);
    }

    private function jsonInvalidDetail($purchaseDetail) {
        return response()->json([
                    "message" => "Invalid Purchase detail",
                    "object" => $purchaseDetail,
                        ], 400);
    }

    private function jsonInvoiceStored($newPurchase) {
        return response()->json([
                    "message" => "Invoice record created",
                    "invoiceNum" => $newPurchase->invoicenumber_mobile
                        ], 201);
    }
    
}
