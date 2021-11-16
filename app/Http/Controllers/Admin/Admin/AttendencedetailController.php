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

use App\Exports\AttendencedetailExport;

use Maatwebsite\Excel\Facades\Excel;
use PDF;


use Storage;
use File;
use ZipArchive;

  

class AttendencedetailController extends Controller
{
    public function attendencedetaillist(Request $request){

       $user = Auth::user();
          // print_r($user->id);
          $date = date('Y-m-d');
          $users = UserAttendance::getalldataattendencenew($date,$user->id);
      
        $teach = User::where('role_type',2)->Where('parent_id',$user->id)->get();
        
             $data = array(
              "a" => "55034",
              "b" => "64974"
            );

        
        if($user->id == 55034){
        
               $batch = UserAttendance::staticuserbatch($data);

          }else{

             $batch = UserAttendance::loginuserbatch($user->id);

          }

                session_start();
            $showDate1 = date('Y-m-d');
            session(['date1' => $showDate1]);
            $showDate2 = null;
            session(['date2' => $showDate2]);

             //print_r($users); die;
            return view('admin.attendenceDetail.index',compact('users','teach','batch'));

    }

     public function attendenceDetailstatus(Request $request){

           $user = Auth::user();
              // print_r($user->id);
          $date = date('Y-m-d');
          $users = UserAttendance::getalldataattendencenew($date,$user->id);
          // print_r($users); die;

          foreach ( $users as  $user) {
           // print_r($user->intime); die;
            if( $user->hours){
              $date1 = strtotime($user->intime);
            $date2 = strtotime($user->outtime);

             $diff = abs($date2 - $date1);            
                $years = floor($diff / (365*60*60*24));  
                $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));  
                $days = floor(($diff - $years * 365*60*60*24 -$months*30*60*60*24)/ (60*60*24)); 
                $hours = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                $minutes = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);  
                $seconds = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
                 $diff = $hours.":".$minutes;
                 $totaltime = $user->hours.":00";

                 $time = strtotime($totaltime);
                 $time = $time - (30 * 60);
                 $time = date("H:i", $time);
                 // print_r( $time); die;
                 // print_r($totaltime-0:30); die;
                if( $time <=  $diff){
                $user->userstatus = "Present";
                  
                }else{
                $user->userstatus = "Absent";
                }

               }else{
                $user->userstatus = "Absent";
               }
            


             
          }

           $teach = User::where('role_type',2)->Where('parent_id',$user->id)->get();
        
             $data = array(
              "a" => "55034",
              "b" => "64974"
            );

        
            if($user->id == 55034){
                
                 $batch = UserAttendance::staticuserbatch($data);

            }else{

               $batch = UserAttendance::loginuserbatch($user->id);

            }

              session_start();
          $showDate1 = date('Y-m-d');
          session(['date1' => $showDate1]);
          $showDate2 = null;
          session(['date2' => $showDate2]);

           // print_r($users); die;

          return view('admin.attendenceDetail.index1',compact('users','teach','batch'));
    }


    public function batchfilter1(Request $request){
       $user = Auth::user();
      extract($_POST);
      print_r( $_POST); die;
      $data = array(
          "a" => "55034",
          "b" => "64974"
        );

    
    if($user->id == 55034){
        
         $batch = UserAttendance::staticuserbatch($data);

    }else{

       $batch = UserAttendance::loginuserbatch($user->id);

    }

    
      $users = UserAttendance::getalldataattendencenewfilter($_POST['batch'],$_POST['date'],$_POST['date1']);


      if(empty($_POST['batch'])){
        foreach ($users as $user) {
            $batchss = Batch::where('id',$user->batch_id)->select('batch_name')->first();
            if($batchss){
              $user->batch_name = $batchss->batch_name;
            }else{
              $user->batch_name = '-';

            }



             if( $user->hours){
              $date1 = strtotime($user->intime);
            $date2 = strtotime($user->outtime);

             $diff = abs($date2 - $date1);            
                $years = floor($diff / (365*60*60*24));  
                $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));  
                $days = floor(($diff - $years * 365*60*60*24 -$months*30*60*60*24)/ (60*60*24)); 
                $hours = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
                $minutes = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);  
                $seconds = floor(($diff - $years * 365*60*60*24- $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
                 $diff = $hours.":".$minutes;
                 $totaltime = $user->hours.":00";

                 $time = strtotime($totaltime);
                 $time = $time - (30 * 60);
                 $time = date("H:i", $time);
                 // print_r( $time); die;
                 // print_r($totaltime-0:30); die;
                if( $time <=  $diff){
                $user->userstatus = "Present";
                  
                }else{
                $user->userstatus = "Absent";
                }

               }else{
                $user->userstatus = "Absent";
               }




        }

      }
        


       // print_r( $users); die;
        session_start();
      session(['date1' => $_POST['date']]);
      session(['date2' => $_POST['date1']]);
      session(['batch' => $_POST['batch']]);

      return view('admin.attendenceDetail.filterbatch1',compact('users','batch'));

    }

    // public function attendencedetaillist1(Request $request){

    //    $user = Auth::user();
    //     //print_r($user->id); die;
    //   $date = date('Y-m-d');
    //   $users = UserAttendance::getalldataattendencenew($date,$user->id);
  
    // $teach = User::where('role_type',2)->Where('parent_id',$user->id)->get();
    // //print_r($teach); die;
    //  $data = array(
    //       "a" => "55034",
    //       "b" => "64974"
    //     );

    
    // if($user->id == 55034){
        
    //      $batch = UserAttendance::staticuserbatch($data);

    // }else{

    //    $batch = UserAttendance::loginuserbatch($user->id);

    // }
     
     
    //       session_start();
    //   $showDate1 = date('Y-m-d');
    //   session(['date1' => $showDate1]);
    //   $showDate2 = null;
    //   session(['date2' => $showDate2]);

    //    //print_r($users); die;
    //   return view('admin.attendenceDetail.attendence',compact('users','teach','batch'));

    // }







    public function store(Request $request){

       $id = $request->teacher;
       //print_r($id); die;

       $batch = User_batch::getalluserbatchdata($id);

       //print_r( $batch); die;

       return response()->json($batch);


    }


    public function batchfilter(Request $request){
       $user = Auth::user();
      extract($_POST);
      //print_r( $_POST); die;
      $data = array(
          "a" => "55034",
          "b" => "64974"
        );

    
    if($user->id == 55034){
        
         $batch = UserAttendance::staticuserbatch($data);

    }else{

       $batch = UserAttendance::loginuserbatch($user->id);

    }

    
      $users = UserAttendance::getalldataattendencenewfilter($_POST['batch'],$_POST['date'],$_POST['date1']);

      if(empty($_POST['batch'])){
        foreach ($users as $user) {
            $batchss = Batch::where('id',$user->batch_id)->select('batch_name')->first();
            if($batchss){
              $user->batch_name = $batchss->batch_name;
            }else{
              $user->batch_name = '-';

            }
            // print_r( $batch); die;
          // if($user->batch_id == 0 || $user->batch_id==null){
          //    $a = '-';
          // }else{
          //   $batch = Batch::where('id',$user->batch_id)->select('batch_name')->first();
          //   $a = $batch['batch_name'];
          // }
          //  $user->batch_name = $a; 

        }

      }
        


       // print_r( $users); die;
        session_start();
      session(['date1' => $_POST['date']]);
      session(['date2' => $_POST['date1']]);
      session(['batch' => $_POST['batch']]);

      return view('admin.attendenceDetail.filterbatch',compact('users','batch'));

    }



    //  public function batchfilter1(Request $request){
    //    $user = Auth::user();
    //   extract($_POST);
    //   //print_r( $_POST); die;
    //   $data = array(
    //       "a" => "55034",
    //       "b" => "64974"
    //     );

    
    // if($user->id == 55034){
        
    //      $batch = UserAttendance::staticuserbatch($data);

    // }else{

    //    $batch = UserAttendance::loginuserbatch($user->id);

    // }

    
    //   $users = UserAttendance::getalldataattendencenewfilter1($_POST['batch'],$_POST['date'],$_POST['date1']);

    //   if(empty($_POST['batch'])){
    //     foreach ($users as $user) {
    //         $batchss = Batch::where('id',$user->batch_id)->select('batch_name')->first();
    //         if($batchss){
    //           $user->batch_name = $batchss->batch_name;
    //         }else{
    //           $user->batch_name = '-';

    //         }
    //         // print_r( $batch); die;
    //       // if($user->batch_id == 0 || $user->batch_id==null){
    //       //    $a = '-';
    //       // }else{
    //       //   $batch = Batch::where('id',$user->batch_id)->select('batch_name')->first();
    //       //   $a = $batch['batch_name'];
    //       // }
    //       //  $user->batch_name = $a; 

    //     }

    //   }
        


    //    // print_r( $users); die;
    //     session_start();
    //   session(['date1' => $_POST['date']]);
    //   session(['date2' => $_POST['date1']]);
    //   session(['batch' => $_POST['batch']]);

    //   return view('admin.attendenceDetail.filterbatch1',compact('users','batch'));

    // }



    public function attendenceExport(){
      //  echo "fdf"; die;
        return Excel::download(new AttendencedetailExport, 'userList.xls');
       }

    public function pdfExport(){
      //  echo "fdf"; die;
        $user = Auth::user();
        
           $currentdate = date('Y-m-d');
           $date = session('date1');
           
          if($currentdate == $date){

         $users = UserAttendance::getalldataattendencenew($date,$user->id);
         $pdf = PDF::loadView('admin.attendenceDetail.pdf', compact('users'))->setPaper('a4', 'landscape');

       	 Storage::put('public/myFiles/users.pdf', $pdf->output());

	    	  $zip = new ZipArchive;
		   
		        $fileName = 'Users.zip';
		   
		        if ($zip->open(storage_path('app/public' . '/' . $fileName), ZipArchive::CREATE) === TRUE)
		        {
		            $files = File::files(storage_path('app/public' . '/' .'myFiles'));
		   
		            foreach ($files as $key => $value) {
		                $relativeNameInZipFile = basename($value);
		                $zip->addFile($value, $relativeNameInZipFile);
		            }
		             
		            $zip->close();
		        }
		    
		        return response()->download(storage_path('app/public' . '/' . $fileName));

       
      }else{ 
         $date2 = session('date2');
         $batch = session('batch');
       if(!empty($batch)){

       	 	$users = UserAttendance::getalldataattendencenewfilter($batch,$date,$date2);
       }else{

       	$batchid = null;
       	$users = UserAttendance::getalldataattendencenewfilter($batchid,$date,$date2);
       }
            
        
          if(empty($batchid)){
        foreach ($users as $user) {
            $batchss = Batch::where('id',$user->batch_id)->select('batch_name')->first();
            if($batchss){
              $user->batch_name = $batchss->batch_name;
            }else{
              $user->batch_name = '-';

            }
        }

      }
         $pdf = PDF::loadView('admin.attendenceDetail.pdf', compact('users'))->setPaper('a4', 'landscape'); 

          Storage::put('public/myFiles/users.pdf', $pdf->output());

      	  $zip = new ZipArchive;
		   
		        $fileName = 'Users.zip';
		   
		        if ($zip->open(storage_path('app/public' . '/' . $fileName), ZipArchive::CREATE) === TRUE)
		        {
		            $files = File::files(storage_path('app/public' . '/' .'myFiles'));
		   
		            foreach ($files as $key => $value) {
		                $relativeNameInZipFile = basename($value);
		                $zip->addFile($value, $relativeNameInZipFile);
		            }
		             
		            $zip->close();
		        }
		    
		        return response()->download(storage_path('app/public' . '/' . $fileName));


      }
     }
      
       
}