<?php

namespace App\Http\Controllers;

use App\Exports\YourExport;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $Tourrequests = TourBooking::where('status', 0)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.home', compact('Tourrequests'));
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


        $taxi_booking_requests = DB::table('book_taxis')->where("user_id", $data->id)->when(request()->has('date'), function ($query) {
            return $query->whereDate('booking_time', Carbon::parse(request('date')));
        })->orderBy("id", "desc")->paginate(config("app.records_per_page"));

        foreach ($taxi_booking_requests as $item) {
            $item->user = User::find($item->user_id);
        }


        // Retrieve user payment methods and convert them to an array
        $user_payment_methods = DB::table("user_payment_methods")->where("userid", $id)->pluck("id")->toArray();

        // Calculate the total amount paid by the user based on successful transactions
        $total_amount = DB::table("payment_details")->whereIn("user_payment_method_id", $user_payment_methods)->where("status", 1)->sum("amount");

        // Pass data to the 'admin.user-detail' view
        return view('admin.user-detail', compact('data', 'normal_tours', 'virtual_tours', 'PhotoBooths', 'total_amount', 'taxi_booking_requests'));
    }

    public function AddTour()
    {
        $data = null;
        return view('admin.add-edit-tour', compact('data'));
    }

    public function EditTour($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $data = Tour::where('id', $id)->first();
        return view('admin.add-edit-tour', compact('data'));
    }

    public function EditVirtualTour($id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $data = VirtualTour::where('id', $id)->first();
        return view('admin.add-edit-virtual-tour', compact('data'));
    }

    public function DeleteTour(Request $request)
    {
        $data = Tour::where('id', $request->id)->update(['status' => 3]);/*3:Delete*/
        return redirect()->back()->with('success', 'Tour deleted successfully');
    }

    public function DeleteVirtualTour(Request $request)
    {
        $data = VirtualTour::where('id', $request->id)->update(['status' => 3]);/*0:Pending,1:Approved, 3:Delete*/
        return redirect()->back()->with('success', 'Virtual Tour deleted successfully');
    }

    public function tours()
    {
        $datas = Tour::where('status', 1)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.tours', compact('datas'));
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
            if ($file = $request->file('audio')) {
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->audio_file = $name;
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
            if ($file = $request->file('audio')) {
                $destination = public_path('upload/virtual-audio');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $Tour->audio_file = $name;
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
            $Tourrequests = TourBooking::where('status', 0)->orderBy('id', 'DESC')->paginate(10);
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
            return view('admin.manage-booking', compact('Tourrequests', 'tours'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

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
    public function InquiryRequest()
    {
        try {
            $datas = CallbackRequest::where('status', 1)->orderBy('id', 'DESC')->paginate(10);
            return view('admin.tour-inquiry-request', compact('datas'));
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
            $bookings = TourBooking::where('tour_type', 2)->where('status', 0)->orderBy('id', 'DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            return view('admin.manage-photo-booth', compact('PhotoBooths', 'bookings'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Add a details of Photo/Video tour*/
    public function AddPhoto()
    {
        try {
            $tours = Tour::where('status', 1)->orderBy('id', 'DESC')->get();
            $data = null;
            return view('admin.add-edit-photo-booth', compact('tours', 'data'));
        } catch (\Exception $e) {
            return errorMsg("Exception -> " . $e->getMessage());
        }
    }

    /*Add a details of Photo/Video tour*/
    public function TaxiBookingRequest()
    {
        try {
            $tours = VirtualTour::where('status', 1)->orderBy('id', 'DESC')->get();
            $bookings = TourBooking::where('tour_type', 2)->where('status', 0)->orderBy('id', 'DESC')->paginate(10);/*1:Normal tour booking, 2:Virtual tour bppking*/
            $taxi_booking_requests = DB::table('book_taxis')->when(request()->has('date'), function ($query) {
                return $query->whereDate('booking_time', Carbon::parse(request('date')));
            })->orderBy("id", "desc")->paginate(config("app.records_per_page"));

            foreach ($taxi_booking_requests as $item) {
                $item->user = User::find($item->user_id);
            }
            return view('admin.taxi-booking-request', compact('tours', 'bookings', 'taxi_booking_requests'));
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

    /*Search live users*/
    public function loadSectors(Request $request)
    {
        $users = [];

        if ($request->has('q')) {
            $search = $request->q;
            $users = User::select("id", "fullname")->where('status', 1)->where('type', 2)
                ->where('fullname', 'LIKE', "%$search%")
                ->get();
        } else {

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
                'price' => 'required',
                'description' => 'required',
                'cancellation_policy' => 'required',
                //'image[]' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $boothID = PhotoBooth::insertGetId([
                'title' => $request->title,
                'users_id' => implode(',', $request->users),
                'price' => $request->price,
                'tour_id' => $request->tour_id,
                'description' => $request->description,
                'cancellation_policy' => $request->cancellation_policy,
                'status' => 1,
            ]);
            if ($files = $request->file('image')) {
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

            $photo = PhotoBooth::find($request->pid);
            if ($file = $request->file('image')) {
                $destination = public_path('upload/photo-booth');
                $name = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $file->extension();
                $file->move($destination, $name);
                $photo->audio_file = $name;
                $Attributes = PhotoBoothMedia::create([
                    'booth_id' => $request->pid,
                    'media_type' => 'Image',
                    'media' => $name,
                    'status' => 1,
                ]);
            }
            if ($files = $request->file('video')) {
                $destination_file = public_path('upload/video-booth');
                $name_file = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $files->extension();
                $files->move($destination_file, $name_file);
                $photo->thumbnail_file = $name_file;
                $Attributes = PhotoBoothMedia::create([
                    'booth_id' => $request->pid,
                    'media_type' => 'video',
                    'media' => $name,
                    'status' => 1,
                ]);
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
        return view('admin.add-edit-photo-booth', compact('data', 'tours'));
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
        //dd(55);
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
}
