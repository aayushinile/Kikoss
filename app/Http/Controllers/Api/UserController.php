<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;
use App\Models\Event;
use App\Models\Tour;
use App\Models\TourAttribute;
use App\Models\CallbackRequest;
use App\Models\TourBooking;
use App\Models\VirtualTour;
use App\Models\TaxiBooking;
use App\Models\PhotoBooth;
use App\Models\PhotoBoothMedia;

use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DateTime;

class UserController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return errorMsg($validator->errors()->first());
            }
           
            if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                $user = Auth::user();
                $token = $user->createToken('kikos')->plainTextToken;
                $success['token'] = $token;
                $success['userid'] = $user->id;
                $success['fullname'] = $user->fullname;
                $success['email'] = $user->email;
                $success['mobile'] = ($user->mobile) ?? '';
                $success['status'] = $user->status;
                if ($user->status == 0) { /*Checking User is active or in-active 0:unapproved 1:Approved bu admin */
                    return response()->json(["status" => false, "message" => "your account is deactivated by administrator!"]);
                }
                return response()->json(["status" => true, "message" => "Logged in successfully.", "data" => $success]);
            } else {
                return response()->json(["status" => true, "message" => "Unauthorised.", "data" => ''] ,401);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    // function for logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(["status" => true, "message" => "User successfully logout."]);
    }
    
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
    */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'mobile' => 'required|unique:users',
                'password' => 'required|min:8',
                'c_password' => 'required|min:8|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()],404);
            }
            
            $input = $request->all();
            
            $input['password'] = Hash::make($input['password']);
            //When Create a new user then user status is by default active
            $user = User::create($input);
            $token = $user->createToken('kikos')->plainTextToken;
            $success['token'] = $token;
            $success['userId'] = $user->id;
            $success['fullname'] = $user->fullname;
            $success['email'] = $user->email;
            $success['mobile'] = ($user->mobile) ?? '';
            $success['status'] = $user->status;
            return response()->json(["status" => true, "message" => "Registered successfully.", "data" => $success]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*User details */
    public function userDetails()
    {
        try {
            $user = Auth::user();
            $token = $user->createToken('kikos')->plainTextToken;
            $success['token'] = $token;
            $success['userid'] = $user->id;
            $success['fullname'] = $user->fullname;
            $success['email'] = $user->email;
            $success['mobile'] = ($user->mobile) ?? '';
            $success['status'] = $user->status;
            $success['profile'] = $user->user_profile ? asset('public/upload/profile/'.$user->user_profile) : '';;
            $success['confirmed_tour'] = 02;
            $success['virtual_audio_purchased'] = 06;
            $success['total_purchased_video'] = 40;
            $success['total_purchased_photo'] = 20;
            return response()->json(["status" => true, "message" => "Profile.", "data" => $success]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*User Update profile*/
    public function updateProfile(Request $request) 
    {
        try {
            $user = User::where('id',Auth::user()->id)->first();
            $validator = Validator::make($request->all() , [
                'fullname' => 'required|string|max:255',
                'email' =>   ['required','email',Rule::unique('users')->ignore($user->id)],
                'mobile' => ['required',Rule::unique('users')->ignore($user->id)],
                'mobile' => ['required'],
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails())
            {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()],404);
            }
            
            if ($file = $request->file('image')) {
                if(!empty($user->user_profile))
                {
                    if (file_exists(public_path('upload/profile/'.$user->user_profile))) {
                        unlink(public_path('upload/profile/'.$user->user_profile));/*Delete Photo booth image from file*/
                    }
                }
                
                $destination = public_path('upload/profile');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $user->user_profile = $name;
            }
            
            $user->fullname = $request->fullname;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $val = $user->save();
            if(!empty($val))
            {
                $datas = User::where('id',Auth::user()->id)->first();
                $success['userid'] = $user->id;
                $success['fullname'] = $user->fullname;
                $success['email'] = $user->email;
                $success['mobile'] = ($user->mobile) ?? '';
                $success['status'] = $user->status;
                $success['user_profile'] = $user->user_profile ? asset('public/upload/profile/'.$user->user_profile) : '';
                $data['status'] =true;
                $data['message'] = 'Profile Details Update Successfully';
                $data['data'] = $success;
                return response()->json($data);
            }else{
                $data['status'] = true;
                $data['message'] = 'Somethings went wrong';
                $data['data'] = '';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing tour data with or without login */
    public function home() 
    {
        try {
            $tours = Tour::where('status',1)->orderBy('id','ASC')->get();
            if(count($tours) > 0){
                $response = array();
                foreach ($tours as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['title'] = $value->title;
                    $temp['name'] = $value->name;
                    $temp['age_11_price'] = $value->age_11_price;
                    $temp['age_60_price'] = $value->age_60_price;
                    $temp['under_10_age_price'] = $value->under_10_age_price;
                    $temp['duration'] = $value->duration;
                    $temp['total_people_occupancy'] = $value->total_people;
                    $temp['description'] = $value->description;
                    $temp['cancellation_policy'] = $value->cancellation_policy;
                    $image = TourAttribute::where('tour_id',$value->id)->first();
                    $tourImage = asset('public/upload/tour-thumbnail/'.$image->attribute_name);/*First image of tour*/
                    $temp['images'] = $tourImage;
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Home data';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Legal links of about us, Contact us */
    public function legal_links() 
    {
        try {
            $data['status'] = true;
            $data['about_us'] = 'https://kikostoursoahu.com/';
            $data['contact_us'] = 'https://kikostoursoahu.com/';
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    public function calendarEvents() 
    {
        try {
            $booked_events = Event::where('title','Booked Event')->get();
            if(count($booked_events) > 0){
                $response = array();
                foreach ($booked_events as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['title'] = $value->title;
                    $temp['date'] = $value->start;
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            $not_available = Event::where('title','Not Available')->get();
            if(count($not_available) > 0){
                $response1 = array();
                foreach ($not_available as $key => $value) {
                    $temp1['id'] = $value->id;
                    $temp1['title'] = $value->title;
                    $temp1['date'] = date('Y-m-d', strtotime($value->start));
                    $response1[] = $temp1;
                }
            }else{
                $response1 = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Event data';
            $data['booked_events'] = $response;
            $data['not_available'] = $response1;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing tour data with or without login */
    public function tour_detail(Request $request) 
    {
        try {
            $tour = Tour::where('id',$request->tour_id)->first();/*Get tour data accoding to id */
            if(!empty($tour)){
                $tourImage = array();
                $temp['id'] = $tour->id;
                $temp['title'] = $tour->title;
                $temp['name'] = $tour->name;
                $temp['age_11_price'] = $tour->age_11_price;/* Tour Price for 11 years+ per person */
                $temp['age_60_price'] = $tour->age_60_price;/* Tour Price for 60 years+ per person */
                $temp['under_10_age_price'] = $tour->under_10_age_price;/* Tour Price for under 10 years per person */
                $temp['duration'] = $tour->duration;/* Tour Time */
                $temp['total_people_occupancy'] = $tour->total_people;/* Total person booked for tour */
                $temp['description'] = $tour->description;
                $temp['cancellation_policy'] = $tour->cancellation_policy;
                $temp['short_description'] = $tour->short_description;
                $temp['what_to_bring'] = $tour->what_to_bring;
                $temp['tour_date_time'] = '19 October, 2023 Monday';
                $images = TourAttribute::where('tour_id',$tour->id)->get();
                foreach ($images as $key => $val) {
                    $tourImage[] = asset('public/upload/tour-thumbnail/'.$val->attribute_name);
                }
                
                $temp['images'] = $tourImage;
            }else{
                $temp = '';
            }
            
            $data['status'] = true;
            $data['message'] = 'Tour Detail';
            $data['data'] = $temp;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*User forgot password (Otp send via email)*/
    public function forgetpassword(Request $request) 
    {
        try {
            $data=array();
            $validator = Validator::make($request->all() , [
                'email' => 'required|email',
            ]);
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            $email = $request->email;
            $user = User::where('email',$email)->where('status',1)->orderBy('id','DESC')->first();
            if(!empty($user))
            {
                $exist = Otp::where('email',$email)->orderBy('id','DESC')->first();
                if(!empty($exist))
                {
                    $code = rand(1000,9999);/*Four digit otp code*/
                    $users = Otp::where('email',$email)->update(['otp'=>$code]);
                    $data['status'] = true;
                    $data['message'] = 'Verification code has been sent';
                    $data['code'] = $code;
                    $data['email'] = $user->email;
                    return response()->json($data);
                }else{
                    $OTP = new Otp;
                    $code = rand(1000,9999);
                    $OTP->email = $email;
                    $OTP->otp = $code;
                    $OTP->save();
                    $data['status'] = true;
                    $data['message'] = 'Verification code has been sent';
                    $data['code'] = $code;
                    $data['email'] = $user->email;
                    return response()->json($data);
                }
            }else{
                $data['status'] = false;
                $data['message'] = 'Please contact to admin.';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Check Email auth  (Otp send via email)*/
    public function send_otp(Request $request) 
    {
        try {
            $data=array();
            $validator = Validator::make($request->all() , [
                'email' => 'required|email',
            ]);
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            $email = $request->email;
            $user = User::where('email',$email)->orderBy('id','DESC')->first();
            
            if(empty($user))
            {
                
                $exist = Otp::where('email',$email)->orderBy('id','DESC')->first();
                if(!empty($exist))
                {
                    $code = rand(1000,9999);/*Four digit otp code*/
                    $users = Otp::where('email',$email)->update(['otp'=>$code]);
                    $data['status'] = true;
                    $data['message'] = 'Verification code has been sent';
                    $data['code'] = $code;
                    $data['email'] = $email;
                    return response()->json($data);
                }else{
                    
                    $OTP = new Otp;
                    $code = rand(1000,9999);
                    $OTP->email = $email;
                    $OTP->otp = $code;
                    $OTP->save();
                    $data['status'] = true;
                    $data['message'] = 'Verification code has been sent';
                    $data['code'] = $code;
                    $data['email'] = $email;
                    return response()->json($data);
                }
            }else{
                $data['status'] = false;
                $data['message'] = 'Email is allready register.';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*User verified otp(Email via) */
    public function verifyotp(Request $request) 
    {
        try {
            $data=array();
            $otp = $request->otp;
            $validator = Validator::make($request->all() , [
                'email' => 'required|email',
                'otp' => 'required|digits:4|numeric',
            ]);
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
        
            $user = Otp::where('email',$request->email)->orderBy('id','DESC')->first();
            if(!empty($user))
            {
                $user_detail = User::where('email',$request->email)->where('status',1)->orderBy('id','DESC')->first();
                if(!empty($user_detail))
                {
                    $remember_token = $user_detail->remember_token;
                }else{
                    $remember_token = '';
                }
                if($user->otp == $otp)
                {
                    $validTill = strtotime($user->updated_at) + (24*60*60);
                    if (strtotime("now") > $validTill) {
                        $data['status']=true;
                        $data['message'] = "OTP has expired.";
                        $data['user_id'] = '';
                        $data['token'] = '';
                        return response()->json($data);
                    }else{
                        $data['status']=true;
                        $data['message']="OTP verified";
                        $data['user_id'] = $user->id;
                        $data['token'] = $remember_token;
                        return response()->json($data);
                    }
                }else{
                    $data['status']=false;
                    $data['message']="Please enter valid OTP";
                    $data['user_id'] = '';
                    $data['token'] = '';
                    return response()->json($data);
                }
            }else{
                $data['status'] = false;
                $data['message'] = 'Otp does not exits';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /* Function for change password with login or without login*/
    public function change_password(Request $request) 
    {
        try {
            $data=array();
            $new_password = $request->new_password;
            $old_password = $request->old_password;
            if(!empty($old_password))
            {
                $validator = Validator::make($request->all() , [
                    'email' => 'required',
                    'old_password' => 'required|min:8',
                    'new_password' => 'required|min:8',
                    'confirm_new_password' => 'required|same:new_password',
                ]);
            }else{
                $validator = Validator::make($request->all() , [
                    'email' => 'required',
                    'new_password' => 'required|min:8',
                    'confirm_new_password' => 'required|same:new_password',
                ]);
            }
            
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            
            if(!empty($old_password))
            {
                /*Checking old password is same or not */
                $userdata = User::where('email',$request->email)->where('status',1)->first();
                if ((Hash::check($request->old_password, $userdata->password)) == false) {
                    $arr = array("status" => false, "message" => "Check your old password.");
                    return response()->json($arr);
                }
            }

            $update =array('password'=>Hash::make($new_password));
            $id = User::where('email',$request->email)->where('status',1)->update($update);
            if(!empty($id))
            {
                $data['status']=true;
                $data['message']="Password change successfully";
                return response()->json($data);
            }else{
                $data['status'] = false;
                $data['message'] = 'Something went wrong!';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /* Send a request to admin accoding to tour id*/
    public function callback_request(Request $request) 
    {
        try {
            $data=array();
            $validator = Validator::make($request->all() , [
                'tour_id' => 'required|integer',
                'name' => 'required|string|max:255|min:1',
                'mobile' => 'required',
                'timezone' => 'required',
                'preferred_time' => 'required',/*Date and Time*/
                'note' => 'required|min:3|max:1000',
            ]);
            
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            
            $callback = new CallbackRequest;
            $callback->tour_id = $request->tour_id;
            $callback->name = $request->name;
            $callback->mobile = $request->mobile;
            $callback->timezone = $request->timezone;
            $callback->preferred_time = $request->preferred_time;
            $callback->note = $request->note;
            $callback->status = 1;
            $callback->save();
            
            $data['status']=true;
            $data['message']="Request Send";
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /* Booking tour*/
    public function bookingTour(Request $request) 
    {
        try {
            $data=array();
            $validator = Validator::make($request->all() , [
                'tour_id' => 'required|integer',
                'tour_type' => 'required|string|max:255|min:1',
                'booking_date' => 'required',
                'no_adults' => 'required',
                'no_senior_citizen' => 'required',
                'no_childerns' => 'required',
                'adults_amount' => 'required',
                'senior_amount' => 'required',
                'childrens_amount' => 'required',
            ]);
            
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            $booking_id = random_alphanumeric();
            $user = Auth::user();
            $booking = new TourBooking;
            $booking->booking_id = $booking_id;
            $booking->tour_id = $request->tour_id;
            $booking->tour_type = $request->tour_type;/*1-Normal Tour, 2:Virtual tour */
            $booking->user_id = $user->id;
            $booking->user_name = $user->fullname;
            $booking->booking_date = $request->booking_date;
            $booking->no_adults = $request->no_adults;/*Number of adults*/
            $booking->no_senior_citizen = $request->no_senior_citizen;/*Number of senior citizen*/
            $booking->no_childerns = $request->no_childerns;/*Number of Children*/
            $adults_amount = $request->adults_amount*$request->no_adults;
            $booking->adults_amount = $adults_amount;
            $senior_amount = $request->senior_amount*$request->no_senior_citizen;
            $booking->senior_amount = $senior_amount;
            $childrens_amount = $request->childrens_amount*$request->no_childerns;
            $booking->childrens_amount = $childrens_amount;
            $tax = 100;
            $booking->tax = $tax;
            $booking->total_amount = $adults_amount + $senior_amount + $childrens_amount + $tax;
            $booking->status = 0;
            $booking->save();
            
            $data['status']=true;
            $data['message']="Boooked successfully";
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing Virtual tour data with or without login */
    public function VirtualTourListing() 
    {
        try {
            $tours = VirtualTour::where('status',1)->orderBy('id','ASC')->get();//Get all datas of Virtual-Tour
            if(count($tours) > 0){
                $response = array();/*Store data an array */
                foreach ($tours as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['minute'] = $value->minute;
                    $temp['name'] = $value->name;
                    $temp['price'] = $value->price;
                    $temp['description'] = $value->description;
                    $temp['cancellation_policy'] = $value->cancellation_policy;
                    $temp['audio'] = asset('public/upload/virtual-audio/'.$value->audio_file);/*Audio file of virtual tour*/
                    $temp['thumbnail'] = asset('public/upload/virtual-thumbnail/'.$value->thumbnail_file);/*Thumbnail file of virtual tour*/
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Virtual tour listing';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Virtual tour Details  */
    public function VirtualTourDetail(Request $request) 
    {
        try {
            //Virtual detail accoding to virtual tour id
            $tour = VirtualTour::where('id',$request->id)->first();
            if(!empty($tour)){
                $tourImage = array();
                $temp['id'] = $tour->id;
                $temp['minute'] = $tour->minute;
                $temp['duration'] = $tour->duration ?? '';
                $temp['name'] = $tour->name;
                $temp['price'] = $tour->price;
                $temp['description'] = $tour->description;
                $temp['short_description'] = $tour->short_description?? '';
                $temp['cancellation_policy'] = $tour->cancellation_policy ?? '';
                $temp['uploaded_date'] = date('d M, Y, h:i:s a', strtotime($tour->created_at));
                $temp['purchase_user_count'] = 2.1.'M';
                $temp['purchase_date'] = date('d M, Y, h:i:s a', strtotime($tour->created_at));
                $temp['audio'] = asset('public/upload/virtual-audio/'.$tour->audio_file);/*Audio file of virtual tour*/
                if(isset($tour->trial_audio_file))
                {
                    $temp['trail_audio'] =  asset('public/upload/virtual-audio/'.$tour->trial_audio_file);/*Audio file of virtual tour*/
                }else{
                    $temp['trail_audio'] = '';/*Audio file of virtual tour*/
                }
                
                $temp['thumbnail'] = asset('public/upload/virtual-thumbnail/'.$tour->thumbnail_file);/*Thumbnail file of virtual tour*/
            }else{
                $temp = '';
            }
            
            $data['status'] = true;
            $data['message'] = 'Virtual Tour Detail';
            $data['data'] = $temp;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Taxi booking api  */
    public function bookingTaxi(Request $request) 
    {
        try {
            
            //Validation for Taxi booking
            $validator = Validator::make($request->all(), [
                'booking_date_time' => 'required',
                'fullname' => 'required|string|max:255',
                'pickup_location' => 'required',
                'pickup_lat_long'=> 'required',
                'drop_location'=> 'required',
                'drop_lat_long'=> 'required',
                'mobile'=> 'required',
                'hotel_name'=> 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()],404);
            }
            $user_id = Auth::user()->id;
            $user = Auth::user();
            /*Create taxi booking with booking id*/
            $booking_id = rand(10000000,99999999);/*Generate random booking  ID*/
            
            /*Calculate Distance in KM*/
            $pick_lat_lng = explode(',', $request->pickup_lat_long, 2);/*Expload the data */
            $drop_lat_long = explode(',', $request->drop_lat_long, 2);/*Expload the data */
            $distance = getDistanceFromLatLonInKm($pick_lat_lng[0], $pick_lat_lng[1], $drop_lat_long[0], $drop_lat_long[1]);
            $Distance = number_format((float)$distance, 2, '.', '') . " Km"; 
            $distance_int = round((int)$distance);
            
            $amount = $distance_int * 10;
            
            
            $bookingID = TaxiBooking::insertGetId([
                'booking_time' => $request->booking_date_time,/*Date and Time */
                'booking_date' => date('y-m-d', strtotime($request->booking_date_time)),/*Filter date and save */
                'user_id' => $user_id,
                'user_name' => $user->fullname,
                'booking_id' => $booking_id,
                'fullname' => $request->fullname,
                'pickup_location' => $request->pickup_location,
                'pickup_lat_long' => $request->pickup_lat_long,
                'drop_location' => $request->drop_location,
                'drop_lat_long' => $request->drop_lat_long,
                'mobile' => $request->mobile,
                'hotel_name' => $request->hotel_name,
                'distance' => $distance_int,
                'amount' => $amount,
                'status' => 0,
                'created_at' => date("Y-m-d h:i:s")
            ]);
            if($bookingID){
                $data['status'] = true;
                $data['message'] = 'Booking request send successfully';
            }else{
                $data['status'] = false;
                $data['message'] = 'Something went wrong';
            }
            
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing List of Booking Taxi */
    public function TaxiBookingListing() 
    {
        try {
            $user_id = Auth::user()->id;
            $user = Auth::user();
            $bookings = TaxiBooking::where('user_id',$user_id)->orderBy('id','ASC')->get();
            if(count($bookings) > 0){
                $response = array();
                foreach ($bookings as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['booking_id'] = $value->booking_id;
                    $temp['booking_time'] = date('d M, Y - g:i A', strtotime($value->booking_time));
                    $temp['user_id'] = $value->user_id;
                    $temp['user_name'] = $user->fullname;
                    $temp['user_profile'] = $user->profile ? asset('public/upload/photo-booth/'.$user->profile): '';
                    $temp['pickup_location'] = $value->pickup_location;
                    $temp['pickup_lat_long'] = $value->pickup_lat_long;
                    $temp['drop_location'] = $value->drop_location;
                    $temp['drop_lat_long'] = $value->drop_lat_long;
                    $temp['mobile'] = $value->mobile;
                    $temp['hotel_name'] = $value->hotel_name;
                    $temp['book_taxicol'] = $value->book_taxicol;
                    $temp['distance'] = $value->distance;
                    $temp['status'] = $value->status;
                    $temp['created_at'] = date('d M, Y - g:i A', strtotime($value->created_at));
                    $temp['TimeAgo'] = $value->created_at->diffForHumans();/*Calculate time ago*/
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Booking Listing';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing all Photo booth listing */
    public function PhotoBoothListing() 
    {
        try {
            $photos = PhotoBooth::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            if(count($photos) > 0){
                $response = array();/*Store data an array */
                foreach ($photos as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['tour_id'] = $value->tour_id;
                    $tour = Tour::where('id',$value->tour_id)->first();
                    $temp['tour_name'] = $tour->name;/*Tour Name*/
                    $temp['title'] = $value->title;/*Photo Booth Title*/
                    $temp['price'] = $value->price;/*Photo Booth Price*/
                    $temp['description'] = $value->description;
                    $temp['cancellation_policy'] = $value->cancellation_policy;
                    $image = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->first();
                    $temp['image'] = $image->media;/*Image file of Photo Booth */
                    $temp['image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();
                    $temp['video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();
                    $temp['purchase_image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();
                    $temp['purchase_video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Photo booth listing';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing all tour listing, count of bookrd tour */
    public function book_tour() 
    {
        try {
            
            $tours = Tour::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            if(count($tours) > 0){
                $response = array();/*Store data an array */
                foreach ($tours as $key => $tour) {
                    $temp['id'] = $tour->id;
                    $temp['title'] = $tour->title;
                    $temp['name'] = $tour->name;
                    $temp['age_11_price'] = $tour->age_11_price;/* Tour Price for 11 years+ per person */
                    $temp['age_60_price'] = $tour->age_60_price;/* Tour Price for 60 years+ per person */
                    $temp['under_10_age_price'] = $tour->under_10_age_price;/* Tour Price for under 10 years per person */
                    $temp['duration'] = $tour->duration;/* Tour Time */
                    $images = TourAttribute::where('tour_id',$tour->id)->first();
                    $temp['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            if(Auth::user()){
                $user = Auth::user();
                $bookings = TourBooking::where('tour_type',1)->whereIn('status',[1,2])->where('user_id',$user->id)->count();//Get count of Normal tour accoding to user id
                $CallbackRequest = CallbackRequest::where('tour_type',1)->where('user_id',$user->id)->count();//Get all datas of Normal tour
            }else{
                $bookings = 0;
                $CallbackRequest = 0;
            }
            
            $tours = Tour::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            
            $data['status'] = true;
            $data['message'] = 'Book tour listing';
            $data['data'] = $response;
            $data['confirmed_tour'] = $bookings;
            $data['free_callback_request'] = $CallbackRequest;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing all tour listing, count of booked tour */
    public function confirmed_tour(Request $request) 
    {
        try {
            $user = Auth::user();
            if($request->status == 1)
            {
                /*Accepted */
                $all_bookings = TourBooking::where('status',1)->where('user_id',$user->id)->where('tour_type',1)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }elseif($request->status == 2){
                /*Rejected */
                $all_bookings = TourBooking::where('status',2)->where('user_id',$user->id)->where('tour_type',1)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }else{
                /*All */
                $all_bookings = TourBooking::whereIn('status',[1,2])->where('user_id',$user->id)->where('tour_type',1)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }
            
            
            if(count($all_bookings) > 0){
                $response = array();/*Store data an array */
                foreach ($all_bookings as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['status_id'] = $value->status;
                    $temp['status'] = (($value->status == 1) ? "Accepted" : (($value->status == 2) ? "Rejected" : ""));
                    $temp['boooking_id'] = $value->booking_id;
                    $tour = Tour::where('id',$value->tour_id)->first();
                    $temp['tour_title'] = $tour->title;
                    $temp['cancellation_policy'] = $tour->cancellation_policy;
                    $temp['selectd_date'] = $tour->boking_date;
                    $temp['duration'] = $tour->duration;/* Tour Time */
                    $temp['no_of_adults'] = $value->no_adults;
                    $temp['no_of_senior'] = $value->no_senior_citizen;
                    $temp['no_of_children'] = $value->no_childerns;
                    $temp['total_amount'] = $value->total_amount;
                    $images = TourAttribute::where('tour_id',$value->id)->first();
                    $temp['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Book tour listing';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing all tour listing, count of booked tour */
    public function FreeCallbackRequest(Request $request) 
    {
        try {
            $user = Auth::user();
            if($request->date)
            {
                /*Accepted */
                $requests = CallbackRequest::where('preferred_time',$request->date)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }else{
                /*All */
                $requests = CallbackRequest::orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }
            
            
            if(count($requests) > 0){
                $response = array();/*Store data an array */
                foreach ($requests as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['tour_id'] = $value->tour_id;
                    $tour = Tour::where('id',$value->tour_id)->first();
                    $temp['tour_title'] = $tour->title;
                    $temp['date'] = date('d M, Y - g:i A', strtotime($tour->preferred_time));
                    $temp['duration'] = $tour->duration;
                    $images = TourAttribute::where('tour_id',$value->tour_id)->first();
                    $temp['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    $response = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Free Callback listing';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Photo booth Details  */
    public function PhotoBoothDetails(Request $request) 
    {
        try {
            //Photo booth detail accoding to booth id
            $value = PhotoBooth::where('id',$request->id)->first();
            if(!empty($value)){
                $tourImage = array();
                $temp['id'] = $value->id;
                $temp['tour_id'] = $value->tour_id;
                $tour = Tour::where('id',$value->tour_id)->first();
                $temp['tour_name'] = $tour->name;/*Tour Name*/
                $temp['title'] = $value->title;/*Photo Booth Title*/
                $temp['price'] = $value->price;/*Photo Booth Price*/
                $temp['description'] = $value->description;
                $temp['cancellation_policy'] = $value->cancellation_policy;
                $temp['uploaded_date'] = date('d M, Y - g:i A', strtotime($value->created_at));
                $images = PhotoBoothMedia::where('booth_id',$value->id)->get();/*Find all images and video accoding to photo booth id */
                foreach ($images as $key => $val) {
                    if($val->media_type == 'Image'){
                        $tourImage[] = asset('public/upload/photo-booth/'.$val->media);/*Images save in array */
                    }else{
                        $tourImage[] = asset('public/upload/video-booth/'.$val->media);/*Video save in array */
                    }
                }
                $temp['all_image_video'] = $tourImage;/*Image video listing of photo booth in array */
                $temp['image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();/*Count of all images photo booth  */
                $temp['video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();/*Count of all videos photo booth  */
            }else{
                $temp = '';
            }
            
            $data['status'] = true;
            $data['message'] = 'Photo Booth Detail';
            $data['data'] = $temp;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

}