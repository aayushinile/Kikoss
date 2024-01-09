<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourAttribute;
use App\Models\CallbackRequest;
use App\Models\VirtualTour;
use App\Models\PhotoBooth;
use App\Models\PhotoBoothMedia;
use App\Models\TaxiBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
        // Showing All datas of app like tourbooking
        $Tourrequests = TourBooking::with("Tour")->where('status', 1)->orderBy('id', 'DESC')->paginate(10);
        $booked_dates = TourBooking::where('status', 1)->groupBy('booking_date')->pluck('booking_date');
        return view('admin.home', compact('Tourrequests', 'booked_dates'));
    }

    public function users()
    {
        $datas = User::where('type', 2)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users', compact('datas'));
    }

    public function userDetail($id)
    {
        // Decrypt the user ID using the encrypt_decrypt function
        $id = encrypt_decrypt('decrypt', $id);

        // Retrieve user data based on the decrypted ID
        $data = User::where('id', $id)->first();

        // Retrieve normal tours and attach transaction details to each booking
        $normal_tours = TourBooking::where("tour_type", 1)->orderBy("id")->when(request()->has('date'), function ($query) {
            return $query->whereDate('booking_date', Carbon::parse(request('date')));
        })->paginate(config("app.records_per_page"));
        foreach ($normal_tours as $item) {
            // Retrieve transaction details for the current booking
            $transaction = DB::table("payment_details")->where("booking_id", $item->id)->first();
            // Attach transaction details to the current booking
            $item->transaction = $transaction ? $transaction : null;
        }

        // Retrieve virtual tours and attach transaction details to each booking
        $virtual_tours = TourBooking::where("tour_type", 2)->when(request()->has('date'), function ($query) {
            return $query->whereDate('booking_date', Carbon::parse(request('date')));
        })->orderBy("id")->paginate(config("app.records_per_page"));
        foreach ($virtual_tours as $item) {
            // Retrieve transaction details for the current booking
            $transaction = DB::table("payment_details")->where("booking_id", $item->id)->first();
            // Attach transaction details to the current booking
            $item->transaction = $transaction ? $transaction : null;
        }

        // Retrieve Photo Booths and attach transaction details to each booking
        $PhotoBooths = TourBooking::where("tour_type", 3)->when(request()->has('date'), function ($query) {
            return $query->whereDate('booking_date', Carbon::parse(request('date')));
        })->orderBy("id")->paginate(config("app.records_per_page"));
        foreach ($PhotoBooths as $item) {
            // Retrieve transaction details for the current booking
            $transaction = DB::table("payment_details")->where("booking_id", $item->id)->first();
            // Attach transaction details to the current booking
            $item->transaction = $transaction ? $transaction : null;
        }

        // Count the total number of tour bookings for the user


        // Retrieve user payment methods and convert them to an array
        $user_payment_methods = DB::table("user_payment_methods")->where("userid", $id)->pluck("id")->toArray();

        // Calculate the total amount paid by the user based on successful transactions
        $total_amount = DB::table("payment_details")->whereIn("user_payment_method_id", $user_payment_methods)->where("status", 1)->sum("amount");

        // Pass data to the 'admin.user-detail' view
        $taxi_booking_requests = TaxiBooking::orderBy('id', 'DESC')->paginate(10); //Get all request taxi booking
        return view('admin.user-detail', compact('data', 'normal_tours', 'virtual_tours', 'PhotoBooths', 'total_amount', 'taxi_booking_requests'));
    }

    public function AddTour()
    {
        try {
            // vIEW PAGE OF ADD TOUR WITH NULL DATA(mANAGE ADD EDIT BOTH)
            $data = null;
            return view('admin.add-edit-tour', compact('data'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //Edit tour
    public function EditTour($id)
    {
        try {
            // Decrypt the TOUR ID using the encrypt_decrypt function
            $id = encrypt_decrypt('decrypt', $id);
            // VIEW PAGE OF EDIT TOUR WITH NULL DATA(ACCODING TO TOUR ID)
            $data = Tour::where('id', $id)->first();
            $images = TourAttribute::where('tour_id', $id)->get();/*Get all Images od Photo Booth*/
            return view('admin.add-edit-tour', compact('data', 'images'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    // View page of virtual tour
    public function EditVirtualTour($id)
    {
        try {
            // Decrypt the Virtual TOUR ID using the encrypt_decrypt function
            $id = encrypt_decrypt('decrypt', $id);
            // Showing data accoding to virtual tour id 
            $data = VirtualTour::where('id', $id)->first();
            return view('admin.add-edit-virtual-tour', compact('data'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //Delete Tour
    public function DeleteTour(Request $request)
    {
        //Update status of tour
        $data = Tour::where('id', $request->id)->update(['status' => 3]);/*3:Delete*/
        return redirect('tours')->with('success', 'Tour deleted successfully');
    }

    //Delete Virtual Tour
    public function DeleteVirtualTour(Request $request)
    {
        $data = VirtualTour::where('id', $request->id)->update(['status' => 3]);/*0:Pending,1:Approved, 3:Delete*/
        return redirect()->back()->with('success', 'Virtual Tour deleted successfully');
    }

    public function tours()
    {
        $datas = Tour::where('status', 1)->orderBy('id', 'DESC')->paginate(9);
        return view('admin.tours', compact('datas'));
    }

    public function SaveTour(Request $request)
    {
        try {
            if ($request->same_for_all) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:255|min:1',
                    'name' => 'required|string|max:255|min:1',
                    'total_people' => 'required',
                    'duration' => 'required|min:0',
                    'what_to_bring' => 'required',
                    'same_for_all' => 'required',
                    'short_description' => 'required|string|min:3|max:1000',
                    'description' => 'required|string|min:3|max:1000',
                    'cancellation_policy' => 'required|min:3|max:1000',
                    //'thumbnail' => 'required','mimes:jpeg,png,jpg,svg','max:5120',
                ]);

                $price = $request->same_for_all;
                $age_11_price = $price;
                $age_60_price = $price;
                $under_10_age_price = $price;
                $same_for_all = $price;
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:255|min:1',
                    'name' => 'required|string|max:255|min:1',
                    'total_people' => 'required',
                    'duration' => 'required|min:0',
                    'what_to_bring' => 'required',
                    'age_11_price' => 'required|min:0',
                    'age_60_price' => 'required|min:0',
                    'under_10_age_price' => 'required|min:0',
                    'short_description' => 'required|string|min:3|max:1000',
                    'description' => 'required|string|min:3|max:1000',
                    'cancellation_policy' => 'required|min:3|max:1000',
                    //'thumbnail[]' => 'required','thumbnail','mimes:jpeg,png,jpg,svg','max:5120',
                ]);
                $age_11_price = $request->age_11_price;
                $age_60_price = $request->age_60_price;
                $under_10_age_price = $request->under_10_age_price;
                $same_for_all = 0;
            }


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $TourID = Tour::insertGetId([
                'title' => $request->title,
                'name' => $request->name,
                'total_people' => $request->total_people,
                'duration' => $request->duration,
                'what_to_bring' => $request->what_to_bring,
                'age_11_price' => $age_11_price,
                'age_60_price' => $age_60_price,
                'under_10_age_price' => $under_10_age_price,
                'same_for_all' => $same_for_all,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'cancellation_policy' => $request->cancellation_policy,
                'status' => 1,
            ]);
            if ($files = $request->file('thumbnail')) {
                foreach ($files as $j => $file) {
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
            return redirect('tours')->with('success', 'Tour Created successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function UpdateTour(Request $request)
    {
        try {
            if ($request->for_all_price) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:255|min:1',
                    'name' => 'required|string|max:255|min:1',
                    'total_people' => 'required',
                    'duration' => 'required|min:0',
                    'what_to_bring' => 'required',
                    'for_all_price' => 'required|min:0',
                    'short_description' => 'required|string|min:3|max:1000',
                    'description' => 'required|string|min:3|max:1000',
                    'cancellation_policy' => 'required|min:3|max:1000',
                ]);
                $price = $request->for_all_price;
                $age_11_price = $price;
                $age_60_price = $price;
                $under_10_age_price = $price;
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:255|min:1',
                    'name' => 'required|string|max:255|min:1',
                    'total_people' => 'required',
                    'duration' => 'required|min:0',
                    'what_to_bring' => 'required',
                    'age_11_price' => 'required|min:0',
                    'age_60_price' => 'required|min:0',
                    'under_10_age_price' => 'required|min:0',
                    'short_description' => 'required|string|min:3|max:1000',
                    'description' => 'required|string|min:3|max:1000',
                    'cancellation_policy' => 'required|min:3|max:1000',
                ]);
                $age_11_price = $request->age_11_price;
                $age_60_price = $request->age_60_price;
                $under_10_age_price = $request->under_10_age_price;
            }

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }


            $tour = Tour::find($request->pid);
            $tour->title = $request->title;
            $tour->name = $request->name;
            $tour->total_people = $request->total_people;
            $tour->duration = $request->duration;
            $tour->what_to_bring = $request->what_to_bring;
            if ($request->for_all_price) {
                $price = $request->for_all_price;
                $tour->age_11_price = $price;
                $tour->age_60_price = $price;
                $tour->under_10_age_price = $price;
            } else {
                $tour->age_11_price = $request->age_11_price;
                $tour->age_60_price = $request->age_60_price;
                $tour->under_10_age_price = $request->under_10_age_price;
            }
            $tour->short_description = $request->short_description;
            $tour->description = $request->description;
            $tour->cancellation_policy = $request->cancellation_policy;
            if ($files = $request->file('thumbnail')) {
                foreach ($files as $j => $file) {
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
            return redirect('tours')->with('success', 'Tour Updated successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    /*Delete image/video of Photo Booth*/
    public function DeleteTourImage($id)
    {
        try {
            // Decrypt the tour ID using the encrypt_decrypt function
            $id = encrypt_decrypt('decrypt', $id);
            $tour = TourAttribute::where('id', $id)->first();/*Get first data of media  tour*/
            $image_count = TourAttribute::where('tour_id', $tour->tour_id)->count();
            if ($image_count > 1) {
                if (file_exists(public_path('upload/tour-thumbnail/' . $tour->attribute_name))) {
                    unlink(public_path('upload/tour-thumbnail/' . $tour->attribute_name));/*Delete Photo booth image from file*/
                }
                $data = TourAttribute::where('id', $id)->delete();/*Delete Photo booth images or videos*/
                $message = 'Tour image deleted successfully'; //sesions mesages
                $type = 'success'; //sesions type
            } else {
                $message = 'Unable to delete last image'; //sesions mesages
                $type = 'error'; //sesions type
            }
            return redirect()->back()->with($type, $message);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }


    public function SaveVirtualTour(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:1',
                'price' => 'required|min:0',
                'minute' => 'required|min:0',
                'duration' => 'required|min:0',
                'description' => 'required',
                'short_description' => 'required',
                'cancellation_policy' => 'required',
                //'audio' => 'required|max:5120',
                //'trial_audio_file' => 'required|max:5120',
                //'thumbnail' => 'required|mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput();
            }

            $Tour = new VirtualTour;
            if ($file = $request->file('audio')) {
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->audio_file = $name;
            }
            if ($file = $request->file('trial_audio_file')) {
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->trial_audio_file = $name;
            }
            if ($files = $request->file('thumbnail')) {
                $destination_file = public_path('upload/virtual-thumbnail');
                $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $files->extension();
                $files->move($destination_file, $name_file);
                $Tour->thumbnail_file = $name_file;
            }
            $Tour->name = $request->name;
            $Tour->price = $request->price;
            $Tour->minute = $request->minute;
            $Tour->duration = $request->duration;
            $Tour->description = $request->description;
            $Tour->short_description = $request->short_description;
            $Tour->cencellation_policy = $request->cancellation_policy;
            $Tour->status = 1;
            $Tour->save();

            return redirect('manage-virtual-tour')->with('success', 'Virtual Tour Created successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //Udpate virtual tour
    public function UpdateVirtualTour(Request $request)
    {
        try {

            //Validation of virtual tour
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:1',
                'price' => 'required',
                'minute' => 'required',
                'duration' => 'required',
                'description' => 'required',
                'short_description' => 'required',
                'cancellation_policy' => 'required',
                'audio' => 'max:5120',
                'trial_audio_file' => 'max:5120',
                //'thumbnail' => 'mimes:jpeg,png,jpg,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $Tour = VirtualTour::find($request->pid);
            if ($file = $request->file('audio')) {
                try {
                    if (file_exists(public_path('upload/virtual-audio/' . $Tour->audio_file))) {
                        unlink(public_path('upload/virtual-audio/' . $Tour->audio_file));/*Delete audio file of virtual tour */
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->audio_file = $name;
            }
            if ($file = $request->file('trial_audio_file')) {
                try {
                    if (file_exists(public_path('upload/virtual-audio/' . $Tour->trial_audio_file))) {
                        unlink(public_path('upload/virtual-audio/' . $Tour->trial_audio_file));/*Delete audio file of virtual tour */
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }

                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->trial_audio_file = $name;
            }
            if ($files = $request->file('thumbnail')) {
                if (file_exists(public_path('upload/virtual-thumbnail/' . $Tour->thumbnail_file))) {
                    unlink(public_path('upload/virtual-thumbnail/' . $Tour->thumbnail_file));/*Delete thumbnail file of virtual tour */
                }
                $destination_file = public_path('upload/virtual-thumbnail');
                $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $files->extension();
                $files->move($destination_file, $name_file);
                $Tour->thumbnail_file = $name_file;
            }

            $Tour->name = $request->name;
            $Tour->price = $request->price;
            $Tour->minute = $request->minute;
            $Tour->duration = $request->duration;
            $Tour->description = $request->description;
            $Tour->short_description = $request->short_description;
            $Tour->cencellation_policy = $request->cancellation_policy;
            $Tour->save();
            return redirect('manage-virtual-tour')->with('success', 'Virtual Tour Updated successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    public function ManageBooking()
    {
        try {
            $Tourrequests = TourBooking::where('status', 0)->orderBy('id', 'DESC')->paginate(15);
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
            return view('admin.manage-booking', compact('Tourrequests', 'tours'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Get all data of Virtual-tour */
    public function ManageVirtualTour()
    {
        try {
            $tours = VirtualTour::where('status', 1)->orderBy('id', 'DESC')->get();
            $bookings = TourBooking::where('tour_type', 2)->where('status', 0)->orderBy('id', 'DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            return view('admin.manage-virtual-tour', compact('tours', 'bookings'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function AddVirtualTour()
    {
        try {
            $data = null;
            return view('admin.add-edit-virtual-tour', compact('data'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Callback request listing */
    public function CallbackRequest()
    {
        try {
            $datas = CallbackRequest::orderBy('id', 'DESC')->paginate(10);
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
            return view('admin.tour-callback-request', compact('datas', 'tours'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    public function ViewTransactionHistory()
    {
        try {
            $datas = Tour::where('status', 1)->orderBy('id', 'DESC')->paginate(10);
            return view('admin.view-transaction-history', compact('datas'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Accept Booking Request*/
    public function AcceptTourBooking($id)
    {
        try {
            $datas = TourBooking::where('id', $id)->update(['status' => 1]);/*0:pending,1:accept,2:reject */
            return redirect()->back();
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Reject Booking Request*/
    public function RejectTourBooking($id)
    {
        try {
            $datas = TourBooking::where('id', $id)->update(['status' => 2]);/*0:pending,1:accept,2:reject */
            return redirect()->back();
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*listing of tour Photo/Video */
    public function ManagePhotoBooth()
    {
        try {
            $PhotoBooths = PhotoBooth::where('status', 1)->orderBy('id', 'DESC')->get();
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
            $bookings = TourBooking::where('tour_type', 2)->where('status', 0)->orderBy('id', 'DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            return view('admin.manage-photo-booth', compact('PhotoBooths', 'bookings', 'tours'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Add a details of Photo/Video tour*/
    public function AddPhoto()
    {
        try {
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();/*Get listing of tour*/
            $data = null;/*Empty booth data to handle add page*/
            $images = [];/*Empty Images to handle add page*/
            $videos = [];/*Empty Videos to handle add page*/
            return view('admin.add-edit-photo-booth', compact('tours', 'data', 'images', 'videos'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Add a details of Photo/Video tour*/
    public function TaxiBookingRequest()
    {
        try {
            $bookings = TaxiBooking::orderBy('id', 'DESC')->paginate(10); //Get all request taxi booking
            return view('admin.taxi-booking-request', compact('bookings'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Listing of virtual tour transaction history*/
    public function VirtualTransactionHistory()
    {
        try {
            $tours = VirtualTour::where('status', 1)->orderBy('id', 'DESC')->get();
            $bookings = TourBooking::where('tour_type', 2)->where('status', 0)->orderBy('id', 'DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            return view('admin.virtual-transaction-history', compact('tours', 'bookings'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Listing of virtual tour transaction history*/
    public function PhotoTransactionHistory()
    {
        try {
            $tours = VirtualTour::where('status', 1)->orderBy('id', 'DESC')->get();
            $bookings = TourBooking::where('tour_type', 2)->where('status', 0)->orderBy('id', 'DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            return view('admin.photo-transaction-history', compact('tours', 'bookings'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Search live listing of users*/
    public function loadSectors(Request $request)
    {
        $users = [];

        if ($request->has('q')) {/*Search request data*/
            $search = $request->q;
            /*Listing all active users accoding to search data*/
            $users = User::select("id", "fullname")->where('status', 1)->where('type', 2)
                ->where('fullname', 'LIKE', "%$search%")
                ->get();
        } else {
            /*Listing all active users*/
            $users = User::select("id", "fullname")->where('status', 1)->where('type', 2)
                ->get();
        }
        return response()->json($users);
    }

    /*Save Photo Booth*/
    public function SavePhotoBooth(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'tour_id' => 'required',
                'title' => 'required|string|max:255|min:1',
                'price' => 'required|min:0',
                'description' => 'required',
                'cancellation_policy' => 'required',
                //'image[]' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            /*Save all input data of photobooth */
            $boothID = PhotoBooth::insertGetId([
                'title' => $request->title,
                'users_id' => implode(',', $request->users),/*Multiple user id change into implode like 2,3,4 */
                'price' => $request->price,
                'tour_id' => $request->tour_id,
                'description' => $request->description,
                'cancellation_policy' => $request->cancellation_policy,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            if ($files = $request->file('image')) {
                /*Save Multiple photos */
                foreach ($files as $j => $file) {
                    $destination = public_path('upload/photo-booth');
                    $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                    $file->move($destination, $name);
                    $Attributes = PhotoBoothMedia::create([
                        'booth_id' => $boothID,
                        'media_type' => 'Image',/* media_type:Image/Video */
                        'media' => $name,
                        'status' => 1,
                    ]);
                }
            }
            if ($files = $request->file('video')) {
                /*Save Multiple videos */
                foreach ($files as $j => $file) {
                    $destination = public_path('upload/video-booth');
                    $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                    $file->move($destination, $name);
                    $Attributes = PhotoBoothMedia::create([
                        'booth_id' => $boothID,
                        'media_type' => 'video',/* media_type:Image/Video */
                        'media' => $name,
                        'status' => 1,
                    ]);
                }
            }

            return redirect('manage-photo-booth')->with('success', 'Photo Booth Created successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    /*Update Photo Booth*/
    public function UpdatePhotoBooth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tour_id' => 'required',
                'title' => 'required|string|max:255|min:1',
                'price' => 'required',
                'description' => 'required',
                'cancellation_policy' => 'required',
                //'image[]' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            /*Update photo booth data */
            $photo = PhotoBooth::find($request->pid);
            if ($files = $request->file('image')) {
                /*Save Multiple photo */
                foreach ($files as $j => $file) {
                    $destination = public_path('upload/photo-booth');
                    $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                    $file->move($destination, $name_file);
                    $Attributes = PhotoBoothMedia::create([
                        'booth_id' => $request->pid,/* Photobooth ID */
                        'media_type' => 'Image',/* media_type:Image/Video */
                        'media' => $name_file,
                        'status' => 1,
                    ]);
                }
            }
            if ($files = $request->file('video')) {
                /*Save Multiple videos */
                foreach ($files as $j => $file) {
                    $destination_file = public_path('upload/video-booth');
                    $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                    $file->move($destination_file, $name_file);
                    $Attributes = PhotoBoothMedia::create([
                        'booth_id' => $request->pid,/* Photo booth ID */
                        'media_type' => 'video',/* media_type:Image/Video */
                        'media' => $name_file,
                        'status' => 1,
                    ]);
                }
            }

            $photo->tour_id = $request->tour_id;
            $photo->title = $request->title;
            $photo->price = $request->price;
            $photo->description = $request->description;
            $photo->cancellation_policy = $request->cancellation_policy;
            $photo->save();
            return redirect('manage-photo-booth')->with('success', 'Photo Booth Updated successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    /*Edit Photo Booth*/
    public function EditPhotoBooth($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
        $data = PhotoBooth::where('id', $id)->first();
        $images = PhotoBoothMedia::where('media_type', 'Image')->where('booth_id', $id)->get();/*Get all Images od Photo Booth*/
        $videos = PhotoBoothMedia::where('media_type', 'Video')->where('booth_id', $id)->get();/*Get all videoes od Photo Booth*/
        return view('admin.add-edit-photo-booth', compact('data', 'tours', 'images', 'videos'));
    }

    /*Delete Photo Booth*/
    public function DeletePhotoBooth(Request $request)
    {
        try {
            $data = PhotoBooth::where('id', $request->photo_booth_id)->update(['status' => 3]);/*0:Active, 1:Active, 3:Delete*/
            return redirect()->back()->with('success', 'Photo booth deleted successfully');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    /*Delete image/video of Photo Booth*/
    public function DeletePhotoBoothImage($id)
    {
        try {
            // Decrypt the Photo Booth ID using the encrypt_decrypt function
            $id = encrypt_decrypt('decrypt', $id);
            $media = PhotoBoothMedia::where('id', $id)->first();/*Get first data of media  Photo booth*/
            if ($media->media_type == 'Image') {
                $image_count = PhotoBoothMedia::where('media_type', 'Image')->where('booth_id', $media->booth_id)->count();
                if ($image_count > 1) {
                    if (file_exists(public_path('upload/photo-booth/' . $media->media))) {
                        unlink(public_path('upload/photo-booth/' . $media->media));/*Delete Photo booth image from file*/
                    }
                    $data = PhotoBoothMedia::where('id', $id)->delete();/*Delete Photo booth images or videos*/
                    $message = 'Photo booth image deleted successfully'; //sesions mesages
                    $type = 'success'; //sesions type
                } else {
                    $message = 'Unable to delete last image'; //sesions mesages
                    $type = 'error'; //sesions type
                }
            } else {
                $video_count = PhotoBoothMedia::where('media_type', 'Video')->where('booth_id', $media->booth_id)->count();
                if ($video_count > 1) {
                    if (file_exists(public_path('upload/video-booth/' . $media->media))) {
                        unlink(public_path('upload/video-booth/' . $media->media));/*Delete Photo booth image from file*/
                    }
                    $message = 'Photo booth video deleted successfully'; //sesions mesages
                    $type = 'success'; //sesions type 
                    $data = PhotoBoothMedia::where('id', $id)->delete();/*Delete Photo booth images or videos*/
                } else {
                    $message = 'Unable to delete last video'; //sesions mesages
                    $type = 'error'; //sesions type
                }
            }
            return redirect()->back()->with($type, $message);
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    /* Profile Admin*/
    public function profile()
    {
        try {
            $data = Auth::user();
            return view('admin.profile', compact('data'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    /* Function for change password with login or without login*/
    public function UpdatePassword(Request $request)
    {
        try {
            $data = array();
            $new_password = $request->new_password;
            $old_password = $request->old_password;
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|min:8',
                'new_password' => 'required|min:8',
                'confirm_new_password' => 'required|same:new_password',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            if (!empty($old_password)) {
                /*Checking old password is same or not */
                if ((Hash::check($request->old_password, $user->password)) == false) {
                    return redirect()->back()->with('error', 'Check your old password.');
                }
            }
            $id = User::where('id', $user->id)->update(['password' => Hash::make($new_password)]);

            if (!empty($id)) {
                return redirect()->back()->with('success', 'Password change successfully');
            } else {
                return redirect()->back()->with('error', 'Something went wrong!');
            }
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /* Function for change password with login or without login*/
    public function UpdateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|string|max:255|min:1',
                'email' => 'required|email',
                'mobile' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $users = User::where('id', $user->id)->first();
            $users->email = $request->email;
            $users->fullname = $request->fullname;
            $users->mobile = $request->mobile;
            if ($file = $request->file('user_profile')) {
                /*Upload Admin profile*/
                $destination = public_path('upload/profile');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $users->user_profile = $name;
            }
            $users->save();
            return redirect()->back()->with('success', 'Profile uploaded successfully');
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    // Tour Details
    public function TourDetails($id)
    {
        try {
            // Decrypt the TOUR ID using the encrypt_decrypt function
            $id = encrypt_decrypt('decrypt', $id);
            // Showing data accoding to virtual tour id 
            $data = Tour::where('id', $id)->first();
            return view('admin.tour-details', compact('data'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }

    //Live Search of tours
    public function live_tours(Request $request)
    {
        $query = $request['query'];

        $datas = Tour::where('title', 'like', '%' . $query . '%')
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get();
        $i = 1;
        $table_data = '';
        if ($datas->count() > 0) {
            foreach ($datas as $val) {
                $table_data .= '
                    <div class="col-md-4">
                        <div class="manage-tour-card">
                            <div class="manage-tour-card-media">
                                <img src="' . assets('assets/admin-images/IMG_9838.jpg') . '">
                            </div>
                            <div class="manage-tour-card-content">
                                <div class="manage-tour-card-text">
                                    <h3>' . $val->title . '</h3>
                                    <p>' . $val->name  . ' • ' . $val->duration . ' Hours</p>
                                    <div class="price-text">US$' . $val->under_10_age_price . ' –
                                        US$' . $val->age_11_price . '</div>
                                    </div>
                                    <div class="manage-tour-card-action">
                                        <a href="' . url('tour-detail/' . encrypt_decrypt('encrypt', $val->id)) . '">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
        } else {
            $table_data = '<tr>
                <td colspan="10">
                    <h5 style="text-align: center">No Record Found</h5>
                </td>
            </tr>';
        }
        echo json_encode($table_data);
    }

    //Live Search of users
    public function live_users(Request $request)
    {
        $query = $request['query'];

        $datas = User::where('fullname', 'like', '%' . $query . '%')
            ->Orwhere('email', 'like', '%' . $query . '%')
            ->Orwhere('mobile', 'like', '%' . $query . '%')
            ->where('type', '!=', 1)
            ->orderBy('id', 'DESC')
            ->limit(50)
            ->get();
        $i = 1;
        $table_data = '';
        if ($datas->count() > 0) {
            foreach ($datas as $val) {
                $table_data .= '
                <tr>
                    <td>
                       ' . $i++ . '
                    </td>
                    <td>
                        ' . $val->fullname  . '
                    </td>

                    <td>
                        ' . $val->email  . '
                    </td>
                    <td>
                        ' . '+1 ' . $val->mobile  . '
                    </td>
                    <td>
                        ' . date('d M, Y, h:i:s a', strtotime($val->created_at)) . '
                    </td>
                <td>
                    <div class="action-btn-info">
                    <a class="dropdown-item view-btn"
                    href="' . url('user-details/' . encrypt_decrypt('encrypt', $val->id)) . '"><i
                        class="las la-eye"></i> View</a>
                    </div>
                </td>
            </tr>';
            }
        } else {
            $table_data = '<tr>
                <td colspan="10">
                    <h5 style="text-align: center">No Record Found</h5>
                </td>
            </tr>';
        }
        echo json_encode($table_data);
    }

    //Live Search of users
    public function search_name(Request $request)
    {
        //Search by user name, Tour name, Date
        $query = $request['query'];
        $tour_id = $request['tour_id'];
        $Date = $request['Date'];

        if (isset($query)) {
            $datas = TourBooking::where('user_name', 'like', '%' . $query . '%')
                ->where('tour_type', 1) //1:Normal tour, 2:Virtual Tour
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get();
        } elseif ($tour_id) {
            $datas = TourBooking::where('tour_id', 'like', '%' . $tour_id . '%')
                ->where('tour_type', 1) //1:Normal tour, 2:Virtual Tour
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get();
        } elseif ($Date) {
            $datas = TourBooking::where('booking_date', 'like', '%' . $Date . '%')
                ->where('tour_type', 1) //1:Normal tour, 2:Virtual Tour
                ->orderBy('id', 'DESC')
                ->limit(50)
                ->get();
        }

        $i = 1;
        $table_data = '';
        if ($datas->count() > 0) {
            foreach ($datas as $val) {
                $table_data .= '
                <tr>
                    <td>
                        <div class="sno">' . $i++ . '</div>
                    </td>
                    <td>' . $val->Users->fullname . '</td>
                    <td>' . $val->Tour->title . '</td>
                    <td>' . $val->Tour->duration . ' Hours</td>
                    <td>' . date('Y-m-d', strtotime($val->booking_date)) . '</td>
                    <td>
                        <div class="status-text Pending-status"><i class="las la-hourglass-start"></i> Pending for Approval</div>
                    </td>
                    <td>
                        <div class="action-btn-info">
                            <a class="dropdown-item view-btn" data-bs-toggle="modal"
                                href="#BookingRequest"
                                onclick="accept_tour(' . $val->id . ',' . $val->Tour->title . ',' . $val->booking_date . ',' . $val->Tour->duration . ',' . $val->total_amount . ')"
                                role="button"><i class="las la-eye"></i> View</a>
                            
                        </div>
                    </td>
                </tr>';
            }
        } else {
            $table_data = '<tr>
                <td colspan="10">
                    <h5 style="text-align: center">No Record Found</h5>
                </td>
            </tr>';
        }
        echo json_encode($table_data);
    }

    //Live Search of Callback listing
    public function live_callbacks(Request $request)
    {
        $query = $request['query'];
        $tour_id = $request['tour_id'];
        $Date = $request['Date'];

        if (isset($query)) {
            $datas = CallbackRequest::where('name', 'like', '%' . $query . '%')
                ->Orwhere('mobile', 'like', '%' . $query . '%')
                ->orderBy('id', 'DESC')
                ->limit(20)
                ->get();
        } elseif ($tour_id) {
            $datas = CallbackRequest::where('tour_id', $tour_id)
                ->orderBy('id', 'DESC')
                ->limit(20)
                ->get();
        } elseif ($Date) {
            $datas = CallbackRequest::whereDate('preferred_time', '=', '2024-01-04')
                ->orderBy('id', 'DESC')
                ->limit(20)
                ->get();
        }

        $i = 1;
        $table_data = '';
        if ($datas->count() > 0) {
            foreach ($datas as $val) {
                $table_data .= '
                <tr>
                    <td>
                        <div class="sno">' . $i++ . '</div>
                    </td>
                    <td>' . $val->name . '</td>
                    <td>' . $val->TourName->name . '</td>
                    <td>' . '+1 ' . $val->mobile . '</td>
                    <td>' . $val->TourName->duration . ' Hours</td>
                    <td>' . date('d M, Y, h:i:s a', strtotime($val->preferred_time)) . '
                    </td>
                    <td>
                        <div class="switch-toggle">
                            <div class="">
                                <label class="toggle" for="myToggleClass_' . $i++ . '">
                                <input class="toggle__input myToggleClass_"
                                    "@if (' . $val->status . ' = 1) checked @endif"
                                        name="status" data-id="' . $val->id . '" type="checkbox" id="myToggleClass_' . $i++ . '">
                                <div class="toggle__fill"></div>
                                </label>
                            </div>
                        </div>
                    </td>
                    <td>' . substr($val->note, 0, 30) . '<a class="infoRequestMessage" data-bs-toggle="modal"
                            href="#infoRequestMessage" onclick="GetData(' . $val->note . ')"
                                role="button"><i class="las la-info-circle"></i></a>
                    </td>
                </tr>';
            }
        } else {
            $table_data = '<tr>
                <td colspan="10">
                    <h5 style="text-align: center">No Record Found</h5>
                </td>
            </tr>';
        }
        echo json_encode($table_data);
    }
}
