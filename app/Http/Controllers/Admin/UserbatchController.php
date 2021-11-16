<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offices;
use App\Models\Batch;
use App\User;
use App\Models\User_batch;
use App\Models\User_office;
use App\Models\UserDetails;

use Auth;


class UserbatchController extends Controller
{
    public function list(Request $request){
        
        $data = $request->session()->all();
        $data = Auth::user()->toarray();

        // print_r($data['role_type']); die;
        if($data['role_type']== 3){

            $data['id'] = $data['parent_id'];

        }
    	$office = User_office::loginuseroffices($data['id']);
        $batch = UserDetails::loginuserbatch($data['id']);	
    	$user = User::where('parent_id',$data['id'])->get();
            $usersId = [];
            if($user){
                foreach ($user as $key) {
                array_push($usersId, $key->id);   
             } 
            }
            
    	$user_batchs = user_batch::getallDatauserbatch($usersId); 
    	foreach ($user_batchs as $newdata) {
    		$officeName = Offices::where('id',$newdata->office_id)->first();
    		$newdata->officename = $officeName->name;   			
    	}
    	return view('admin.userbatch.index',compact('office','batch','user_batchs','user'));

    }
    public function adduser_batch(){
    	extract($_POST);
    	// print_r($_POST); die;
        foreach ($_POST['user_id'] as $value) {
            // print_r($value); die;
             $obj= [
                    'batch_id'=>$_POST['batch_id'],
                    'user_id'=>$value,
                    
            ];

        $getid=User_batch::insertGetId($obj);

        $obj1= [        
                    'user_batch_id'=>$getid,
                    'user_id'=>$value,
                    'office_id'=>$_POST['office_id'],
                    
            ];

        
        User_office::insert($obj1);

         } 
    	// $obj= [
    	// 			'batch_id'=>$_POST['batch_id'],
    	// 			'user_id'=>$_POST['user_id'],
    				
    	// 	];

    	// $getid=User_batch::insertGetId($obj);
    	// 		//print_r($getid); die;

    	// $obj1= [		
    	// 			'user_batch_id'=>$getid,
   		// 			'user_id'=>$_POST['user_id'],
    	// 			'office_id'=>$_POST['office_id'],
    				
    	// 	];

    	
    	// User_office::insert($obj1);

    	return redirect('admin/user_batch');
    }



    public function update_batch(){
    	extract($_POST);
    	// print_r($_POST); die;

    	// $getid=User_batch::where('id',$_POST['batchId'])->update(['user_id'=>$_POST['user_id'],'batch_id'=>$_POST['batch_id']]);
    	// $getid=User_office::where('id',$_POST['officeId'])->update(['user_id'=>$_POST['user_id'],'office_id'=>$_POST['office_id']]);

        User_batch::where('id',$_POST['batchId'])->update(['batch_id'=>$_POST['batch_id']]);
        $newData = User_office::where('id',$_POST['officeId'])->update(['office_id'=>$_POST['office_id']]);

    	return redirect('admin/user_batch');


    }

    public function checkboxstatus(Request $request){

        $id = $request->id;
        $status = $request->status;
        User_batch::where('id',$id)->update(['is_online'=>$status]);

    } 
}
