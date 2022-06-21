<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DB;

class PurchasesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
        return view('purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
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
    public function store(Request $request) {
        DB::beginTransaction(); //will rollback in case of failure

        $data = $request->json()->all();

        //Log::info(print_r($data, true));

        if ($this->validPurchaseHeader($data)) {
            $newPurchase = new Purchase($this->createPurchaseHeader($data));
            $newPurchase->save();
        } else {
            DB::rollback();
            return $this->jsonInvalidHeader($newPurchase);
        }

        foreach ($data['products'] as $purchaseDetail) {
            //add purchased products to the detail of the invoice
            if ($this->validPurchaseDetail($purchaseDetail)) {
                $purchaseDetail = new PurchaseDetail($this->createPurchaseDetail($purchaseDetail, $newPurchase->id));
                $purchaseDetail->save();
            } else {
                DB::rollback();
                return $this->jsonInvalidDetail($purchaseDetail);
            }
        }
        DB::commit();
        return $this->jsonInvoiceStored($newPurchase);
    }

    /**
     * Display the specified resource.
     *
     * @param  Obj  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase) {
        //
        $purchaseTotal = $purchase->purchaseDetails()->sum(\DB::raw('purchase_details.cost'));
        return view('purchases.show', compact('purchase', 'purchaseTotal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase) {
        //
        $editingMode = "edit";
        $id = $purchase->id;
        return view('purchases.create', compact('editingMode', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
        DB::beginTransaction(); //will rollback in case of failure

        $data = $request->json()->all();

        //Log::info(print_r($data, true));

        if ($this->validPurchaseHeader($data)) {
            $purchase = $this->savePurchase($data, $id);
        } else {
            DB::rollback();
            return $this->jsonInvalidHeader($data);
        }
        //delete the purchaseDetail before creating a new one
        //that way dealing with checking if new or updated is not necessary

        Purchase::find($id)->purchaseDetails()->delete();

        foreach ($data['products'] as $purchasedProducts) {
            //add purchased products to the detail of the invoice
            if ($this->validPurchaseDetail($purchasedProducts)) {
                $purchaseDetail = new PurchaseDetail($this->createPurchaseDetail($purchasedProducts, $purchase->id));
                $purchaseDetail->save();
            } else {
                DB::rollback();
                return $this->jsonInvalidDetail($purchaseDetail);
            }
        }
        DB::commit();
        return $this->jsonInvoiceStored($purchase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Obj  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase) {
        //
        Purchase::find($purchase->id)->purchaseDetails()->delete();
        Purchase::find($purchase->id)->delete();
        return redirect()->route('purchases.index');
    }

    public function returnPurchase($id) {

        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        //Log::info(print_r($id, true));

        $purchase = Purchase::find($id);

        $header_data = $this->retrieveHeaderData($purchase);

        if ($purchase) {
            $counter = 0;
            foreach ($purchase->purchaseDetails as $purchaseDetail) {
                $detail_data[$counter] = [
                    'purchase_id' => $purchaseDetail->purchase_id,
                    'product_id' => $purchaseDetail->product_id,
                    'product_description' => $purchaseDetail->product->barcode . " | " . $purchaseDetail->product->description,
                    'amount' => $purchaseDetail->amount,
                    'cost' => $purchaseDetail->cost
                ];
                $counter = $counter + 1;
            }

            $response = ['purchase' => $header_data, 'purchase_details' => $detail_data];

            return response()->json($response);
        } else {
            //return error
        }
    }

    public function indexPurchasesAjax(Request $request) {
        //returns list of products
        //if ($request->ajax()) {//return json data only to ajax queries 
        $filter = $request->search['value'];

        $columns = array('name', 'buyer_name', 'purchase_invoice_number', 'purchase_date'); //array to sort by, from incoming ajax request
        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        $orderedColumn = $request->order[0]['column'] == 0 ? 'purchase_date' : $columns[$request->order[0]['column'] - 1];
        //Log::info(print_r($orderedColumn, true));
        $purchase = Purchase::select('purchases.id', 'providers.name', 'buyers.buyer_name', 'purchases.purchase_invoice_number', 'purchases.purchase_date')
                ->join('providers', 'provider_id', '=', 'providers.id')
                ->join('buyers', 'buyer_id', '=', 'buyers.id')
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

    private function createPurchaseHeader($data) {

        $purchaseHeader = [
            'provider_id' => $data['provider_id'],
            'purchase_date' => $data['purchase_date'],
            'purchase_invoice_number' => $data['purchase_invoice'],
            'buyer_id' => $data['buyer_id']
        ];

        return $purchaseHeader;
    }

    private function createPurchaseDetail($itemProduct, $purchase_id) {
        $purchaseDetail = [
            'purchase_id' => $purchase_id,
            'product_id' => $itemProduct[1],
            'amount' => $itemProduct[3],
            'cost' => $itemProduct[4]
        ];
        return $purchaseDetail;
    }

    private function validPurchaseHeader($data) {
        $purchaseValidator = Validator::make($data, [
                    'provider_id' => 'required',
                    'buyer_id' => 'required',
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

    private function retrieveHeaderData($purchase) {
        $purchase_header = [
            'provider_id' => $purchase->provider_id,
            'provider_name' => $purchase->provider->name,
            'buyer_id' => $purchase->buyer_id,
            'buyer_name' => $purchase->buyer->buyer_name,
            'purchase_invoice_number' => $purchase->purchase_invoice_number,
            'purchase_date' => $purchase->purchase_date,
        ];

        return $purchase_header;
    }
    
    private function savePurchase($data, $id) {
        $purchase = Purchase::find($id);
        $newPurchase = $this->createPurchaseHeader($data);
        $purchase->provider_id = $newPurchase['provider_id'];
        $purchase->purchase_invoice_number = $newPurchase['purchase_invoice_number'];
        $purchase->purchase_date = $newPurchase['purchase_date'];
        $purchase->buyer_id = $newPurchase['buyer_id'];
        $purchase->save();
         
        return $purchase;
    }
    
    

}
