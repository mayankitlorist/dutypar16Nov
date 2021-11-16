<?php


namespace App\Http\Controllers\Api;


use App\Models\LevelCategory;
use App\Models\Offices;
use App\Models\UserAttendance;
use App\Models\UserDetails;
use App\Models\UserLeaveDetails;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;



class NotificationController extends Controller
{
    public static function sendPushNotification($options=[]) {
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
            'authorization: key=' .'AAAAdljZ-rA:APA91bGxde4ALolZVJ6mqiaUsstkK4KLg5ELogV_vrW3cL0ub8el-h4qZtyuxFqg8qUHKvwpPmVOrJmFsmLZOagmz8EWIDRElTnOtEeVBeVPxfWze04HWul4mwfafIHSgDa68Eql5LAv',
            'content-type: application/json'
        ];

        $postdata = '{
            "to" : "' .$options['firebase_token']. '",

            "data" : {
                "title":"' . $options['title'] . '",
                "user":"' . $options['username'] . '",
                "apply_date":"' . $options['attendance_date'] . '",
                "description" : "' . $options['reason'] . '",
              },
              "android": {
            "priority": "high"
          },
          "priority": 10
                      
                }';
        //dd($postdata);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

                $result = curl_exec($ch);
                curl_close($ch);

                return $result;
    }

    public function smssend(Request $request){

        $name = $request->name;
        $number = $request->phone;
        
        $curl = curl_init();

            curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://smsapi.edumarcsms.com/api/v1/sendsms?apikey=ckrugvgwi0002d7qoetgl03k8&senderId=DUTYPR&message=Dear%20'.$name.',%0AYour%20attendance%20for%20today%20has%20been%20marked%20on%202021-10-22.%0AThank%20You,%0ADutyPar%20Team&number='.$number.'&templateId=1407163393391486089',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
            }
}
