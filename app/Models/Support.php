<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use DB;

class Support extends Authenticatable
{
    use Notifiable;

    protected $table = 'support';


   public static function getalldatauserfilter($user){

        $data  = DB::table('user_attendance')
                    ->join('users','user_attendance.user_id','=','users.id')
                   	->join('batch','user_attendance.batch_id','=','batch.id')
                    ->where('users.uid',$user)
                    ->orderBy('user_attendance.id', 'desc')
                    ->paginate(1);
                    //print_r($data); die;
            return $data;        

    }

    public static function getalldatabatchfilter($id){

        $data  = DB::table('user_batch')
        			->join('users','user_batch.user_id','=','users.id')
                    ->join('batch','user_batch.batch_id','=','batch.id')
                    ->where('user_batch.batch_id',$id)
                    ->get();
                    //print_r($data); die;
            return $data;        

    }

    public static function getDatauserbatch($id){

     	$data = DB::table('user_batch')
    				->join('users','user_batch.user_id','=','users.id')
    				->join('batch','user_batch.batch_id','=','batch.id')
    				->where('user_batch.user_id',$id)
    				->get();
    				//print_r($data); die;

    	return $data;				
    }   



    public static function getDatauserbatchs($id){

     	$data = DB::table('user_batch')
    				->join('users','user_batch.user_id','=','users.id')
    				->join('batch','user_batch.batch_id','=','batch.id')
    				->where('user_batch.batch_id',$id)
    				->get();
    				//print_r($data); die;

    	return $data;				
    }   
  

   
    
}
