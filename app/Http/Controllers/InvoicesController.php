<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use App\Models\InvoiceDetail;
use App\Models\Product;

class InvoicesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function receiveInvoicesJson(Request $request) {

        $data = $request->json()->all();

            Log::info(print_r($data, true)); 
            
            $newInvoice = new Invoice;
            $newInvoice->invoicenumber_mobile = $data['invoicenumber'];
            $newInvoice->customername = $data['customername'];
            $newInvoice->invoicedate = $data['invoicedate'];
            $newInvoice->invoicetotal = $data['invoicetotal'];
            $newInvoice->save();

            foreach ($data['products'] as $itemProduct) {
                //add purchased products to the detail of the invoice

                $invoiceDetail = new InvoiceDetail;
                $invoiceDetail->invoice_id = $newInvoice->id;
                $invoiceDetail->product_id = Product::where('barcode', $itemProduct['barcode'])->first()->id;
                $invoiceDetail->amount = $itemProduct['amount'];
                $invoiceDetail->price = $itemProduct['price'];
                $invoiceDetail->save();
            }
            
            $validator = Validator::make($request->all(), [
                    'barcode' => ['required', Rule::unique('products')],
                    'description' => ['required', Rule::unique('products')],
                    'price' => ['required','numeric']
                        ]
        );

        if ($validator->fails()) {
            return redirect()->route('products.create')
                            ->withErrors($validator)
                            ->withInput();
        }

        return response()->json([
                    "message" => "Invoice record created",
                    "invoiceNum"=>$newInvoice->invoicenumber_mobile
                        ], 201);
    }
}
