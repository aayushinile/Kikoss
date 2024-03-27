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
use App\Models\Notification;
use App\Models\TimeZone;
use App\Models\TaxiBooking;
use App\Models\Master;
use App\Models\PhotoBooth;
use App\Models\BookingPhotoBooth;
use App\Models\PhotoBoothMedia;
use App\Models\PaymentDetail;
use Mail;
use App\Mail\RegisterMail;
use App\Mail\SendOTPMail;
use App\Mail\TaxiBookingMail;
use App\Mail\freeCallBackMail;
use App\Mail\PhotoBoothBookingadminMail;
use App\Mail\PhotoBoothBookingUserMail;
use App\Mail\TaxiBookingAdminMail;
use App\Mail\TourBookingAdminMail;
use App\Mail\TourBookingUserMail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use DateTime;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Models\TaxiBookingEvent;
use Illuminate\Support\Facades\Http;
use Eluceo\iCal\Domain\Entity\Calendar;
use GuzzleHttp\Client;


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
                $data=array('device_token'=>request('device_token'));
                User::where('id', Auth::user()->id)->update($data);
                $user = Auth::user();
                $token = $user->createToken('kikos')->plainTextToken;
                $success['token'] = $token;
                $success['userid'] = $user->id;
                $success['fullname'] = $user->fullname;
                $success['email'] = $user->email;
                $success['mobile'] = ($user->mobile) ?? '';
                $success['status'] = $user->status;
                if ($user->status == 0) { /*Checking User is active or in-active 0:unapproved 1:Approved by admin */
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
            $success['mobile'] = '+1'.($user->mobile) ?? '';
            $success['status'] = $user->status;
            
            /*User Mail*/
            $mailData = [
                'name'  => $user->fullname
            ];
            Mail::to($user->email)->send(new RegisterMail($mailData));
            
            
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
            $success['profile'] = $user->user_profile ? asset('public/upload/profile/'.$user->user_profile) : '';
            $bookings = TourBooking::where('tour_type',1)->whereIn('status',[1,2])->where('user_id',$user->id)->count();//Get count of Normal tour accoding to user id
            $virtual_bookings = TourBooking::where('tour_type',2)->whereIn('status',[1,2])->where('user_id',$user->id)->count();//Get count of virtual tour accoding to user id
            $image_count = BookingPhotoBooth::where('userid',$user->id)->sum('image_count');//Get count of virtual tour accoding to user id
            $video_count = BookingPhotoBooth::where('userid',$user->id)->sum('video_count');//Get count of virtual tour accoding to user id
            $success['confirmed_tour'] = $bookings;
            $success['virtual_audio_purchased'] = $virtual_bookings;
            $success['total_purchased_video'] = $image_count;
            $success['total_purchased_photo'] = $video_count;
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
                //'email' =>   ['required','email',Rule::unique('users')->ignore(Auth::user()->id)],
                'mobile' => ['required',Rule::unique('users')->ignore($user->id)],
                'image' => ['mimes:jpeg,png,jpg,max:1024'],
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
                    $temp['same_for_all'] = $value->same_for_all;
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
                /*$push_message = $message; 
                // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
                $device_token = $user->firebase_token;
                $img='';
                $type=''; 
                $id='';
                $title= $user->first_name.' '.$user->last_name;
                $id1='';
                $sound ='default';
                $serverKey= 'AAAASwFiVeM:APA91bHxRaPbKYx4krs489Y1sjMV6uau2wff1xrwa36JoyJOGdwJPGtdbk6XER6qn2XLZhgom8KdyUNVdhQZOfXZUyJLWdKnov9VcHSlRBM0NTMBT1ZeI498SoJCNt1sN36Rx2IkTNBi';
                $check=$this->send_notification($serverKey,$push_message,$device_token,$title);*/
            $booked_events = Event::where('title','Booked Tour')->get();
            if(count($booked_events) > 0){
                $response = array();
                foreach ($booked_events as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['title'] = $value->title;
                    $temp['date'] = date('Y-m-d', strtotime($value->start));
                    $temp['color'] = $value->color;
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
                    $temp1['color'] = $value->color;
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
            $tax = Master::where('id','!=',null)->first();
            if(!empty($tour)){
                $tourImage = array();
                $temp['id'] = $tour->id;
                $temp['title'] = $tour->title;
                $temp['name'] = $tour->name;
                $temp['same_for_all'] = $tour->same_for_all;
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
                $temp['tax'] = $tax->tax ?? '';
                $temp['seat_available'] = 5;
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
                    $mailData = 
                    [
                        'body' => 'You are receiving this email because you have registered on our Kikos platform',
                        'code'  => 'Your otp is '. $code,
                        'email'  => $email
                    ];
                    Mail::to($email)->send(new SendOTPMail($mailData));
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
                    $mailData = 
                    [
                        'body' => 'You are receiving this email because you have registered on our Kikos platform',
                        'code'  => 'Your otp is '. $code,
                        'email'  => $email
                    ];
                    Mail::to($email)->send(new SendOTPMail($mailData));
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
            //dd($user);
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
                    $mailData = 
                    [
                        'body' => 'You are receiving this email because you have registered on our Kikos platform',
                        'code'  => 'Your otp is '. $code,
                        'email'  => $email
                    ];
                    Mail::to($email)->send(new SendOTPMail($mailData));
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
                    //dd($email,'2');
                    $mailData = 
                    [
                        'body' => 'You are receiving this email because you have registered on our Kikos platform',
                        'code'  => 'Your otp is '. $code,
                        'email'  => $email
                    ];
                    Mail::to($email)->send(new SendOTPMail($mailData));
                    return response()->json($data);
                }
            }else{
                $data['status'] = false;
                $data['message'] = 'Email is already registered.';
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
            $callback->status = 0;
            $callback->save();
            $callback_id = $callback->id;
            
            /*Admin Mail and Push Notification*/
            $admin = User::where('id',1)->first();
            $mailData = [
                'name'  => $admin->fullname,
                'booking_id'  => $callback_id,
                'pickup_address'  => '',
                'drop_address'  => '',
                'date_time'  => '',
                'driver_details'  => ''
            ];
            Mail::to($admin->email)->send(new freeCallBackMail($mailData));
            $push_message = 'Free Callback Request'; 
            // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
            $device_token = $admin->firebase_token;
            $img='';
            $type=''; 
            $id='';
            $title= $admin->fullname;
            $id1='';
            $sound ='default';
            $serverKey= 'AAAASwFiVeM:APA91bHxRaPbKYx4krs489Y1sjMV6uau2wff1xrwa36JoyJOGdwJPGtdbk6XER6qn2XLZhgom8KdyUNVdhQZOfXZUyJLWdKnov9VcHSlRBM0NTMBT1ZeI498SoJCNt1sN36Rx2IkTNBi';
            $check=$this->send_notification($serverKey,$push_message,$device_token,$title);
            $notification = new Notification;
            $notification->booking_type = 'Callback Request';
            $notification->booking_id = $callback_id;
            $notification->notification = $push_message;
            $notification->status = 0;
            $notification->user_id = $admin->id;
            $notification->save();
            
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
            //Check Validation accoding to Tour type
            if($request->tour_type == 1){
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
            }else{
                $validator = Validator::make($request->all() , [
                    'tour_id' => 'required|integer',
                    'tour_type' => 'required|string|max:255|min:1',
                    'booking_date' => 'required',
                    'amount' => 'required',
                ]);
            }
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            $booking_id = random_alphanumeric();
            $transaction_id = random_alphanumeric();
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
            $booking->transaction_id = $request->transaction_id;
            $tax = Master::where('id','!=',null)->value('tax');
            $booking->tax = $tax->tax ?? '0';
            //Checking Tour type
            if($request->amount){
                $booking->total_amount = $request->amount + $tax;/*Virtual amount */
            }else{
                $booking->total_amount = $adults_amount + $senior_amount + $childrens_amount + $tax;/*Tour amount */
            }
            $booking->status = 0;
            $booking->save();
            $tour_booking_id = $booking->id;
            $PaymentDetail = new PaymentDetail;
            $PaymentDetail->booking_id = $booking_id;
            $PaymentDetail->transaction_id = $request->transaction_id;
            $PaymentDetail->payment_provider = 'PayPal';
            //Checking Tour type
            if($request->amount){
                $PaymentDetail->amount = $request->amount + $tax;/*Virtual amount */
            }else{
                $PaymentDetail->amount = $adults_amount + $senior_amount + $childrens_amount + $tax;/*Tour amount */
            }
            $PaymentDetail->status = 1;
            $PaymentDetail->type = $request->tour_type;/*1:Normal Tour,2:Virtual Tour, 3:Photo-Booth, 4:Tax-booking */
            $PaymentDetail->save();
            /*Admin Mail*/
            $admin = User::where('id',1)->first();
            $mailData = [
                'name'  => $admin->fullname,
                'booking_id'  => $booking_id,
                'pickup_address'  => '',
                'drop_address'  => '',
                'date_time'  => '',
                'driver_details'  => ''
            ];
            Mail::to($admin->email)->send(new TourBookingAdminMail($mailData));
            $push_message = 'New Tour Booking';
            // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
            $device_token = $admin->device_token;
            $img='';
            $type='';
            $id='';
            $title= $admin->fullname;
            $id1='';
            $sound ='default';
            $serverKey= 'AAAA_Djj7e4:APA91bESbcbXUuWZA-VVyuxyRJA9npCPpwU5I9uv7iwbnK73bHn0WyCYIfIe-KHMcE1STSK3kiq0_eYxF4F3ob7L4BZyVPRCNx7Mfq2CaUiXk_UKirgzr_ZrT650upTpW3SjMuz-EJ7l';
            $check=$this->send_notification($serverKey,$push_message,$device_token,$title);

            $notification = new Notification;
            $notification->booking_type = 'Tour Booking';
            $notification->booking_id =  $booking->id;
            $notification->notification = $push_message;
            $notification->user_id = $admin->id;
            $notification->status = 0;
            $notification->save();
            /*User Mail*/
            $mailData = [
                'name'  => $user->fullname,
                'booking_id'  => $booking_id,
                'pickup_address'  => '',
                'drop_address'  => '',
                'date_time'  => '',
                'driver_details'  => ''
            ];
            Mail::to($user->email)->send(new TourBookingAdminMail($mailData));
            $push_message = 'New Tour Booking';
            // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
            $device_token = $user->device_token;
            $img='';
            $type='';
            $id='';
            $title= $user->fullname;
            $id1='';
            $sound ='default';
            $serverKey= 'AAAA_Djj7e4:APA91bESbcbXUuWZA-VVyuxyRJA9npCPpwU5I9uv7iwbnK73bHn0WyCYIfIe-KHMcE1STSK3kiq0_eYxF4F3ob7L4BZyVPRCNx7Mfq2CaUiXk_UKirgzr_ZrT650upTpW3SjMuz-EJ7l';
            $check=$this->send_notification($serverKey,$push_message,$device_token,$title);
            $notification = new Notification;
            $notification->booking_type = 'Tour Booking';
            $notification->booking_id =  $booking->id;
            $notification->notification = $push_message;
            $notification->user_id = $user->id;
            $notification->status = 0;
            $notification->save();

            $data['status']=true;
            $data['message']="Boooked successfully";
            $data['booking_id']=$booking_id;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /* Booking Photo Booth */
    public function bookingPhotoBooth(Request $request) 
    {
        try {
            $data=array();
            $validator = Validator::make($request->all() , [
                'photo_booth_id' => 'required|integer',
                'tour_type' => 'required|string|max:255|min:1',
                'booking_date' => 'required',
                'amount' => 'required'
            ]);
            
            if ($validator->fails())
            {
                return errorMsg($validator->errors()->first());
            }
            $booking_id = random_alphanumeric();
            $user = Auth::user();
            $image_count = PhotoBoothMedia::where('booth_id',$request->photo_booth_id)->where('media_type','Image')->count();
            $video_count = PhotoBoothMedia::where('booth_id',$request->photo_booth_id)->where('media_type','video')->count();
            $booking = new BookingPhotoBooth;
            $booking->booking_id = $booking_id;
            $booking->booth_id = $request->photo_booth_id;/*Photo Booth id save in Photo booth ID */
            $booking->userid = $user->id;
            $booking->image_count = $image_count;
            $booking->video_count = $video_count;
            $booking->booking_date = $request->booking_date;
            $tax = Master::where('id','!=',null)->value('tax');
            $booking->tax = $tax;
            $booking->total_amount = $request->amount + $tax;
            $booking->status = 0;
            $booking->transaction_id =  $request->transaction_id;
            $booking->save();
            $photo_booth_booking_id = $booking->id;
            
            $PaymentDetail = new PaymentDetail;
            $PaymentDetail->booking_id = $booking_id;
            $PaymentDetail->transaction_id = $request->transaction_id;
            $PaymentDetail->payment_provider = 'PayPal';
            $PaymentDetail->amount = $request->amount + $tax;/*PhotoBOOTH amount */
            $PaymentDetail->status = 1;
            $PaymentDetail->type = 3;/*1:Normal Tour,2:Virtual Tour, 3:Photo-Booth, 4:Tax-booking */
            $PaymentDetail->save();
            
            
            /*User Mail and Push Notification*/
            $mailData = [
                'name'  => $user->fullname,
                'booking_id'  => $booking_id,
                'pickup_address'  => '',
                'drop_address'  => '',
                'date_time'  => '',
                'driver_details'  => ''
            ];
           // Mail::to($user->email)->send(new PhotoBoothBookingUserMail($mailData));
            $push_message = 'Photo Booth Booked'; 
            // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
            $device_token = $user->device_token;
            $img='';
            $type=''; 
            $id='';
            $title= $user->fullname;
            $id1='';
            $sound ='default';
            $serverKey= 'AAAA_Djj7e4:APA91bESbcbXUuWZA-VVyuxyRJA9npCPpwU5I9uv7iwbnK73bHn0WyCYIfIe-KHMcE1STSK3kiq0_eYxF4F3ob7L4BZyVPRCNx7Mfq2CaUiXk_UKirgzr_ZrT650upTpW3SjMuz-EJ7l';
           // $check=$this->send_notification($serverKey,$push_message,$device_token,$title);

            $notification = new Notification;
            $notification->booking_type = 'PhotoBooth Booking';
            $notification->booking_id =  $booking->id;
            $notification->notification = $push_message;
            $notification->status = 0;
            $notification->user_id = $user->id;
            $notification->save();
            /*Admin Mail and Push Notification*/
            $admin = User::where('id',1)->first();
            $mailData = [
                'name'  => $admin->fullname,
                'booking_id'  => $booking_id,
                'pickup_address'  => '',
                'drop_address'  => '',
                'date_time'  => '',
                'driver_details'  => ''
            ];
           // Mail::to($admin->email)->send(new PhotoBoothBookingadminMail($mailData));
            $push_message = 'New Taxi Booking'; 
            // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
            $device_token = $admin->device_token;
            $img='';
            $type=''; 
            $id='';
            $title= $admin->fullname;
            $id1='';
            $sound ='default';
            $serverKey= 'AAAA_Djj7e4:APA91bESbcbXUuWZA-VVyuxyRJA9npCPpwU5I9uv7iwbnK73bHn0WyCYIfIe-KHMcE1STSK3kiq0_eYxF4F3ob7L4BZyVPRCNx7Mfq2CaUiXk_UKirgzr_ZrT650upTpW3SjMuz-EJ7l';
           // $check=$this->send_notification($serverKey,$push_message,$device_token,$title);
            
            $notification = new Notification;
            $notification->booking_type = 'Taxi Booking';
            $notification->booking_id =  $booking->id;
            $notification->notification = $push_message;
            $notification->status = 0;
            $notification->user_id = $admin->id;
            $notification->save();

            $data['status']=true;
            $data['message']="Boooked successfully";
            $data['booking_id']=$booking_id;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing Virtual tour data with or without login */
    public function VirtualTourListing() 
    {
        try {
            $tours = VirtualTour::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Virtual-Tour
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


    public function VirtualTourStopsListing() 
    {
        try {
            $tours = VirtualTour::where('status', 1)->with('stop_details')->orderBy('id', 'DESC')->get();

            $response = [];
            foreach ($tours as $tour) {
                $origin= [
                    'name' => $tour->origin, 
                    'lat'=> $tour->origin_lat, 
                    'lng'=> $tour->origin_long
                ];
               $destination = [ 
                    'name'=> $tour->destination,  
                    'lat'=> $tour->dest_lat, 
                    'lng'=> $tour->dest_long
                ];
                $temp = [
                    'id' => $tour->id,
                    'minute' => $tour->minute,
                    'name' => $tour->name,
                    'price' => $tour->price,
                    'description' => $tour->description,
                    'cancellation_policy' => $tour->cancellation_policy,
                    'audio' => $tour->audio_file,
                    'thumbnail' => $tour->thumbnail_file,
                    'origin' => $origin,
                    'destination' => $destination,
                    'stop_details' => []
                ];

                foreach ($tour->stop_details as $stop) {
                    $temp['stop_details'][] = [
                        'id' => $stop->id,
                        'parent_id' => $stop->parent_id,
                        'stop_name' => $stop->stop_name,
                        'stop_number' => $stop->stop_number,
                        'stop_image' => $stop->stop_image,
                        'stop_audio' => $stop->stop_audio ?? null,
                        'latitude' => $stop->lat ?? null,
                        'longitude' => $stop->long ?? null,
                    ];
                }

                $response[] = $temp;
            }

            $data['status'] = true;
            $data['message'] = 'Virtual tour listing';
            $data['data'] = $response;
            // $data['regionData'] = $regionData;
            
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }


    public function VirtualTourStopDetail(Request $request) 
    {
        try {
            //Virtual detail according to virtual tour id
            $tour = VirtualTour::where('id', $request->id)->with('stop_details')->first();
            // $stops_data = [
            //     ['name' => "Leonard's Bakery", 'coordinates' => ['lat' => 21.2921, 'lng' => -157.8417], 'stopNumber' => 1],
            //     ['name' => "Byodo In - Japanese Temple", 'coordinates' => ['lat' => 21.4368, 'lng' => -157.8344], 'stopNumber' => 2],
            //     ['name' => "Kualoa Ranch", 'coordinates' => ['lat' => 21.528525, 'lng' => -157.837039], 'stopNumber' => 3],
            //     ['name' => "Kahana Bay", 'coordinates' => ['lat' => 21.5628, 'lng' => -157.8643], 'stopNumber' => 4],
            //     ['name' => "Pounder's Beach", 'coordinates' => ['lat' => 21.589276, 'lng' => -157.907976], 'stopNumber' => 5],
            //     ['name' => "Chinamanan's Hat - Mokoli'i", 'coordinates' => ['lat' => 21.623869, 'lng' => -157.919242], 'stopNumber' => 6],
            //     ['name' => "Polynesian Cultural Center", 'coordinates' => ['lat' => 21.6349, 'lng' => -157.9226], 'stopNumber' => 7],
            //     ['name' => "Mastumoto Shave Ice", 'coordinates' => ['lat' => 21.6412, 'lng' => -157.9223], 'stopNumber' => 8],
            //     ['name' => "Laie Temple", 'coordinates' => ['lat' => 21.6447, 'lng' => -157.9186], 'stopNumber' => 9],
            //     ['name' => "Ted's Bakery", 'coordinates' => ['lat' => 21.6496, 'lng' => -157.9237], 'stopNumber' => 10],
            //     ['name' => "Laie Point", 'coordinates' => ['lat' => 21.6517, 'lng' => -157.9082], 'stopNumber' => 11],
            //     ['name' => "Kahuku Fruit Stand", 'coordinates' => ['lat' => 21.676938, 'lng' => -157.950047], 'stopNumber' => 12],
            //     ['name' => "Kahuku Shrimp & Food Trucks", 'coordinates' => ['lat' => 21.677091, 'lng' => -157.950312], 'stopNumber' => 13],
            //     ['name' => "Sunset Beach", 'coordinates' => ['lat' => 21.674306, 'lng' => -158.038180], 'stopNumber' => 14],
            //     ['name' => "Banzai Pipeline", 'coordinates' => ['lat' => 21.673301, 'lng' => -158.042791], 'stopNumber' => 15],
            //     ['name' => "Shark's Cove", 'coordinates' => ['lat' => 21.671957, 'lng' => -158.064999], 'stopNumber' => 16],
            //     ['name' => "Waimea Bay", 'coordinates' => ['lat' => 21.642436, 'lng' => -158.067561], 'stopNumber' => 17],
            //     ['name' => "Waimea Valley", 'coordinates' => ['lat' => 21.6216, 'lng' => -158.0646], 'stopNumber' => 18],
            //     ['name' => "Haleiwa Beach", 'coordinates' => ['lat' => 21.5941, 'lng' => -158.1037], 'stopNumber' => 19],
            //     ['name' => "Tropical Nut Farms", 'coordinates' => ['lat' => 21.5559, 'lng' => -158.0239], 'stopNumber' => 20],
            //     ['name' => "Dole Plantation", 'coordinates' => ['lat' => 21.5255, 'lng' => -158.0373], 'stopNumber' => 21],
            // ];
            $regionData = [];
            if (!empty($tour)) {
                $temp['id'] = $tour->id;
                $temp['minute'] = $tour->minute;
                $temp['duration'] = $tour->duration ?? '';
                $temp['name'] = $tour->name;
                $temp['price'] = $tour->price;
                $temp['description'] = $tour->description;
                $temp['short_description'] = $tour->short_description ?? '';
                $temp['cancellation_policy'] = $tour->cancellation_policy ?? '';
                $temp['uploaded_date'] = date('d M, Y, h:i:s a', strtotime($tour->created_at));
                $temp['purchase_user_count'] = '2.1M'; // Placeholder value, replace with actual purchase user count logic
                $temp['purchase_date'] = date('d M, Y, h:i:s a', strtotime($tour->created_at));
                $temp['audio'] = asset('public/upload/virtual-audio/' . $tour->audio_file);
                $temp['trail_audio'] = $tour->profile ? asset('public/upload/virtual-audio/' . $tour->trial_audio_file) : '';
                $temp['thumbnail'] = asset('public/upload/virtual-thumbnail/' . $tour->thumbnail_file);

                // Process stop details
                //$stopDetails = [];
                // foreach ($tour->stop_details as $stop) {
                //     $stopDetails[] = [
                //         'stop_name' => $stop->stop_name,
                //         'stop_number' => $stop->stop_number,
                //         'stop_image' => $stop->stop_image ? asset('public/upload/virtual-images/'.$stop->stop_image) : null,
                //         'stop_audio' => $stop->stop_audio ? asset('public/upload/virtual-audio/'.$stop->stop_audio) : null,
                //         "latitude" => '37.78825',
                //         "longitude" => '-122.4324' ,
                //     ];
                // }

                $origin[] = [
                    'name' => $tour->origin, 
                    'lat'=> $tour->origin_lat, 
                    'lng'=> $tour->origin_long
                ];
               $destination[] = [ 
                    'name'=> $tour->destination,  
                    'lat'=> $tour->dest_lat, 
                    'lng'=> $tour->dest_long
                ];

               $temp['origin'] = $origin;
               $temp['destination'] = $destination;
                // $temp['stop_details'] = $stopDetails;

                foreach ($tour->stop_details as $stop) {
                    $regionData[] = [
                        'stop_name' => $stop['stop_name'],
                        'stop_number' => (string)$stop['stop_number'],
                        'latitude' => $stop['lat'],
                        'longitude' => $stop['long'],
                        'stop_audio' =>  $stop->stop_image ? asset('public/upload/virtual-images/'.$stop->stop_image) : null,
                        'stop_image' =>  $stop->stop_audio ? asset('public/upload/virtual-audio/'.$stop->stop_audio) : null,
                    ];
                }
                $temp['region'] = $regionData;
               
            } else {
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
                $temp['trail_audio'] =  $tour->profile ? asset('public/upload/virtual-audio/'.$tour->trial_audio_file) : '';/*Audio file of virtual tour*/
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
    
    /*We can create a booking with & without login | Taxi booking api  */
    public function bookingTaxi(Request $request) 
    {
        try {
            //Validation for Taxi booking
            $validator = Validator::make($request->all(), [
                'booking_date_time' => 'required',
                'fullname' => 'required|string|max:255',
                'email_id' => 'nullable|string',
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
            /*Checking User Auth */
            if($request->user_id)
            {
                $user = User::where('id',$request->user_id)->first();
                $user_id = $request->user_id;
                $user_name = $user->fullname;
            }else{
                $user_id = null;
                $user_name = $request->fullname;
            }
            
            /*Create taxi booking with booking id*/
            $booking_id = rand(10000000,99999999);/*Generate random booking  ID*/
            
            /*Calculate Distance in KM*/
            $pick_lat_lng = explode(',', $request->pickup_lat_long, 2);/*Expload the data */
            $drop_lat_long = explode(',', $request->drop_lat_long, 2);/*Expload the data */
            $distance = getDistanceBetweenPoints($pick_lat_lng[0], $pick_lat_lng[1], $drop_lat_long[0], $drop_lat_long[1]);
           
            $Distance = number_format((float)$distance['miles'], 2, '.', ''); 
            
            $amount = $Distance * 10;
            
            
            $bookingID = TaxiBooking::insertGetId([
                'booking_time' => $request->booking_date_time,/*Date and Time */
                'booking_date' => date('y-m-d', strtotime($request->booking_date_time)),/*Filter date and save */
                'user_id' => $user_id,
                'user_name' => $user_name,
                'booking_id' => $booking_id,
                'fullname' => $request->fullname,
                'email' => $request->email_id,
                'pickup_location' => $request->pickup_location,
                'pickup_lat_long' => $request->pickup_lat_long,
                'drop_location' => $request->drop_location,
                'drop_lat_long' => $request->drop_lat_long,
                'mobile' => $request->mobile,
                'hotel_name' => $request->hotel_name,
                'distance' => $Distance,
                'amount' => $amount,
                'status' => 0,
                'created_at' => date("Y-m-d h:i:s")
            ]);
            // $PaymentDetail = new PaymentDetail;
            // $PaymentDetail->booking_id = $bookingID;
            // $PaymentDetail->transaction_id = $request->transaction_id;
            // $PaymentDetail->payment_provider = 'PayPal';
            // $PaymentDetail->amount = $request->amount;/*TaxiBooking amount */
            // $PaymentDetail->status = 1;
            // $PaymentDetail->type = 4;/*1:Normal Tour,2:Virtual Tour, 3:Photo-Booth, 4:Tax-booking */
            // $PaymentDetail->save();
            
            if($bookingID){
                $data['status'] = true;
                $data['message'] = 'Booking request send successfully';
            }else{
                $data['status'] = false;
                $data['message'] = 'Something went wrong';
            }
            
            /*Without login Send mail to user via emailId */
            if($request->email_id)
            {
                /*User Mail and Push Notification*/
                $mailData = [
                    'name'  => $request->fullname,
                    'booking_id'  => $booking_id,
                    'pickup_address'  => $request->pickup_location,
                    'drop_address'  => $request->drop_location,
                    'date_time'  => $request->booking_date_time,
                    'driver_details'  => ''
                ];
                //Mail::to($request->email_id)->send(new TaxiBookingMail($mailData));
                if($request->user_id){
                    $user = User::where('id',$request->user_id)->first();
                    $push_message = 'Taxi Booked'; 
                    // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
                    $device_token = $user->device_token;
                    $img='';
                    $type=''; 
                    $id='';
                    $title= $user->fullname;
                    $id1='';
                    $sound ='default';
                    $serverKey= 'AAAA_Djj7e4:APA91bESbcbXUuWZA-VVyuxyRJA9npCPpwU5I9uv7iwbnK73bHn0WyCYIfIe-KHMcE1STSK3kiq0_eYxF4F3ob7L4BZyVPRCNx7Mfq2CaUiXk_UKirgzr_ZrT650upTpW3SjMuz-EJ7l';
                    $check=$this->send_notification($serverKey,$push_message,$device_token,$title);


                    $notification = new Notification;
                    $notification->booking_type = 'Taxi Booked';
                    $notification->booking_id =  $bookingID;
                    $notification->notification = $push_message;
                    $notification->user_id = $user->id;
                    $notification->status = 0;
                    $notification->save();
                }
            }
            
            /*Admin Mail and Push Notification*/
            $admin = User::where('id',1)->first();
            $mailData = [
                'name'  => $admin->fullname,
                'booking_id'  => $booking_id,
                'pickup_address'  => '',
                'drop_address'  => '',
                'date_time'  => '',
                'driver_details'  => ''
            ];
            Mail::to($admin->email)->send(new TaxiBookingAdminMail($mailData));
            $push_message = 'New Taxi Booking'; 
            // $device_token = 'e552do-MSJm4gjhgjhgbhjPiUlVPj1_:APA91bFGhdwdAHMtLV_9SYGqKjBMzWyMTR_Y5KE5SSWP2kqsXcX6Rx-wl_k2RvQJAm-sKO1BvTXicAjjChkLj1k_ZgpKlWY7-wMsT_2guKpLtWz_2wpOpZ9ibl51j7ZdK3HXD737h6KJ';
            $device_token = $admin->device_token;
            $img='';
            $type=''; 
            $id='';
            $title= $admin->fullname;
            $id1='';
            $sound ='default';
            $serverKey= 'AAAA_Djj7e4:APA91bESbcbXUuWZA-VVyuxyRJA9npCPpwU5I9uv7iwbnK73bHn0WyCYIfIe-KHMcE1STSK3kiq0_eYxF4F3ob7L4BZyVPRCNx7Mfq2CaUiXk_UKirgzr_ZrT650upTpW3SjMuz-EJ7l';
            $check=$this->send_notification($serverKey,$push_message,$device_token,$title);
            $notification = new Notification;
            $notification->booking_type = 'New Taxi Booking';
            $notification->booking_id =  $bookingID;
            $notification->notification = $push_message;
            $notification->user_id = $admin->id;
            $notification->status = 0;
            $notification->save();
            
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
            $bookings = TaxiBooking::where('user_id',$user_id)->orderBy('id','DESC')->get();
            if(count($bookings) > 0){
                $response = array();
                foreach ($bookings as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['booking_id'] = $value->booking_id;
                    $temp['booking_time'] = date('d M, Y - g:i A', strtotime($value->booking_time));
                    $temp['user_id'] = $value->user_id;
                    $temp['user_name'] = $user->fullname;
                    $temp['user_profile'] = $user->profile ? asset('public/upload/profile/'.$user->profile): '';
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
            $tax = Master::where('id','!=',null)->first();
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
                    $temp['image'] = asset('public/upload/photo-booth/'.$image->media);/*Image file of Photo Booth */
                    $temp['image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();
                    $temp['video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();
                    $temp['purchase_image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();
                    $temp['purchase_video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();
                    $temp['tax'] = $tax->tax; 
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $image_count = 20;
            $video_count = 10;
                    
            $data['status'] = true;
            $data['message'] = 'Photo booth listing';
            $data['total_purchase_image'] = $image_count;
            $data['total_purchase_video'] = $video_count;
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*Showing all tour listing, count of bookrd tour */
    public function book_tour(Request $request) 
    {
        try {
            $tours = Tour::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            if(count($tours) > 0){
                $response = array();/*Store data an array */
                foreach ($tours as $key => $tour) {
                    $temp['id'] = $tour->id;
                    $temp['title'] = $tour->title;
                    $temp['name'] = $tour->name;
                    $temp['same_for_all'] = $tour->same_for_all;/* Tour Price for all person */
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
            
            $popular_tours = Tour::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            if(count($popular_tours) > 0){
                $popular_response = array();/*Store data an array */
                foreach ($popular_tours as $key => $tour) {
                    $temp1['id'] = $tour->id;
                    $temp1['title'] = $tour->title;
                    $temp1['name'] = $tour->name;
                    $temp1['same_for_all'] = $tour->same_for_all;/* Tour Price for all person */
                    $temp1['age_11_price'] = $tour->age_11_price;/* Tour Price for 11 years+ per person */
                    $temp1['age_60_price'] = $tour->age_60_price;/* Tour Price for 60 years+ per person */
                    $temp1['under_10_age_price'] = $tour->under_10_age_price;/* Tour Price for under 10 years per person */
                    $temp1['duration'] = $tour->duration;/* Tour Time */
                    $images = TourAttribute::where('tour_id',$tour->id)->first();
                    $temp1['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    $popular_response[] = $temp1;
                }
            }else{
                $popular_response = [];
            }
            
            if($request->user_id){
                $user_id = $request->user_id;
                $bookings = TourBooking::where('tour_type',1)->whereIn('status',[1,2])->where('user_id',$user_id)->count();//Get count of Normal tour accoding to user id
                $CallbackRequest = CallbackRequest::where('user_id',$user_id)->count();//Get all datas of Normal tour
            }else{
                $bookings = 0;
                $CallbackRequest = 0;
            }
            
            $tours = Tour::where('status',1)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            
            $data['status'] = true;
            $data['message'] = 'Book tour listing';
            $data['data'] = $response;
            $data['popular_tour'] = $popular_response;
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
                $all_bookings = TourBooking::whereIn('status',[0,1,2])->where('user_id',$user->id)->where('tour_type',1)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }
            // dd($all_bookings);die;
            
            if(count($all_bookings) > 0){
                $response = array();/*Store data an array */
                foreach ($all_bookings as $key => $value) {
                    $payment_details = PaymentDetail::where('booking_id', $value->id)->where('type',$value->tour_type)->first();
                    //dd($value->id);
                    $temp['id'] = $value->id;
                    $temp['status_id'] = $value->status;
                    $temp['tour_type'] = $value->tour_type;
                    $temp['status'] = (($value->status == 1) ? "Accepted" : (($value->status == 2) ? "Rejected" : (($value->status == 0) ? "Pending":"")));
                    $temp['payment_status'] = (($payment_details->status == 1) ? "Accepted" : (($payment_details->status == 2) ? "Rejected" : (($payment_details->status == 0) ? "Pending":"")));
                    $temp['boooking_id'] = $value->booking_id;
                    $tour = Tour::where('id',$value->tour_id)->first();
                    $temp['tour_title'] = $tour->title;
                    $temp['cancellation_policy'] = $tour->cancellation_policy;
                    $temp['selectd_date'] = date('d M, Y', strtotime($value->booking_date));
                    $temp['duration'] = $tour->duration;/* Tour Time */
                    $temp['no_of_adults'] = $value->no_adults;
                    $temp['no_of_senior'] = $value->no_senior_citizen;
                    $temp['no_of_children'] = $value->no_childerns;
                    $temp['total_amount'] = $value->total_amount;
                    $temp['no_of_person'] = $value->no_adults+$value->no_senior_citizen+$value->no_childerns;
                    $images = TourAttribute::where('tour_id',$value->id)->first();
                    if($images){
                        $temp['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    }else{
                        $temp['images'] = '';
                    }
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
            dd($e);
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }



    public function virtual_tour_booking(Request $request) 
    {
        try {
            $user = Auth::user();
            if($request->status == 1)
            {
                /*Accepted */
                $all_bookings = TourBooking::where('status',1)->where('user_id',$user->id)->where('tour_type',2)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }elseif($request->status == 2){
                /*Rejected */
                $all_bookings = TourBooking::where('status',2)->where('user_id',$user->id)->where('tour_type',2)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }else{
                /*All */
                $all_bookings = TourBooking::whereIn('status',[0,1,2])->where('user_id',$user->id)->where('tour_type',2)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            }
            
            
            if(count($all_bookings) > 0){
                $response = array();/*Store data an array */
                foreach ($all_bookings as $key => $value) {
                    $payment_details = PaymentDetail::where('booking_id', $value->id)->where('type',$value->tour_type)->first();
                    $temp['id'] = $value->id;
                    $temp['status_id'] = $value->status;
                    $temp['tour_type'] = $value->tour_type;
                    $temp['status'] = (($value->status == 1) ? "Accepted" : (($value->status == 2) ? "Rejected" : (($value->status == 0) ? "Pending":"")));
                    $temp['payment_status'] = (($payment_details->status == 1) ? "Accepted" : (($payment_details->status == 2) ? "Rejected" : (($payment_details->status == 0) ? "Pending":"")));
                    $temp['boooking_id'] = $value->booking_id;
                    $tour = Tour::where('id',$value->tour_id)->first();
                    $temp['tour_title'] = $tour->title;
                    $temp['cancellation_policy'] = $tour->cancellation_policy;
                    $temp['selectd_date'] = date('d M, Y', strtotime($value->booking_date));
                    $temp['duration'] = $tour->duration;/* Tour Time */
                    $temp['no_of_adults'] = $value->no_adults;
                    $temp['no_of_senior'] = $value->no_senior_citizen;
                    $temp['no_of_children'] = $value->no_childerns;
                    $temp['total_amount'] = $value->total_amount;
                    $temp['no_of_person'] = $value->no_adults+$value->no_senior_citizen+$value->no_childerns;
                    $images = TourAttribute::where('tour_id',$value->id)->first();
                    if($images){
                        $temp['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    }else{
                        $temp['images'] = '';
                    }
                    
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
    public function confirmed_tour_detail(Request $request) 
    {
        try {
            $user = Auth::user();
            $value = TourBooking::where('id',$request->booking_id)->first();//Get datas of tour booking of user
            $tourImage = array();
            $temp['id'] = $value->id;
            $temp['status_id'] = $value->status;
            $temp['status'] = (($value->status == 1) ? "Accepted" : (($value->status == 2) ? "Rejected" : ""));
            $temp['boooking_id'] = $value->booking_id;
            $tour = Tour::where('id',$value->tour_id)->first();
            $temp['tour_title'] = $tour->title;
            $temp['cancellation_policy'] = $tour->cancellation_policy;
            $temp['selectd_date'] = date('d M, Y', strtotime($value->booking_date));
            $temp['duration'] = $tour->duration;/* Tour Time */
            $temp['what_to_bring'] = $tour->what_to_bring;
            $temp['short_description'] = $tour->short_description;
            $temp['description'] = $tour->description;
            $temp['no_of_adults'] = $value->no_adults;
            $temp['no_of_senior'] = $value->no_senior_citizen;
            $temp['no_of_children'] = $value->no_childerns;
            $temp['total_amount'] = $value->total_amount;
            $temp['same_for_all'] = $tour->same_for_all;/* Tour Price for all person */
            $temp['age_11_price'] = $tour->age_11_price;/* Tour Price for 11 years+ per person */
            $temp['age_60_price'] = $tour->age_60_price;/* Tour Price for 60 years+ per person */
            $temp['under_10_age_price'] = $tour->under_10_age_price;/* Tour Price for under 10 years per person */
            $temp['no_of_person'] = $value->no_adults+$value->no_senior_citizen+$value->no_childerns;
            $images = TourAttribute::where('tour_id',$value->id)->get();
            foreach ($images as $key => $val) {
                $tourImage[] = asset('public/upload/tour-thumbnail/'.$val->attribute_name);
            }
                
                $temp['images'] = $tourImage;
            
            $data['status'] = true;
            $data['message'] = 'Book tour listing';
            $data['data'] = $temp;
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
            $tax = Master::where('id','!=',null)->first();
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
                $temp['tax'] = $tax->tax; 
                $images = PhotoBoothMedia::where('booth_id',$value->id)->get();/*Find all images and video accoding to photo booth id */
                $response = array();
                foreach ($images as $key => $val) {
                    $temp1['id'] = $val->id;
                    $temp1['object_type'] = $val->media_type;
                    
                    if($val->media_type == 'Image'){
                        $media = asset('public/upload/photo-booth/'.$val->media);
                    }else{
                        $media = asset('public/upload/video-booth/'.$val->media); 
                    }
                    $temp1['media'] = $media;
                    $response[] = $temp1;
                }
               
                $temp['all_image_video'] = $response;/*Image video listing of photo booth in array */
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
    
    /*Showing all Photo booth user purchase listing */
    public function PhotoBoothPurchaseListing() 
    {
        try {
            $user = Auth::user();
            $datas = BookingPhotoBooth::where('userid',$user->id)->orderBy('id','DESC')->get();//Get all datas of Photo-Booth
            if(count($datas) > 0){
                $response = array();/*Store data an array */
                foreach ($datas as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['booth_id'] = $value->booth_id;
                    $tour = PhotoBooth::where('id',$value->booth_id)->first();
                    $temp['title'] = $tour->title;/*Photo Booth Title*/
                    $temp['price'] = $tour->price;/*Photo Booth Price*/
                    $temp['description'] = $tour->description;
                    $temp['cancellation_policy'] = $tour->cancellation_policy;
                    $image = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$tour->id)->first();
                    $temp['image'] = asset('public/upload/photo-booth/'.$image->media);/*Image file of Photo Booth */
                    $temp['image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();
                    $temp['video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();
                    $temp['purchase_image_count'] = PhotoBoothMedia::where('media_type','Image')->where('booth_id',$value->id)->count();
                    $temp['purchase_video_count'] = PhotoBoothMedia::where('media_type','Video')->where('booth_id',$value->id)->count();
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $image_count = 20;
            $video_count = 10;
                    
            $data['status'] = true;
            $data['message'] = 'Photo booth listing';
            $data['total_purchase_image'] = $image_count;
            $data['total_purchase_video'] = $video_count;
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    /*TimeZone listing */
    public function TimeZoneList() 
    {
        try {
            $timezone = TimeZone::where('status',1)->orderBy('id','DESC')->get();//Get all datas of timezone
            if(count($timezone) > 0){
                $response = array();/*Store data an array */
                foreach ($timezone as $key => $value) {
                    $temp['id'] = $value->id;
                    $temp['name'] = $value->timezone;
                    $temp['status'] = $value->status;
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
                    
            $data['status'] = true;
            $data['message'] = 'TimeZone Listing';
            $data['data'] = $response;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }
    
    public function send_notification($serverKey='', $push_message='',$device_token='',$title='')
    {
        $msg = array(
            'body'  => $push_message,
            'title' => $title,
            'sound' => 'default',
        );
        $load = array(
            'body'  => $push_message,
            'title' => $title,
            'sound' => 'default',
        );
        $token = $device_token;
        $fields = array('to' => $token, 'notification' => $msg, 'data' => $load, "priority" => "high");
        $serverKey = $serverKey;
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }


    public function addTaxiBookingEvent(Request $request)
    {
        if($request->datesstatustype == 'Not Available')
        {
            $title = 'Not Available';
            $color = '#9C9D9F';
        }elseif($request->datesstatustype == 'Booked Taxi'){
            $title = 'Booked Tour';
            $color = '#1d875a';
        }else{
            $title = 'Available';
            $color = '#FFFFFF';
        }
        $event = new TaxiBookingEvent;
        $event->title = $title;
        $event->date = $request->date;
        $event->color = $color;
        $event->save();

        return response()->json($event);
    }


    public function TaxiBookingFilter(Request $request)
    {
        try {
            $requests = TaxiBooking::query();
            $search = $request->search ? $request->search : '';
            $date = $request->date ? $request->date : '';
            
            if($request->filled('search')){
                $requests->Where('user_name', 'LIKE', '%'.$request->search.'%');
                $requests->orWhere('booking_id', 'LIKE', '%'.$request->search.'%');
            }

            if($request->filled('date')){
                $requests->whereDate('booking_date', '=', $request->date);
            }
            $requests->where('status', 0);
            $requests->orderBy('id', 'DESC');
            $bookings = $requests->paginate(10);
            $amount = TaxiBooking::sum('amount');
            $data['bookings'] = $bookings;
            $data['amount'] = $amount;
            return response()->json($data);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }


    public function CallbackRequest(Request $request)
    {
        try {
            
            $requests = CallbackRequest::query();
            $search = $request->search ? $request->search : '';
            $tour_id = $request->tour_id ? $request->tour_id : '';
            $date = $request->date ? $request->date : '';
            
            if($request->filled('search')){
                $requests->Where('name', 'LIKE', '%'.$request->search.'%');
                $requests->orWhere('mobile', 'LIKE', '%'.$request->search.'%');
            }

            if($request->filled('tour_id')){
                $requests->where('tour_id', $request->tour_id);
            }

            if($request->filled('date')){
                $requests->whereDate('preferred_time', '=', $request->date);
            }
            $requests->where('status', 0);
            $requests->orderBy('id', 'DESC');
            $datas = $requests->paginate(10);
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
            return response()->json($datas);

        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }


    public function virtual_confirmed__tour(Request $request) 
    {
        try {
            $user = Auth::user();
                /*Accepted */
            $all_bookings = TourBooking::where('status',1)->where('user_id',$user->id)->where('tour_type',2)->orderBy('id','DESC')->get();//Get all datas of tour booking of user
            // dd($all_bookings);die;
            
            if(count($all_bookings) > 0){
                $response = array();/*Store data an array */
                foreach ($all_bookings as $key => $value) {
                    // $payment_details = PaymentDetail::where('transaction_id', $value->transaction_id)->where('type',$value->tour_type)->first();
                   // dd($payment_details);
                    $temp['id'] = $value->id;
                    $temp['status_id'] = $value->status;
                    $temp['tour_type'] = $value->tour_type;
                    $temp['status'] = (($value->status == 1) ? "Accepted" : (($value->status == 2) ? "Rejected" : (($value->status == 0) ? "Pending":"")));
                    // $temp['payment_status'] = (($payment_details->status == 1) ? "Accepted" : (($payment_details->status == 2) ? "Rejected" : (($payment_details->status == 0) ? "Pending":"")));
                    $temp['boooking_id'] = $value->booking_id;
                    $tour = Tour::where('id',$value->tour_id)->first();
                    $temp['tour_title'] = $tour->title;
                    $temp['cancellation_policy'] = $tour->cancellation_policy;
                    $temp['selectd_date'] = date('d M, Y', strtotime($value->booking_date));
                    $temp['duration'] = $tour->duration;/* Tour Time */
                    $temp['no_of_adults'] = $value->no_adults;
                    $temp['no_of_senior'] = $value->no_senior_citizen;
                    $temp['no_of_children'] = $value->no_childerns;
                    $temp['total_amount'] = $value->total_amount;
                    $temp['no_of_person'] = $value->no_adults+$value->no_senior_citizen+$value->no_childerns;
                    $images = TourAttribute::where('tour_id',$value->id)->first();
                    if($images){
                        $temp['images'] = asset('public/upload/tour-thumbnail/'.$images->attribute_name);
                    }else{
                        $temp['images'] = '';
                    }
                    $response[] = $temp;
                }
            }else{
                $response = [];
            }
            
            $data['status'] = true;
            $data['message'] = 'Book tour listing';
            $data['data'] = $response;
            $data['count'] = count($response);
            return response()->json($data);
        } catch (\Exception $e) {
            dd($e);
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }



    public function PhotoBoothInfo(Request $request)
    {
        try {
            $requests = BookingPhotoBooth::query();
            $request->validate([
                'search' => 'nullable|string',
                'booth_id' => 'nullable|not_in:', // Ensure booth_id is not the default option
                'date' => 'nullable|date',
            ]);
            $search = $request->input('search');
            $booth_id = $request->input('booth_id');
            $date = $request->input('date');

            if($search){
                $requests->Where('user_name', 'LIKE', '%'.$search.'%');
            }
            if($booth_id != 'Select By Photo Booth Name'){
                $requests->where('booth_id', $booth_id);
            }

            if($date){
                $requests->whereDate('booking_date', '=', $date);
            }
            $requests->whereIn('status', [0,1]);
            $requests->orderBy('id', 'DESC');
            $bookings = $requests->paginate(10);
            
            $PhotoBooths = PhotoBooth::where('status', 1)->orderBy('id', 'DESC')->get();

            // Check if filters are applied
            $filtersApplied = ($search || $booth_id != 'Select By Photo Booth Name' || $date);

            // Check if any data is returned after filtering

            $data = [
                'status' => true,
                'message' => 'Photo Booth Information',
                'data' => [
                    'bookings' => $bookings,
                    'PhotoBooths' => $PhotoBooths,
                    'filters_applied' => $filtersApplied,
                ]
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function getPurchasedTourCount(Request $request)
    {
        try {
            $user = Auth::user();
            $normal_tour =  TourBooking::where('status',1)->where('user_id',$user->id)->where('tour_type',1)->orderBy('id','DESC')->count();
            $virtual_tour = TourBooking::where('status',1)->where('user_id',$user->id)->where('tour_type',2)->orderBy('id','DESC')->count();
            $photo_booth = BookingPhotoBooth::where('userid',$user->id)->orderBy('id','DESC')->count();
            $taxi_booking =  TaxiBooking::where('user_id', $user->id)->count();

            $data['normal_tour_count'] = $normal_tour;
            $data['virtual_tour_count'] = $virtual_tour;
            $data['photo_booth_count'] = $photo_booth;
            $data['taxi_booking_count'] = $taxi_booking;
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function fetchAvailability(Request $request)
    {
        // Fetch the iCalendar data from the provided URL
        $icsUrl = 'https://fareharbor.com/integrations/ics/kikostoursoahu/calendar/?token=098ccc1c-6577-43a3-ba06-f93ec1c2b657';
        $response = Http::get($icsUrl);

        if ($response->successful()) {
            // Parse the iCalendar data
            $icalString = $response->body();

            // Split the iCalendar data into individual events
            $events = explode("BEGIN:VEVENT", $icalString);

            // Extract availability and tour information
            $availabilities = [];
            foreach ($events as $event) {
                // Extract event details
                $matches = [];
                preg_match('/SUMMARY:(.*?)\R/', $event, $summaryMatches);
                $summary = isset($summaryMatches[1]) ? $summaryMatches[1] : '';

                preg_match('/DTSTART:(.*?)\R/', $event, $startMatches);
                $startDateTime = isset($startMatches[1]) ? Carbon::parse($startMatches[1]) : null;

                preg_match('/DTEND:(.*?)\R/', $event, $endMatches);
                $endDateTime = isset($endMatches[1]) ? Carbon::parse($endMatches[1]) : null;

                // Extract booking information from DESCRIPTION field
                preg_match_all('/BOOKING\s*#(\d+).*?Total:\s*\$([\d.]+).*?Due:\s*\$([\d.]+).*?\n(.*?)\n\s*-*\s*/s', $event, $bookingMatches, PREG_SET_ORDER);
                $bookings = [];
                foreach ($bookingMatches as $bookingMatch) {
                    $bookingId = $bookingMatch[1];
                    $totalPrice = $bookingMatch[2];
                    $dueAmount = $bookingMatch[3];
                    $customerInfo = $bookingMatch[4];
                    
                    // Extract customer name and phone number
                    preg_match('/(?:\n|^)(.*?)\n/', $customerInfo, $customerMatches);
                    $customer = isset($customerMatches[1]) ? $customerMatches[1] : '';

                    // Extract seats left information from the summary
                    preg_match('/booking:\s*(\d+)\s*People/', $summary, $seatsMatches);
                    $seatsLeft = isset($seatsMatches[1]) ? $seatsMatches[1] : 0;

                    // Construct booking array
                    $booking = [
                        'booking_id' => $bookingId,
                        'total_price' => $totalPrice,
                        'due_amount' => $dueAmount,
                        'customer' => $customer,
                        'seats_left' => $seatsLeft,
                    ];

                    // Add booking to the array
                    $bookings[] = $booking;
                }

                // Construct availability array
                $availability = [
                    'summary' => $summary,
                    'start' => $startDateTime ? $startDateTime->format('Y-m-d') : null,
                    'end' => $endDateTime ? $endDateTime->format('Y-m-d') : null,
                    'bookings' => $bookings,
                ];

                // Add availability to the array
                $availabilities[] = $availability;
            }

            // Return availability data as JSON response
            return response()->json($availabilities);
        } else {
            // Handle the case where fetching the iCalendar data failed
            return response()->json(['error' => 'Failed to fetch iCalendar data'], $response->status());
        }
    }
    

    public function addPhotoBooth(Request $request)
    {
        try {
            //dd('in');
            $validator = Validator::make($request->all(), [
                'tour_id' => 'required',
                'title' => 'required|string|max:255|min:6',
                'price' => 'required|min:0',
                'description' => 'required',
                'cancellation_policy' => 'required',
                'delete_days' => 'required',
                'users_id.*' => 'required',
                'image.*' => 'required|image|max:5120', // Max 5MB per image
                'video.*' => 'required|file|mimes:mp4,mov,avi|max:102400', // Max 100MB per video
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $imageCount = count($request->file('image'));
            $videoCount = count($request->file('video'));

            if ($imageCount > 20 || $videoCount > 3) {
                return response()->json(['error' => 'You can only upload a maximum of 20 images and 3 videos.'], 400);
            }

            // Save all input data of photobooth
            $usersId = is_array($request->users_id) ? $request->users_id : explode(',', $request->users_id);
            $boothID = PhotoBooth::insertGetId([
                'title' => $request->title,
                'delete_days' => $request->delete_days,
                'users_id' => implode(',', $usersId),
                'price' => $request->price,
                'tour_id' => $request->tour_id,
                'description' => $request->description,
                'cancellation_policy' => $request->cancellation_policy,
                'status' => 1,
                'created_at' => now(),
            ]);

            // Save images
            if ($request->hasFile('image')) {
                $imageFiles = $request->file('image');
                foreach ($imageFiles as $file) {
                    $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                    $file->move(public_path('upload/photo-booth'), $name);
                    PhotoBoothMedia::create([
                        'booth_id' => $boothID,
                        'media_type' => 'Image',
                        'media' => $name,
                        'status' => 1,
                    ]);
                }
            }

            // Save videos
            if ($request->hasFile('video')) {
                $videoFiles = $request->file('video');
                foreach ($videoFiles as $file) {
                    $name = 'VID_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                    $file->move(public_path('upload/video-booth'), $name);
                    PhotoBoothMedia::create([
                        'booth_id' => $boothID,
                        'media_type' => 'video',
                        'media' => $name,
                        'status' => 1,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Photo Booth created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception => ' . $e->getMessage()], 500);
        }
    }



    public function updatePhotoBooth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'photo_booth_id' => 'required',
                'title' => 'required|string|max:255|min:6',
                'price' => 'required|min:0',
                'description' => 'required',
                'cancellation_policy' => 'required',
                'delete_days' => 'required',
                'users_id.*' => 'required',
                'image.*' => 'image|max:5120', // Max 5MB per image
                'video.*' => 'file|mimes:mp4,mov,avi|max:102400', // Max 100MB per video
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Find the existing photo booth
            $photoBooth = PhotoBooth::findOrFail($request->photo_booth_id);
            $existingImageCount = PhotoBoothMedia::where('booth_id', $request->photo_booth_id)
                ->where('media_type', 'Image')
                ->count();

            $existingVideoCount = PhotoBoothMedia::where('booth_id', $request->photo_booth_id)
                ->where('media_type', 'Video')
                ->count();
    
            // Calculate the total count of images and videos after the update
            $newImageCount = $existingImageCount + (is_array($request->file('image')) ? count($request->file('image')) : 0);
            $newVideoCount = $existingVideoCount + (is_array($request->file('video')) ? count($request->file('video')) : 0);
    
            // Ensure the total count doesn't exceed the limits
            if ($newImageCount > 20 || $newVideoCount > 3) {
                return response()->json(['error' => 'You can only upload a maximum of 20 images and 3 videos.'], 400);
            }
            $usersId = is_array($request->users_id) ? $request->users_id : explode(',', $request->users_id);
            // Update the photo booth details
            $photoBooth->update([
                'title' => $request->title,
                'delete_days' => $request->delete_days,
                'users_id' => implode(',', $usersId),
                'price' => $request->price,
                'tour_id' => $request->tour_id,
                'description' => $request->description,
                'cancellation_policy' => $request->cancellation_policy,
                'status' => 1,
                'created_at' => now(),
            ]);
    
            // Save new images
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $imageName = 'IMG_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $image->extension();
                    $image->move(public_path('upload/photo-booth'), $imageName);
                    $photoBooth->media()->create([
                        'media_type' => 'Image',
                        'media' => $imageName,
                        'status' => 1,
                    ]);
                }
            }
    
            // Save new videos
            if ($request->hasFile('video')) {
                foreach ($request->file('video') as $video) {
                    $videoName = 'VID_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $video->extension();
                    $video->move(public_path('upload/video-booth'), $videoName);
                    $photoBooth->media()->create([
                        'media_type' => 'Video',
                        'media' => $videoName,
                        'status' => 1,
                    ]);
                }
            }
    
            return response()->json(['success' => true, 'message' => 'Photo Booth updated successfully'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => 'Exception => ' . $e->getMessage()], 500);
        }
    }


    public function VirtualTourFilter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable',
                'date' => 'nullable|date_format:d/m/y',
                'tour_id' => 'nullable',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $validated = $validator->validated();
            $search = isset($validated['search']) ? $validated['search'] : '';
            $tour_id = isset($validated['tour_id']) ? $validated['tour_id'] : '';
            $date = isset($validated['date']) ? $validated['date'] : '';
            $dbquery = TourBooking::where('tour_type', 2);/*1:Normal tour booking, 2:Virtual tour bppking*/

            if($search){
                $dbquery->where(function($query) use ($search) {
                    $query->where('user_name', 'LIKE', "%$search%")
                          ->orWhere('total_amount', 'LIKE', "%$search%");
                });
            }

            if($tour_id){
                $dbquery->where('tour_id', $tour_id);
            }

            if($date){
                $date = Carbon::createFromFormat('d/m/y', $date)->toDateString();
                $dbquery->whereDate('booking_date', $date);
            }
            
            $data =  $dbquery->orderBy('id', 'DESC')->paginate(10);
            return response()->json([
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }


    public function PhotoBoothFilter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable',
                'date' => 'nullable|date_format:d/m/y',
                'booth_id' => 'nullable',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $validated = $validator->validated();
            $search = isset($validated['search']) ? $validated['search'] : '';
            $booth_id = isset($validated['booth_id']) ? $validated['booth_id'] : '';
            $date = isset($validated['date']) ? $validated['date'] : '';
            $dbquery = BookingPhotoBooth::query();/*1:Normal tour booking, 2:Virtual tour bppking*/

            if($search){
                $dbquery->where(function($query) use ($search) {
                    $query->where('user_name', 'LIKE', "%$search%")
                          ->orWhere('total_amount', 'LIKE', "%$search%");
                });
            }

            if($booth_id){
                $dbquery->where('booth_id', $booth_id);
            }

            if($date){
                $date = Carbon::createFromFormat('d/m/y', $date)->toDateString();
                $dbquery->whereDate('booking_date', $date);
            }
            
            $data =  $dbquery->orderBy('id', 'DESC')->paginate(10);
            return response()->json([
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }



    public function BookTaxiFilter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search' => 'nullable',
                'date' => 'nullable|date_format:d/m/y',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $validated = $validator->validated();
            $search = isset($validated['search']) ? $validated['search'] : '';
            $date = isset($validated['date']) ? $validated['date'] : '';
            $dbquery = TaxiBooking::query();

            if($search){
                $dbquery->where(function($query) use ($search) {
                    $query->where('user_name', 'LIKE', "%$search%")
                          ->orWhere('booking_id', 'LIKE', "%$search%");
                });
            }
            if($date){
                $date = Carbon::createFromFormat('d/m/y', $date)->toDateString();
                $dbquery->whereDate('booking_date', $date);
            }
            
            $data =  $dbquery->orderBy('id', 'DESC')->paginate(10);
            return response()->json([
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }



    public function fetchPlaceSuggestions(Request $request)
    {
        // Get the search query from the request
        $query = $request->input('query');
        $accessToken = 'pk.eyJ1IjoidXNlcnMxIiwiYSI6ImNsdGgxdnpsajAwYWcya25yamlvMHBkcGEifQ.qUy8qSuM_7LYMSgWQk215w';

        // Construct the API URL with the query and access token
        $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . urlencode($query) . ".json?access_token=" . $accessToken;

        // Create a Guzzle HTTP client
        $client = new \GuzzleHttp\Client();

        try {
            // Send a GET request to the API endpoint
            $response = $client->get($url);

            // Decode the JSON response
            $data = json_decode($response->getBody(), true);

            // Process the response data (list of suggestions)
            $suggestions = collect($data['features'])->map(function ($feature) {
                // Extract the place name, longitude, and latitude
                return [
                    'place_name' => $feature['place_name'],
                    'longitude' => $feature['center'][0],
                    'latitude' => $feature['center'][1]
                ];
            });

            // Return the suggestions as JSON response
            return response()->json(['suggestions' => $suggestions]);
        } catch (\Exception $e) {
            // Handle any errors (e.g., API request failed)
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getNotification(Request $request)
    {
        try {
            $user = Auth::user();
            $notifications = Notification::where('user_id',$user->id)->where('status',0)->get();

            $success['notifications'] = $notifications;
            return response()->json(["status" => true, "message" => "Notifications", "data" => $success]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }


    public function ClearNotification(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'clear' => 'nullable|boolean'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $validated = $validator->validated();
            $user = Auth::user();
            $notifications = Notification::where('user_id',$user->id)->where('status',0)->get();
            if($validated['clear'] == 1){
                foreach($notifications as $val){
                    $val->update(['status'=> 1]);
                }
                
            }
            return response()->json(["status" => true, "message" => "Notifications Cleared Successfully"]);
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

}