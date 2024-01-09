<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CallbackRequest;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
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
}
