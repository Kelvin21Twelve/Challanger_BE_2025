<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Gallery_img;
use File;


class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

   
    public function index(Request $request)
    {   
        $perPage = 8;
        $datas= Gallery_img::orderBy('id', 'DESC')->paginate($perPage);
        return view('pages.image_list',compact('datas'));
    }

    public function create(Request $request)
    {  
        
        // echo "<pre>"; print_r($datas); die();
        $datas = "";
        if($request['id']){
            // echo "hgf";die();
            $id = en_de_crypt($request['id'], "d");
            $datas = Gallery_img::findorfail($id);

            return view('pages.add_image', ["datas" => $datas]);
        }else{
            $datas = Gallery_img::get();
            return view('pages.add_image');
        }
        
    }


    public function store(Request $request)
    {  
        // echo "<pre>"; print_r($request->all()); die();
        $user = Auth::user();
        if (!empty($request["id"])) {
            // echo"asdf";exit;
            $fileName = "false";
            $id = en_de_crypt($request['id'], "d");
            if ($request->hasFile('g_image')) {
                $file = $request->file('g_image');
                $fileName =uniqid("g_image").'.png';
                $destinationPath = public_path() . '/image/';
                $file->move($destinationPath, $fileName);
            }else{
                if ($request["g_img"]) {
                    $fileName =$request["g_img"];
                }
            }
            
            $data= Gallery_img::findorfail($id);
            $data->image =$fileName;
            // dd($data);
            if($data->update()){
                return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Image updted successfully!','redirect_url'=>'/add-image']);

            }else{
                return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Failed!','redirect_url'=>'/add-image']);

            }
            
        }else{
// echo"sdfsdfdsfsdf";die;
            $fileName = "false";
            if ($request->hasFile('g_image')) {
                $file = $request->file('g_image');
                $fileName =uniqid("g_image").'.png';
                $destinationPath = public_path() . '/image/';
                $file->move($destinationPath, $fileName);

                $data= new Gallery_img;
                $data->image=$fileName;
                $data->save(); 
                return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Image updted successfully!','redirect_url'=>'/add-image']);
            }else{
                return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Failed!','redirect_url'=>'/add-image']);

            }

        }
        
  
    }

    public function s_delete(Request $request){
        $id = en_de_crypt($request['id'], "d");
        echo"<pre>";print_r($id);exit;
        $datas = Gallery_img::find( $id );
        $destinationPath = public_path() . '/image/'.$datas['image'];
        if(File::exists($destinationPath)) {
            File::delete($destinationPath);
        }

        if($datas ->delete()){
            return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Image updted successfully!','redirect_url'=>env('APP_URL').'/add-image']);


        }else{
            return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Failed!','redirect_url'=>env('APP_URL').'/add-image']);

        }
    }

    
}
