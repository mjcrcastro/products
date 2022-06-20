<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
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
    public function show(Purchase $purchase)
    {
        //
        $purchaseTotal = $purchase->purchaseDetails()->sum(\DB::raw('purchase_details.amount * purchase_details.cost'));
        return view('purchases.show', compact('purchase','purchaseTotal'));
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
    public function destroy(Purchase $purchase)
    {
        //
        Purchase::find($purchase->id)->purchaseDetails()->delete();
        Purchase::find($purchase->id)->delete();
        return redirect()->route('purchases.index');
    }
    
    public function indexPurchasesAjax(Request $request) {
        //returns list of products
        //if ($request->ajax()) {//return json data only to ajax queries 
        $filter = $request->search['value'];

        $columns = array('name', 'buyer_name', 'purchase_invoice_number','purchase_date'); //array to sort by, from incoming ajax request
        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        $orderedColumn = $request->order[0]['column'] == 0? 'purchase_date' : $columns[$request->order[0]['column'] - 1];
        Log::info(print_r($orderedColumn, true));
        $purchase = Purchase::select('purchases.id', 'providers.name', 'buyers.buyer_name', 'purchases.purchase_invoice_number', 'purchases.purchase_date')
                ->join('providers','provider_id','=','providers.id')
                ->join('buyers','buyer_id','=','buyers.id')
                ->where($orderedColumn, 'LIKE', "%" . $filter . "%")
                ->orderBy($orderedColumn, $request->order[0]['dir']) //order[0]['column'] contains the column to be ordered as selected on the US and sent to Laravel by DataTables vi ajax
                ->get();

        $response['draw'] = $request->get('draw');

        $response['recordsTotal'] = Purchase::all()->count();

        $response['recordsFiltered'] = $purchase->count();

        $response['data'] = array_slice($purchase->toArray(), $request->get('start'), $request->get('length'));

        return response()->json($response);
        //}
    }
    
    public function receivePurchasesJson(Request $request) {

        DB::beginTransaction(); //will rollback in case of failure

        $data = $request->json()->all();
        
       // Log::info(print_r($data, true));
        
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
        $newPurchase->provider_id = $data['provider_id'];
        $newPurchase->purchase_date = $data['purchase_date'];
        $newPurchase->purchase_invoice_number = $data['purchase_invoice'];

        return $newPurchase;
    }

    private function createInvoiceDetail($itemProduct, $purchaseId) {
        $purchaseDetail = new PurchaseDetail;
        $purchaseDetail->purchase_id = $purchaseId;
        $purchaseDetail->product_id = $itemProduct[1];
        $purchaseDetail->amount = $itemProduct[3];
        $purchaseDetail->cost = $itemProduct[4];
        return $purchaseDetail;
    }

    private function validPurchaseHeader($data) {
        $purchaseValidator = Validator::make($data, [
                    'provider_id' => 'required',
                    'purchase_date' => 'required',
                    'purchase_invoice_number' => 'required'
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
                    "purchaseId" => $newPurchase->id
                        ], 201);
    }
    
}
