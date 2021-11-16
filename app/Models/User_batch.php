<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class User_batch extends Model
{
     protected $table = 'user_batch';

      public static function getallDatauserbatch($id){

    	$data = DB::table('user_batch')
    				->join('user_office','user_batch.id','=','user_office.user_batch_id')
    				->join('users','user_batch.user_id','=','users.id')
    				->join('batch','user_batch.batch_id','=','batch.id')
    				->select('batch_id','is_online','user_batch_id','user_office.office_id','name','batch_name','user_batch.user_id','user_batch.id as userbatchid','user_office.id as userofficeid')
                     ->whereIn('user_batch.user_id',$id)
    				->get();
    				 // print_r($data); die;
    	return $data;
	 }

	 // public static function getallDatauserbatch(){

	 // 	$data = 
	 // }







}
