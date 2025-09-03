<?php

namespace App\Http\Controllers\API;

use App\Memo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemoController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return Memo::all();
    }

    public function show($id) {
        $data = Memo::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $username = $this->request->user()->name;
        $notes = $request->input("note");
        $date = $request->input("date");

        $model = new Memo();
        $model->user_id = $user_id;
        $model->username = $username;
        $model->note = $notes;
        $model->date = $date;
        $model->save();
        return response()->json(['success' => true, 'data' => $model]);
    }

    public function update(Request $request, $id) {
        $model = Memo::find($id);
        if ($model) {
            $model->update($request->all());
            return response()->json(['success' => true, 'data' => $model]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

}
