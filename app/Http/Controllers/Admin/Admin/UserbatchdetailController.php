<?php

// namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offices;
use App\Models\Schema;	
use App\Models\Batch;
use App\Models\User_batch;
use App\Models\UserDetails;
use App\User;
use App\Models\UserAttendance;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Storage;


class UserbatchdetailController extends Controller
{


    public function viewlist(){

        $user = Auth::user();
         $batch = UserDetails::loginuserbatch($user->id);
    	$office = Offices::all();
    	$schema = Schema::all();
    	



        
    	return view('admin.userbatchdetail.index',compact('office','schema','batch'));

    }

    public function addbatch(){
        $user = Auth::user();

    	extract($_POST);
    	//print_r($_POST); die; 
    	$obj= [
    				'batch_name'=>$_POST['batch_name'],
    				'scheme_id'=>$_POST['schema_id'],
    				'start_time'=>$_POST['Start_time'],
    				'end_time'=>$_POST['End_time'],

    		];

    	$batchId = Batch::insertGetId($obj);

        User_batch::insert(['user_id'=>$user->id,'batch_id'=>$batchId]);
        // print_r($batchId); die;

    	return redirect('admin/batch_detail');
    }

     public function attendancemark(Request $request){
        $data = $request->session()->all();
        $data = Auth::user()->toArray();

             // print_r($data['id']); die;
        // print_r($data['id']); die;
        $batchs = Batch::getDatauserbatch($data['id']);
        // print_r($batchs); die;
        return view('admin.userbatchdetail.attendance',compact('batchs'));


    }

   
public function markattendance(Request $request){

	extract($_POST); 
	//print_r($_POST); die;
    $validator = Validator::make($request->all(),[
    'image' => 'required',
    ]);

        if ($validator->fails()) {
        $messages = $validator->messages();
        return Redirect::back()->withInput($request->except('password'))
        ->withErrors(['Please Select Batch name and click photo!']);
        }
        extract($_POST);

        $data = $request->session()->all();
        $data = Auth::user()->toArray();
        $id = $data['id'];
        $userId = User::where('id',$id)->with('office')->first();
        $isactive = User_batch::where('user_id',$id)->where('batch_id',$_POST['batch_id'])->first();
        if(!$userId) {
         return redirect('/attendance_mark');
        }
        if($userId->profile_image){
            $faceData = $this->addImage($userId->profile_image);
            $jsonDecode = json_decode($faceData);
            // print_r($jsonDecode); die;
            if (isset($jsonDecode->error))
                            {
                                 return Redirect::back()->withInput($request->except('password'))
                ->withErrors(['Something went wrong! Please Change Profile photo.']);
                            }
            if(empty($jsonDecode)  ){

                return Redirect::back()->withInput($request->except('password'))
                ->withErrors(['Something went wrong! Please Change Profile photo.']);
            }
                // print_r($jsonDecode); die;
                if($jsonDecode[0]->faceId){
                // print_r($jsonDecode[0]->faceId); die;
                User::where('id', $id)->update(['face_id'=>$jsonDecode[0]->faceId]);
                }
        }else{
            return Redirect::back()->withInput($request->except('password'))
            ->withErrors(['Please Select profile photo.']);
        }

            if($isactive->is_online == 1){

                    $image =$_POST['image']; // your base64 encoded
                    preg_match("/data:(.*?)\/(.*?);/", $image, $image_extension); // extract the image extension
                    $new_image = preg_replace('/data:(.*?)\/(.*?);base64,/','',$image); // remove the type part
                    $new_image = str_replace(' ', '+', $new_image);
                    $file_name = '/public/new/img_'.time().'.'.$image_extension[2];
                    $path =Storage::disk('local')->put($file_name, base64_decode($new_image));
                    $profileName = url('../storage/app/'.$file_name);
                    $faceData2 = $this->addImage($profileName);
                    // print_r($faceData2); die;
                    $jsonDecode2 = json_decode($faceData2);
                    // print_r($jsonDecode2); die;
                    if(empty($jsonDecode2)){
                    return Redirect::back()->withInput($request->except('password'))
                    ->withErrors(['Something went wrong! Please Click photo Properly.']);
                    }
                    User::where('id', $id)->update(['temp_face_id'=>$jsonDecode2[0]->faceId,'temp_image'=>$profileName]);
                    $userdata = User::where('id',$data['id'])->first();
                    // print_r($userdata); die;
                    // $faceDataprImg = $this->addImage($userdata->profile_image);
                    // $jsonDecodeProImage = json_decode($faceData2);
                    

                    // $faceVerify = $this->checkFace($jsonDecodeProImage[0]->faceId, $jsonDecode2[0]->faceId);
                    $faceVerify = $this->checkFace($userdata->face_id, $userdata->temp_face_id);
                    
                    // print_r( $faceVerify); die;
                    $faceIdentity = json_decode($faceVerify);
                    // print_r( $faceIdentity); die;
                    if($faceIdentity->isIdentical == 1 || $faceIdentity->confidence >= 0.30){
                    	// echo "string"; die;
                        if($_POST['jointime']=='intime'){
                        	
                            $intime = date('Y-m-d H:i:s');
                            $data = array('location_status' => 1, 'face_status' => 1, 'intime' => $intime, 'status' => 1, 'user_id' => $id,
                            'batch_id'=> $_POST['batch_id']);
                            $result = UserAttendance::insert($data);
                            session()
                            ->flash('msg','Invalid User');
                            return back();
                        }else{
                            $outtime = date('Y-m-d H:i:s');
                            $indate = date('Y-m-d');
                            $data = array('location_status' => 1, 'face_status' => 1, 'outtime' => $outtime, 'status' => 1, 'user_id' => $id,
                            'batch_id'=> $_POST['batch_id']);
                            // $result = UserAttendance::insert($data);
                            $result = UserAttendance::where('user_id',$id)->where('batch_id',$_POST['batch_id'])->whereDate('intime',$indate)->update(['outtime'=>$outtime]);

                            session()
                            ->flash('msg','Invalid User');
                             return back();
                        }

                    }else{
                        return Redirect::back()->withInput($request->except('password'))
                        ->withErrors(['Face Not Match.']);
                    }
            }
            else{
                return Redirect::back()->withInput($request->except('password'))
                ->withErrors(['User Not online.']);
            }
    }


    // Add face 


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

         public function checkattendancedetails(Request $request){
             $user = Auth::user();

            $batchId= $request->batchid;
            $date = date('Y-m-d');
            // print_r($batchId);
            // echo "======";
            // print_r($user->id); die;
          $data = UserAttendance::where('user_id',$user->id)->where('batch_id',$batchId)->whereDate('intime',$date)->first();
          $data1 = UserAttendance::where('user_id',$user->id)->where('batch_id',$batchId)->whereDate('outtime',$date)->first();

         // print_r($data); die;
          if(!empty($data1)){
            return response()->json(['status'=>'success1']);

          }elseif(!empty($data)){
            return response()->json(['status'=>'success']);

          }else{
                 return response()->json(['status'=>'fails']);

          }


         }       



}
