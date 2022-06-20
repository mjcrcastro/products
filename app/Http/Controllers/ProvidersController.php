<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;
use Response;

class ProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('providers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('providers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Store new provider
         $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'address' => 'required',
                    'whatsapp' => 'required'
                        ]
        );

        if ($validator->fails()) {
            return redirect()->route('providers.create')
                            ->withErrors($validator)
                            ->withInput();
        }

        //if valid data, create a new product entry
        $provider = Provider::create($request->all());
        //and return to the index
        return redirect()->route('providers.index')
                        ->with('message', 'Proveedor ' . $provider->name . ' Registrado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        //
        return view('providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider)
    {
        //
         if (is_null($provider)) { //if no product is sent by form
            return redirect()->route('providers.index'); //go to previous page
        }

        //otherwise display the shop editor view
        return view('providers.edit', compact('provider'));
        // End of actual code to execute
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider)
    {
        //
         $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'address' => 'required',
                    'whatsapp' => 'required'
                        ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $provider->update($request->all());
        return redirect()->route('providers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        //
        Provider::find($provider->id)->delete();
        return redirect()->route('providers.index');
    }
    
    public function providersAjax(Request $request) {
        //returns list of products
        //if ($request->ajax()) {//return json data only to ajax queries 
        $filter = $request->search['value'];

        $columns = array('name', 'address', 'whatsapp','notes'); //array to sort by, from incoming ajax request
        //$orderedColumn calculates column name that needs to be sorted by Laravel before sending back to Datatables
        $orderedColumn = $request->order[0]['column'] == 0? 'name' : $columns[$request->order[0]['column'] - 1];

        $provider = Provider::where($orderedColumn, 'LIKE', "%" . $filter . "%")
                ->orderBy($orderedColumn, $request->order[0]['dir']) //order[0]['column'] contains the column to be ordered as selected on the US and sent to Laravel by DataTables vi ajax
                ->get();

        $response['draw'] = $request->get('draw');

        $response['recordsTotal'] = Provider::all()->count();

        $response['recordsFiltered'] = $provider->count();

        $response['data'] = array_slice($provider->toArray(), $request->get('start'), $request->get('length'));

        return response()->json($response);
        //}
    }
    
    public function providersSelect2Json(Request $request) {
        
        $term = ($request['term']?$request['term']:'');
        
        $providers = Provider::select('id','name as text')->where('name', 'like', '%' . $term . '%')->limit(10)->get(['id', 'text']);

        $results = array('results' => $providers);

        return response()->json($results);
    }
}
