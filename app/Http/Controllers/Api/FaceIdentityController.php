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





class FaceIdentityController extends Controller
{
    public static function checkFace($faceId1,$faceId2)
    {

        $url = "https://facialattendance.cognitiveservices.azure.com/face/v1.0/verify";
        $header = [
            'Content-Type: application/json',
            'Ocp-Apim-Subscription-Key:751ae9ad11f74ab6bc78eff13022ed71'
        ];
        $faceData = '{
    "faceId1": "'.$faceId1.'",
    "faceId2": "'.$faceId2.'",
}';
//        dd($faceData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $faceData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
//        dd($result);
        curl_close($ch);
        return $result;
    }

    public static function addImage($options)
    {
        $url = "https://facialattendance.cognitiveservices.azure.com/face/v1.0/detect?returnFaceId=true&returnFaceLandmarks=false&recognitionModel=recognition_01&returnRecognitionModel=false&detectionModel=detection_02";
        $header = [
            'Ocp-Apim-Subscription-Key:751ae9ad11f74ab6bc78eff13022ed71',
            'content-type: application/json'
        ];
        $postdata = '{
            "url" : "'.$options.'",
        }';
        // print_r($postdata ); die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
        // print_r($result); die;
        curl_close($ch);
        return $result;
    }

}
