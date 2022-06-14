<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use App\Models\InvoiceDetail;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use DB;

class InvoicesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
          //Shows list of invoices, data is handled by a datatables via Json
        return view('invoices.index');
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
    public function show(Invoice $invoice) {
        //
        return view('invoices.show', compact('invoice'));
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
    public function destroy(Invoice $invoice) {
   
        //
        Invoice::find($invoice->id)->invoiceDetails()->delete();
        Invoice::find($invoice->id)->delete();
        return redirect()->route('invoices.index');
    }
    
    public function indexInvoicesAjax(Request $request) {
        //returns list of products
        //if ($request->ajax()) {//return json data only to ajax queries 
        $filter = $request->search['value'];

        $columns = array('invoicenumber_mobile', 'customername', 'invoicedate','invoicetotal'); //array to sort by, from incoming ajax request
        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        $orderedColumn = $request->order[0]['column'] == 0? 'customername' : $columns[$request->order[0]['column'] - 1];

        $product = Invoice::where($orderedColumn, 'LIKE', "%" . $filter . "%")
                ->orderBy($orderedColumn, $request->order[0]['dir']) //order[0]['column'] contains the column to be ordered as selected on the US and sent to Laravel by DataTables vi ajax
                ->get();

        $response['draw'] = $request->get('draw');

        $response['recordsTotal'] = Product::all()->count();

        $response['recordsFiltered'] = $product->count();

        $response['data'] = array_slice($product->toArray(), $request->get('start'), $request->get('length'));

        return response()->json($response);
        //}
    }
    

    public function receiveInvoicesJson(Request $request) {

        DB::beginTransaction(); //will rollback in case of failure

        $data = $request->json()->all();
        
        //Log::info(print_r($data, true));
        
        if ($this->validInvoiceHeader($data)) {
            $newInvoice = $this->createInvoiceHeader($data);
            $newInvoice->save();
        } else {
            DB::rollback();
            return $this->jsonInvalidHeader($newInvoice);
        }

        foreach ($data['products'] as $invoiceDetail) {
            //add purchased products to the detail of the invoice
            if ($this->validInvoiceDetail($invoiceDetail)) {
                $invoiceDetail = $this->createInvoiceDetail($invoiceDetail, $newInvoice->id);
                $invoiceDetail->save();
            } else {
                DB::rollback();
                return $this->jsonInvalidDetail($invoiceDetail);
            }
        }
        DB::commit();
        return $this->jsonInvoiceStored($newInvoice);
    }

    private function createInvoiceHeader($data) {
        $newInvoice = new Invoice;
        $newInvoice->invoicenumber_mobile = $data['invoicenumber'];
        $newInvoice->customername = $data['customername'];
        $newInvoice->invoicedate = $data['invoicedate'];
        $newInvoice->invoicetotal = $data['invoicetotal'];

        return $newInvoice;
    }

    private function createInvoiceDetail($itemProduct, $invoiceId) {
        $invoiceDetail = new InvoiceDetail;
        $invoiceDetail->invoice_id = $invoiceId;
        $invoiceDetail->product_id = Product::where('barcode', $itemProduct['barcode'])->first()->id;
        $invoiceDetail->amount = $itemProduct['amount'];
        $invoiceDetail->price = $itemProduct['price'];
        return $invoiceDetail;
    }

    private function validInvoiceHeader($data) {
        $invoiceValidator = Validator::make($data, [
                    'inovicenumber_mobile' => 'required',
                    'customername' => 'required',
                    'invoicedate' => 'required',
                    'invoicetotal' => 'required'
                        ]
        );

        return $invoiceValidator->fails();
    }

    private function validInvoiceDetail($invoiceDetail) {
        $productValidator = Validator::make($invoiceDetail, [
                    'barcode' => 'required',
                    'product_id' => 'required',
                    'amount' => 'required',
                    'price' => 'required'
                        ]
        );

        return $productValidator->fails();
    }

    private function jsonInvalidHeader($newInvoice) {
        return response()->json([
                    "message" => "Invalid Invoice Header",
                    "object" => $newInvoice,
                        ], 400);
    }

    private function jsonInvalidDetail($invoiceDetail) {
        return response()->json([
                    "message" => "Invalid Invoice detail",
                    "object" => $invoiceDetail,
                        ], 400);
    }

    private function jsonInvoiceStored($newInvoice) {
        return response()->json([
                    "message" => "Invoice record created",
                    "invoiceNum" => $newInvoice->invoicenumber_mobile
                        ], 201);
    }

}
