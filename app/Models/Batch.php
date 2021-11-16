<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Batch extends Model
{
    protected $table = 'batch';

    public static function getalldata(){

    	$data = DB::table('batch')
    				->join('scheme','batch.scheme_id','=','scheme.id')
    				->select('batch_name','start_time','end_time','batch.is_active as active','name')
    				->get();
    	return $data;				
    }
    
     public static function getDatauserbatch($id){

    	$data = DB::table('user_batch')
    				->join('batch','user_batch.batch_id','=','batch.id')
    				->where('user_batch.user_id',$id)
    				->get();
    	return $data;				
    }
}
