<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallbackRequest;
use App\Models\Event;
use App\Models\TourBooking;
use Illuminate\Http\Request;
use App\Models\VirtualTour;
use App\Models\Tour;
use App\Models\TaxiBookingEvent;

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
        $events = Event::all();
        return response()->json($events);
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
            $color = '#1d875a';
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
    
    public function getEventsSet2()
    {
        //$eventsSet2 = TourBooking::where('tour_type',1)->where('status',1)->get();
        return response()->json($eventsSet2);
    }
    
    public function getTaxiBookingEvent()
    {
        $events = TaxiBookingEvent::all();
        return response()->json($events);
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
    
    //Restore tour(archive to active(Status:-4 to 1))
    public function toggleTourStatus()
    {
        if (request()->has('tour_id')) {
            $tour = Tour::find(request('tour_id'));
            if ($tour) {
                $tour->status = request('status');
                $tour->save();
                return response()->json(['success' => true, 'message' => 'Tour status changed successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'something went wrong']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'something went wrong']);
        }
    }
    
    //Restore Virtual tour(archive to active(Status:-4 to 1))
    public function toggleVirtualTourStatus()
    {
        if (request()->has('tour_id')) {
            $virtual_tour = VirtualTour::find(request('tour_id'));
            if ($virtual_tour) {
                $virtual_tour->status = request('status');
                $virtual_tour->save();
                return response()->json(['success' => true, 'message' => 'Virtual Tour status changed successfully']);
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
}