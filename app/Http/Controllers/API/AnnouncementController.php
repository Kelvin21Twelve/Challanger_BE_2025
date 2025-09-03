<?php

namespace App\Http\Controllers\API;

use App\Announcement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return Announcement::all();
    }

    public function show($id) {
        $data = Announcement::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $announcement = new Announcement();
        $announcement->fill($request->all());
        $announcement->user_id = $user_id;
        $announcement->save();
        return response()->json(['success' => true, 'data' => $announcement]);
    }

    public function update(Request $request, $id) {
        $announcement = Announcement::find($id);
        if ($announcement) {
            $announcement->update($request->all());
            return response()->json(['success' => true, 'data' => $announcement]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

}
