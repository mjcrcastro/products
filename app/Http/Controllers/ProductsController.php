<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Response;

class ProductsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //Shows list of products, data is handled by a datatables via Json
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //

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

        //if valid data, create a new product entry
        $product = Product::create($request->all());
        //and return to the index
        return redirect()->route('products.index')
                        ->with('message', 'Producto ' . $product->description . ' Registrado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product) {
        //
         return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) {
        //
        if (is_null($product)) { //if no product is sent by form
            return redirect()->route('products.index'); //go to previous page
        }

        //otherwise display the shop editor view
        return view('products.edit', compact('product'));
        // End of actual code to execute
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product) {
        //

        $validator = Validator::make($request->all(), [
                    'barcode' => ['required', Rule::unique('products')->ignore($product->id)],
                    'description' => ['required', Rule::unique('products')->ignore($product->id)],
                     'price' => ['required','numeric']
                        ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $product->update($request->all());
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) {
        //
        Product::find($product->id)->delete();
        return redirect()->route('products.index');
    }

    public function productsAjax(Request $request) {
        //returns list of products
        //if ($request->ajax()) {//return json data only to ajax queries 
        $filter = $request->search['value'];

        $columns = array('barcode', 'description', 'price'); //array to sort by, from incoming ajax request
        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        $orderedColumn = $request->order[0]['column'] == 0? 'description' : $columns[$request->order[0]['column'] - 1];

        $product = Product::where($orderedColumn, 'LIKE', "%" . $filter . "%")
                ->orderBy($orderedColumn, $request->order[0]['dir']) //order[0]['column'] contains the column to be ordered as selected on the US and sent to Laravel by DataTables vi ajax
                ->get();

        $response['draw'] = $request->get('draw');

        $response['recordsTotal'] = Product::all()->count();

        $response['recordsFiltered'] = $product->count();

        $response['data'] = array_slice($product->toArray(), $request->get('start'), $request->get('length'));

        return response()->json($response);
        //}
    }

    public function allProductsJson() {
        $products = Product::all();
        return response()->json($products);
    }
    
    
        
    public function exportToCsv() {
        

            $result = DB::table('products')->get();
            
            $handle = fopen("products.csv", 'w+');

            foreach ($result as $row) {
                
                fputcsv($handle, (array)$row);  //fputcsv requires array
                // as second parameter
            }
            fclose($handle);
            $headers = array(
            'Content-Type' => 'Content-type: text/plain',
        );
            return Response::download('products.csv', 'products.csv', $headers );
        }
        
    
}
