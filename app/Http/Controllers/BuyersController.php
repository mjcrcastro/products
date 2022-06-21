<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Response;

class BuyersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('buyers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('buyers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'buyer_name' => ['required', Rule::unique('buyers')]
                ]
        );

        if ($validator->fails()) {
            return redirect()->route('buyers.create')
                            ->withErrors($validator)
                            ->withInput();
        }

        //if valid data, create a new product entry
        $buyer = Buyer::create($request->all());
        //and return to the index
        return redirect()->route('buyers.index')
                        ->with('message', 'Producto ' . $buyer->buyer_name . ' Registrado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //
         return view('buyers.show', compact('buyer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $buyer
     * @return \Illuminate\Http\Response
     */
    public function edit(Buyer $buyer)
    {
        if (is_null($buyer)) { //if no product is sent by form
            return redirect()->route('buyers.index'); //go to previous page
        }

        //otherwise display the shop editor view
        return view('buyers.edit', compact('buyer'));
        // End of actual code to execute
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Buyer $buyer)
    {
        //
        $validator = Validator::make($request->all(), [
                    'buyer_name' => ['required', Rule::unique('buyers')->ignore($buyer->id)]
                        ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $buyer->update($request->all());
        return redirect()->route('buyers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Buyer $buyer)
    {
        Buyer::find($buyer->id)->delete();
        return redirect()->route('buyers.index');
    }
    
    public function buyersAjax(Request $request) {
        //returns list of products
        //if ($request->ajax()) {//return json data only to ajax queries 
        $filter = $request->search['value'];

        $columns = array('buyer_name'); //array to sort by, from incoming ajax request
        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        $orderedColumn = $request->order[0]['column'] == 0 ? 'buyer_name' : $columns[$request->order[0]['column'] - 1];

        $buyer = Buyer::where($orderedColumn, 'LIKE', "%" . $filter . "%")
                ->orderBy($orderedColumn, $request->order[0]['dir']) //order[0]['column'] contains the column to be ordered as selected on the US and sent to Laravel by DataTables vi ajax
                ->get();

        $response['draw'] = $request->get('draw');

        $response['recordsTotal'] = Buyer::all()->count();

        $response['recordsFiltered'] = $buyer->count();

        $response['data'] = array_slice($buyer->toArray(), $request->get('start'), $request->get('length'));

        return response()->json($response);
        //}
    }
    
    public function buyersSelect2Json(Request $request) {
        
        $term = ($request['term']?$request['term']:'');
        
        $buyers = Buyer::select('id','buyer_name as text')->where('buyer_name', 'like', '%' . $term . '%')->limit(10)->get(['id', 'text']);

        $results = array('results' => $buyers);

        return response()->json($results);
    }
}
