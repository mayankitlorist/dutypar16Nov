<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Models\User_batch;
use App\Models\UserAttendance;
use App\Models\Batch;
use App\Models\Schema;

use Carbon\Carbon;

 

class AttendencereportController extends Controller
{
    public function attendencereport(Request $request){

       $user = Auth::user();

       $teacher = User::where('role_type', 1)->get();
        // print_r($teacher); die;

      return view('admin.attendencereport.attendencereport',compact('teacher'));

    }

    public function getteachers(Request $request){

        $teacherid = $request->teacher;
        // print_r($teacherid); die;
        $getalltrainer = User::where('parent_id',$teacherid)->where('role_type',3)->get();
        // print_r($getalltrainer); die;

       return response()->json($getalltrainer);


    }


    public function gettrainer(Request $request){

        $trainer = $request->trainer;
        // print_r($trainer); die;
        $getallbattch = User_batch::where('user_id',$trainer)->get();
          foreach ($getallbattch as $getallbattchs) {
            
            $batchname = Batch::where('id',$getallbattchs->batch_id)->first();
            $getallbattchs->btachn = $batchname->batch_name;
          }

        // print_r($getallbattch); die;

       return response()->json($getallbattch);
     }



    public function ownerbatchfilter(Request $request){

      extract($_POST);
      // print_r($_POST); die;
      $teacher = User::where('role_type', 1)->get();

      $getall = UserAttendance::getalldataownerfilter($_POST['teacher'],$_POST['trainer'],$_POST['batch'],$_POST['date'],$_POST['date1']);
      
      $teachr = User::where('id',$_POST['teacher'])->first();

      foreach ($getall as $getalls) {
          
          $scheme = Schema::where('id',$getalls->scheme_id)->first();
          $getalls->scheme_name = $scheme->name;
          $getalls->teacher_name = $teachr->name;
          $getalls->teacher_uid = $teachr->uid;

          $hours = $getalls->hours;
          $to = Carbon::parse($getalls->intime);
          $from = Carbon::parse($getalls->outtime);
          $diff = $to->diffInHours($from);
          $toteltimeuser =  '0'.$diff.':00:00';
          $classtotaltime=  $hours.':00:00';
          // print_r($getalls); die;
          $endTime = strtotime("-30 minutes", strtotime($classtotaltime));
          $classminmumhours =  date('h:i:s', $endTime);
          // $totanewtime  =  date('h:i:s', $toteltimeuser);

          // print_r($toteltimeuser);   echo "=="; 
          // print_r( $classminmumhours );  

          if($classminmumhours <= $toteltimeuser){
             $getalls->types="Present ";
          }else{
             $getalls->types="Absent";

          }
          // if($diff < $hours){
// die;
          //   $status = "Attendence Not Marked";
          // }else{
          //   $status = "Attendence Marked";
          // }

          // $getalls->att_status = $status;
      }
      
    
     return view('admin.attendencereport.filterattendencereport',compact('teacher','getall'));


    }
       
}