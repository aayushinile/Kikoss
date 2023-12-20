<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\PhotoBoothMedia;

if (!function_exists('PhotoCount')) {
    function PhotoCount($id)
    {
        $image_count = PhotoBoothMedia::where('booth_id',$id)->where('media_type','Image')->count();
        return $image_count;
    }
}

if (!function_exists('VideoCount')) {
    function VideoCount($id)
    {
        $video_count = PhotoBoothMedia::where('booth_id',$id)->where('media_type','Video')->count();
        return $video_count;
    }
}

if (!function_exists('UserNameBooth')) {
    function UserNameBooth($id)
    {

        $user = User::where('id',$id)->first();
        $name = $user->fullname;
        return $name;
    }
}

if (!function_exists('successMsg')) {
    function successMsg($msg, $data = [])
    {
        return response()->json(['status' => true, 'message' => $msg, 'data' => $data]);
    }
}

if (!function_exists('errorMsg')) {
    function errorMsg($msg, $data = [])
    {
        return response()->json(['status' => false, 'message' => $msg, 'data' => $data]);
    }
}

if (!function_exists('encrypt_decrypt')) {
    function encrypt_decrypt($action, $string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';
        // hash
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
}

/* Upload Image */
if (!function_exists('imageUpload')) {
    function imageUpload($request, $path, $name)
    {
        if ($request->file($name)) {
            $imageName = 'IMG_' . date('Ymd') . '_' . date('His') . '_' . rand(1000, 9999) . '.' . $request->image->extension();
            $request->image->move(public_path($path), $imageName);
            return $imageName;
        }
    }
}

/* Handle and path accoding to local and live */
if (!function_exists('assets')) {
    function assets($path)
    {
        //return asset('public/'.$path); /* For live server */
        return asset($path);/* For local server(When project run on local comment first path) */
    }
}

/*Calculate Distance in KM accoding to pickup_lat_long and drop_lat_long */
if (!function_exists('getDistanceFromLatLonInKm')) {
    function getDistanceFromLatLonInKm($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371;
        $dLat = ($lat2 - $lat1) * (3.14159 / 180);
        $dLon = ($lon2 - $lon1) * (3.14159 / 180);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(($lat1) * (3.14159 / 180)) * cos(($lat2) * (3.14159 / 180)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        return $d;
    }
}