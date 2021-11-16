<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Support;
use App\Models\Batch;
use App\Models\UserAttendance;

use App\User;
use DB;
class SupportController extends Controller
{
     public function supportlist()
    {
       
        return view('admin.support.index');
    }


    public function userfilter(Request $request){

       extract($_POST);
      //print_r( $_POST['user']); die;
       $user = Auth::user();
        
       if($_POST['user']){

		$userid = User::where('uid',$_POST['user'])->first();
		//print_r($userid); die;
		$users = Support::getalldatauserfilter($_POST['user']);
       	
       $batch	=Support::getDatauserbatch($userid->id);
		//print_r($batch); die;
       }else{

          $batchid = Batch::where('batch_name',$_POST['batch'])->first();
		   //print_r($batchid); die;
		  $users = Support::getalldatabatchfilter($batchid->id);
        	foreach ($users as $userss) {
        	 			//print_r($userss); die;
        	 			$a = UserAttendance::where('user_id',$userss->user_id)->orderBy('id', 'desc')->select('intime')->first();
        	 			$b = $a['intime'];
        	 			//print_r($b); die;
        	 			$userss->intime = $b;
        	 		}

			//print_r($users[0]->intime->toarray()); die; 		
        
       		$batch	=Support::getDatauserbatchs($batchid->id);
       	 
       }
       
     //print_r( $batch); die;

      return view('admin.support.filteruser',compact('users','batch'));

    }



    public function adduser(Request $request){

       extract($_POST);
     // print_r($_POST); die;
      
      	$date1=date_create($_POST['date']);
		$date2=date_create($_POST['date1']);
		$diff=date_diff($date1,$date2);
		
		$date = $diff->days ;
			
			$ab = $_POST['date'];

		for ($i=0; $i<= $date ; $i++) { 
			$newdates = $ab;
			$timess = $_POST['time'];
			$date11 = str_replace('-', '/', $ab);
			$sss = date('Y-m-d H:i:s', strtotime("$ab $timess")); 
			$ottime = $_POST['time1'];
	
			if (!empty($ottime)){

				$ott = date('Y-m-d H:i:s', strtotime("$ab $ottime"));
			}else{

				$ott = null;
			}

			$obj = [
					'batch_id'=>$_POST['batch_id'],
					'user_id'=>$_POST['uid'],
					'intime'=>$sss,
					'outtime'=>$ott,
					'face_status'=>1,
					'location_status'=>1,
					'status'=>1
			];
			//print_r($obj); die;
			UserAttendance::insert($obj);
			
			$ab = date('Y-m-d',strtotime($date11 . "+1 days"));


		}

		

		

      return redirect('admin/support');

    }

    public function adduserbatch($user_id)

    {

    	$users = User::where('id',$user_id)->first();

    	//print_r($users); die;
    	 $data = DB::table('user_batch')
               ->join('batch','user_batch.batch_id','=','batch.id')
               ->where('user_batch.user_id',$user_id )
               ->select('user_batch.id as batchId','batch.batch_name','user_batch.is_online')
               ->get();
               //print_r($data); die;
       return view('admin.support.adduserbatch',compact('data','users'));
    
    }
}
