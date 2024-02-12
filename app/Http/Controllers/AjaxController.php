<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallbackRequest;
use App\Models\Event;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AjaxController extends Controller
{
    public function privacy_policy()
    {
        return view('admin.privacy_policy');
    }
    
    public function about_us()
    {
        return view('admin.about_us');
    }
    
    public function term_condition()
    {
        return view('admin.term_condition');
    }
    
    public function getEvents()
    {
        //dd('in');
        $events = Event::all();
        return response()->json($events);
    }
    public function getEventsSet2()
    {
        //$eventsSet2 = TourBooking::where('tour_type',1)->where('status',1)->get();
        return response()->json($eventsSet2);
    }

    public function addEvent(Request $request)
    {
        //dd($request->all());
        // $data = $request->validate([
        //     'title' => 'required|string',
        //     'start' => 'required|date',
        //     'end' => 'nullable|date',
        //     //'color' => 'nullable|string',
        // ]);

        if($request->datesstatustype == 'Not Available')
        {
            $title = 'Not Available';
            $color = '#9C9D9F';
        }elseif($request->datesstatustype == 'Booked Tour'){
            $title = 'Booked Tour';
            $color = '#4CBA08';
        }else{
            $title = 'Available';
            $color = '#FFFFFF';
        }
        $event = new Event;
        $event->title = $title;
        $event->start = $request->start;
        $event->color = $color;
        $event->save();

        return response()->json($event);
    }
    
    public function toggleUserStatus()
    {
        if (request()->has('user_id')) {
            $user = User::find(request('user_id'));
            if ($user) {
                $user->status = request('status');
                $user->save();
                return response()->json(['success' => true, 'message' => 'User status changed successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'something went wrong']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'something went wrong']);
        }
    }

    public function setDate(Request $request)
    {
    }


    public function toggleRequestStatus()
    {
        if (request()->has('request_id')) {
            $user = CallbackRequest::find(request('request_id'));
            if ($user) {
                $user->status = request('status');
                $user->save();
                return response()->json(['success' => true, 'message' => 'Request status changed successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'something went wrong']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'something went wrong']);
        }
    }

    public function filterByDate(Request $request)
{
    if ($request->has('date')) {
        $selectedDate = $request->input('date');

        // Fetch data for the selected date
        $tour_booking_day = DB::table('tour_bookings')
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->whereDate('booking_date', $selectedDate)
            ->where('status', '!=', 3)
            ->where('tour_type', 1)
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->get();

        return response()->json(['success' => $tour_booking_day]);
    }

    return response()->json(['error' => 'Date parameter missing'], 400);
}



    

}