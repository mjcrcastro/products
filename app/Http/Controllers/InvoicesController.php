<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use App\Models\InvoiceDetail;

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
        
        $data = $request->all();
        
        foreach ($data as $incomingInvoice) {
            
            Log::info(print_r($incomingInvoice, true));
            
            $newInvoice = new Invoice;
            
            $newInvoice->invoicenumber_mobile = $incomingInvoice['invoicenumber'];
            $newInvoice->customername = $incomingInvoice['customername'];
            $newInvoice->invoicedate = $incomingInvoice['invoicedate'];
            $newInvoice->invoicetotal = $incomingInvoice['invoicetotal'];
            $newInvoice->save();
            
            foreach ($incomingInvoice as $itemProduct){
                //not each product
                $invoiceDetail = new InvoiceDetail;
                //$invoiceDetail
            }
        }


        return response()->json([
                    "message" => "Invoice record created"
                        ], 201);
    }

}
