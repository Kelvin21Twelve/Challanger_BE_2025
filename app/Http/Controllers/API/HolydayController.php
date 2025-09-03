<?php

namespace App\Http\Controllers\API;

use App\Holyday;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HolydayController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return Holyday::all();
    }

    public function show($id) {
        $data = Holyday::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $holyday = new Holyday();
        $holyday->fill($request->all());
        $holyday->user_id = $user_id;
        $holyday->save();
        return response()->json(['success' => true, 'data' => $holyday]);
    }

    public function update(Request $request, $id) {
        $holyday = Holyday::find($id);
        if ($holyday) {
            $holyday->update($request->all());
            return response()->json(['success' => true, 'data' => $holyday]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }


    public function holyday_search(Request $request) {
        try 
        {

            $start_date_search = $request->input("start_date_search");
            $end_date_search = $request->input("end_date_search");

            $search_data = Holyday::where('is_delete','=',0)->get();

            if (!empty($start_date_search)) {
               
               $search_data = $search_data->where('start_date','>=',$start_date_search);
            }

            if (!empty($end_date_search)) {
               
               $search_data = $search_data->where('end_date','<=',$end_date_search);
            }

             //dd($search_data->toSql(),$search_data->getBindings());
 
        return response()->json(['success' => true,'holy_data' => $search_data]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
