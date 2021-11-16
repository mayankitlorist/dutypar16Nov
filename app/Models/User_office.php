<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class User_office extends Model
{
      protected $table = 'user_office';

     /* public static function loginuseroffices($id){

        $data = DB::table('users')
                    ->join('offices','users.office_id','=','offices.id')
                    ->where ('users.id',$id)
                    ->get();
      // $data = DB::table('office_add_user')
    		// 		->join('offices','office_add_user.office_id','=','offices.id')
    		// 		->where ('office_add_user.user_id',$id)
    		// 		->get();
    				// print_r($data); die;
    	return $data;

	}*/


	  public static function loginuseroffices($id){


       $data = DB::table('user_office')
    				->join('users','user_office.user_id','=','users.id')
     				->join('offices','user_office.office_id','=','offices.id')
    				
    				 ->where ('user_office.user_id',$id)
    				->get();
   				//print_r($data); die;
    	return $data;

 }


}