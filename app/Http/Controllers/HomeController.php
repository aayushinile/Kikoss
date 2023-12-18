<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourAttribute;
use App\Models\CallbackRequest;
use App\Models\VirtualTour;
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
     * Showing Tour Booking Request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Tourrequests = TourBooking::where('status',0)->orderBy('id','DESC')->paginate(10);
        return view('admin.home',compact('Tourrequests'));
    }
    
    public function users()
    {
        $datas = User::where('type',2)->orderBy('id','DESC')->paginate(10);
        return view('admin.users',compact('datas'));
    }
    
    public function userDetail($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $data = User::where('id',$id)->first();
        return view('admin.user-detail',compact('data'));
    }
    
    public function AddTour()
    {
        $data=null;
        return view('admin.add-edit-tour',compact('data'));
    }
    
    public function EditTour($id)
    {
        $id = encrypt_decrypt('decrypt',$id);
        $data = Tour::where('id',$id)->first();
        return view('admin.add-edit-tour',compact('data'));
    }
    
    public function EditVirtualTour($id)
    {
        $id = encrypt_decrypt('decrypt',$id);
        $data = VirtualTour::where('id',$id)->first();
        return view('admin.add-edit-virtual-tour',compact('data'));
    }
    
    public function DeleteTour($id)
    {
        $id = encrypt_decrypt('decrypt',$id);
        $data = Tour::where('id',$id)->update(['status'=>3]);/*3:Delete*/
        return redirect()->back()->with('success', 'Tour deleted successfully');
    }
    
    public function DeleteVirtualTour($id)
    {
        $id = encrypt_decrypt('decrypt',$id);
        $data = VirtualTour::where('id',$id)->update(['status'=>3]);/*0:Pending,1:Approved, 3:Delete*/
        return redirect()->back()->with('success', 'Virtual Tour deleted successfully');
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
                'total_people' => 'required',
                'duration' => 'required',
                'age_11_price' => 'required',
                'age_60_price' => 'required',
                'under_10_age_price' => 'required',
                'description' => 'required|string|min:3|max:1000',
                'cancellation_policy' => 'required|min:3|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
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
            return redirect('manage-booking')->with('success', 'Tour Created successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function UpdateTour(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:1',
                'name' => 'required|string|max:255|min:1',
                'total_people' => 'required',
                'duration' => 'required',
                'age_11_price' => 'required',
                'age_60_price' => 'required',
                'under_10_age_price' => 'required',
                'description' => 'required|string|min:3|max:1000',
                'cancellation_policy' => 'required|min:3|max:1000',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            
            $tour = Tour::find($request->pid);
            $tour->title = $request->title;
            $tour->name = $request->name;
            $tour->total_people = $request->total_people;
            $tour->duration = $request->duration;
            $tour->age_11_price = $request->age_11_price;
            $tour->age_60_price = $request->age_60_price;
            $tour->under_10_age_price = $request->under_10_age_price;
            $tour->description = $request->description;
            $tour->cancellation_policy = $request->cancellation_policy;
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
            $tour->save();
            return redirect('manage-booking')->with('success', 'Tour Updated successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function SaveVirtualTour(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:1',
                'price' => 'required',
                'minute' => 'required',
                'description' => 'required',
                'cencellation_policy' => 'required',
                'audio' => 'required|max:5120',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $Tour = new VirtualTour;
            if ($file=$request->file('audio')){
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->audio_file = $name;
            }
            if ($files=$request->file('thumbnail')){
                $destination_file = public_path('upload/virtual-thumbnail');
                $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $files->extension();
                $files->move($destination_file, $name_file);
                $Tour->thumbnail_file = $name_file;
            }
            $Tour->name = $request->name;
            $Tour->price = $request->price;
            $Tour->minute = $request->minute;
            $Tour->description = $request->description;
            $Tour->cencellation_policy = $request->cencellation_policy;
            $Tour->status = 1;
            $Tour->save();
            
            return redirect('manage-virtual-tour')->with('success', 'Virtual Tour Created successfully');
            
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function UpdateVirtualTour(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:1',
                'price' => 'required',
                'minute' => 'required',
                'description' => 'required',
                'cencellation_policy' => 'required',
                'audio' => 'max:5120',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $Tour = VirtualTour::find($request->pid);
            if ($file=$request->file('audio')){
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->audio_file = $name;
            }
            if ($files=$request->file('thumbnail')){
                $destination_file = public_path('upload/virtual-thumbnail');
                $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $files->extension();
                $files->move($destination_file, $name_file);
                $Tour->thumbnail_file = $name_file;
            }
            
            $Tour->name = $request->name;
            $Tour->price = $request->price;
            $Tour->minute = $request->minute;
            $Tour->description = $request->description;
            $Tour->cencellation_policy = $request->cencellation_policy;
            $Tour->save();
            return redirect('manage-virtual-tour')->with('success', 'Virtual Tour Updated successfully');
            
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    
    public function ManageBooking()
    {
        try {
            $Tourrequests = TourBooking::where('status',0)->orderBy('id','DESC')->paginate(10);
            $tours = Tour::where('status',1)->orderBy('id','DESC')->get();
            return view('admin.manage-booking',compact('Tourrequests','tours'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    public function ManageVirtualTour()
    {
        try {
            $tours = VirtualTour::where('status',1)->orderBy('id','DESC')->get();
            $bookings = TourBooking::where('tour_type',2)->where('status',0)->orderBy('id','DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            return view('admin.manage-virtual-tour',compact('tours','bookings'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    public function AddVirtualTour()
    {
        try {
            $data = null;
            return view('admin.add-edit-virtual-tour',compact('data'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Callback request listing */
    public function InquiryRequest()
    {
        try {
            $datas = CallbackRequest::where('status',1)->orderBy('id','DESC')->paginate(10);
            return view('admin.tour-inquiry-request',compact('datas'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    public function ViewTransactionHistory()
    {
        try {
            $datas=Tour::where('status',1)->orderBy('id','DESC')->paginate(10);
            return view('admin.view-transaction-history',compact('datas'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Accept Booking Request*/
    public function AcceptTourBooking($id)
    {
        try {
            $datas=TourBooking::where('id',$id)->update(['status'=>1]);/*0:pending,1:accept,2:reject */
            return redirect()->back();
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
     /*Reject Booking Request*/
    public function RejectTourBooking($id)
    {
        try {
            $datas=TourBooking::where('id',$id)->update(['status'=>2]);/*0:pending,1:accept,2:reject */
            return redirect()->back();
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
}