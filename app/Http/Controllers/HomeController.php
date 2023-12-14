<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tour;
use App\Models\TourAttribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       
        return view('admin.home');
    }
    
    public function users()
    {
        $datas = User::where('type',2)->orderBy('id','DESC')->paginate(10);
        return view('admin.users',compact('datas'));
    }
    
    public function userDetail($id)
    {
        $id = encryptDecrypt('decrypt', $id);
        $data = User::where('id',$id)->first();
        return view('admin.user-detail',compact('data'));
    }
    
    public function AddTour()
    {
        $data=null;
        return view('admin.add-edit-tour',compact('data'));
    }
    
    public function tours()
    {
        $datas=Tour::where('status',1)->orderBy('id','DESC')->paginate(10);
        return view('admin.tours',compact('datas'));
    }
    
    public function SaveTour(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:1',
                'name' => 'required|string|max:255|min:1',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if($request->pid){
                $property = Tour::find($request->pid);
            }else{
                $TourID = Tour::insertGetId([
                    'title' => $request->title,
                    'name' => $request->name,
                    'total_people' => $request->total_people,
                    'duration' => $request->duration,
                    'age_11_price' => $request->age_11_price,
                    'age_60_price' => $request->age_60_price,
                    'under_10_age_price' => $request->under_10_age_price,
                    'description' => $request->description,
                    'cancellation_policy' => $request->cancellation_policy,
                    'status' => 1,
                ]);
                if ($files=$request->file('thumbnail')){
                    foreach ($files as $j => $file){
                        $destination = public_path('upload/tour-thumbnail');
                        $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                        $file->move($destination, $name);
                        $Attributes = TourAttribute::create([
                            'tour_id' => $TourID,
                            'attribute_type' => 'Image',
                            'attribute_name' => $name,
                        ]);
                    }
                }
            } 
            
            
            return redirect()->back()->with('success', 'Tour Created successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function ManageBooking()
    {
        $datas=Tour::where('status',1)->orderBy('id','DESC')->paginate(10);
        return view('admin.manage-booking',compact('datas'));
    }
    
    public function InquiryRequest()
    {
        $datas=Tour::where('status',1)->orderBy('id','DESC')->paginate(10);
        return view('admin.tour-inquiry-request',compact('datas'));
    }
    public function ViewTransactionHistory()
    {
        $datas=Tour::where('status',1)->orderBy('id','DESC')->paginate(10);
        return view('admin.view-transaction-history',compact('datas'));
    }
}