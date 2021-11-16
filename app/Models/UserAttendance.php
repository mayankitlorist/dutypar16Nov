<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

use DB;

class UserAttendance extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_attendance';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'face_status', 'location_status', 'intime','outtime','category','reason', 'status'];

    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }

    public static function offieData($id){

        $data  = DB::table('users')
                    ->join('offices','users.office_id','=','offices.id')
                    ->select('offices.name as officeName',
                               'users.name as username',
                               'users.profile_image as profile' 

                            )
                    ->where('users.id',$id)
                    ->first();

            return $data;        

    }

    public static function  getalldataattendencenew($date,$userid){
       
            $data1  = DB::table('user_attendance')
                    ->join('users','user_attendance.user_id','=','users.id')
                    ->join('batch','user_attendance.batch_id','=','batch.id')
                    // ->orWhereNotNull('user_attendance.batch_id')
                    ->whereDate('user_attendance.intime',$date)
                    ->where('users.parent_id',$userid)
                    ->get();

                  
            return $data1;
        }   

       public static function  getdata($user_id){
       
            $data1 = DB::table('user_batch')
                       ->join('batch','user_batch.batch_id','=','batch.id')
                        ->where('user_id',$user_id)
                        ->get();
                     
                    
            return $data1;
        } 


        public static function  getalldataattendencenewfilterold($batch,$date,$date1){
             $user = Auth::user();
             //print_r($user->id); die;

             $data = array(
              "a" => "55034",
              "b" => "64974"
                );
        
                if($user->id == 55034){

                    $date11 = date('Y-m-d', strtotime($date1. ' + 1 days'));
                        if(!empty($batch)){
                            $data1 = DB::table('user_attendance')
                            ->join('users','user_attendance.user_id','=','users.id')
                            ->join('batch','user_attendance.batch_id','=','batch.id')
                            ->orWhereNotNull('user_attendance.batch_id')
                            ->whereIn('users.parent_id',$data)
                            ->whereBetween('user_attendance.intime', [$date,  $date11])
                            ->where('user_attendance.batch_id',$batch)
                            ->get();
                            return $data1;
                                   
                        }else{

                            if($date == $date1){
                                // echo "hi ";die;
                                 $data1 = DB::table('user_attendance')
                                            ->join('users','user_attendance.user_id','=','users.id')
                                            ->join('batch','user_attendance.batch_id','=','batch.id')
                                            ->orWhereNotNull('user_attendance.batch_id')
                                            ->whereIn('users.parent_id',$data)
                                            ->whereDate('user_attendance.intime',$date)
                                             ->get();
                                             return $data1;
                            }else{
                                // DB::enableQueryLog();
                                 $data1 = DB::table('user_attendance')
                                            ->join('users','user_attendance.user_id','=','users.id')
                                            // ->join('batch','user_attendance.batch_id','=','batch.id')
                                            // ->orWhereNull('user_attendance.batch_id')
                                            // ->orWhereNotNull('user_attendance.intime')
                                            ->whereIn('users.parent_id',$data)
                                            ->whereBetween('user_attendance.intime', [$date,  $date11])
                                            ->get();
                                            // dd(DB::getQueryLog());
                                            // print_r(count($data1));  die;
                            return $data1;
                            }

                            

                        }

                }else{


            $date11 = date('Y-m-d', strtotime($date1. ' + 1 days'));
         //print_r($date11); die;
                if(!empty($batch)){
                    $data1 = DB::table('user_attendance')
                    ->join('users','user_attendance.user_id','=','users.id')
                    ->join('batch','user_attendance.batch_id','=','batch.id')
                    ->orWhereNotNull('user_attendance.batch_id')
                    ->where('users.parent_id',$user->id)
                    ->whereBetween('user_attendance.intime', [$date,  $date11])
                    ->where('user_attendance.batch_id',$batch)
                    ->get();
                    return $data1;
                           
                }else{

                    if($date == $date1){
                        // echo "hi ";die;
                         $data1 = DB::table('user_attendance')
                                    ->join('users','user_attendance.user_id','=','users.id')
                                    ->join('batch','user_attendance.batch_id','=','batch.id')
                                    ->orWhereNotNull('user_attendance.batch_id')
                                    ->where('users.parent_id',$user->id)
                                    ->whereDate('user_attendance.intime',$date)
                                     ->get();
                                     return $data1;
                    }else{
                        // DB::enableQueryLog();
                         $data1 = DB::table('user_attendance')
                                    ->join('users','user_attendance.user_id','=','users.id')
                                    // ->join('batch','user_attendance.batch_id','=','batch.id')
                                    // ->orWhereNull('user_attendance.batch_id')
                                    // ->orWhereNotNull('user_attendance.intime')
                                    ->where('users.parent_id',$user->id)
                                    ->whereBetween('user_attendance.intime', [$date,  $date11])
                                    ->get();
                                    // dd(DB::getQueryLog());
                                    // print_r(count($data1));  die;
                    return $data1;
                    }

                    

                }
         }       
        }   
   
        // } 


         public static function getalldataownerfilter($teacher,$trainer,$batch,$date,$date1){

            $user = Auth::user();
         

            $date11 = date('Y-m-d', strtotime($date1. ' + 1 days'));
            //print_r($date11); die;
                     if($date == $date1){
                        // echo "hi ";die;
                         $data = DB::table('user_attendance')
                                    ->join('users','user_attendance.user_id','=','users.id')
                                    ->join('batch','user_attendance.batch_id','=','batch.id')
                                    // ->orWhereNotNull('user_attendance.batch_id')
                                    ->where('users.id',$trainer)
                                    ->where('batch.id',$batch)
                                    ->whereDate('user_attendance.intime',$date)
                                     ->get();
                                     // print_r($data); die;
                                     return $data;
                    }else{
                       
                            $data1 = DB::table('user_attendance')
                                    ->join('users','user_attendance.user_id','=','users.id')
                                    ->join('batch','user_attendance.batch_id','=','batch.id')
                                    // ->orWhereNotNull('user_attendance.batch_id')
                                    // ->where('users.id',$trainer)
                                    ->where('users.id',$trainer)
                                    ->where('batch.id',$batch)
                                    ->whereBetween('user_attendance.intime', [$date,  $date11])
                                    // ->where('user_attendance.batch_id',$batch)
                                    ->get();
                                    // print_r($data1); die;

                            return $data1;
                    }


                    
            
        }



         public static function getcenterheads($id,$dist){
            // print_r($dist); die;
            $data = DB::table('user_location')
                    ->join('offices','user_location.district','=','offices.districtid')
                    ->join('users','offices.id','=','users.office_id')
                    ->where('users.role_type',1)
                    ->where('user_location.user_id',$id)
                    ->where('user_location.district',$dist)
                    ->select('users.id','users.name')
                    ->get();
                    // print_r($data); die;
                    
                     return $data;
        }

        
         public static function loginuserbatch($id){


            $data = DB::table('user_batch')
                            ->join('batch','user_batch.batch_id','=','batch.id')
                            ->join('scheme','batch.scheme_id','=','scheme.id')
                            ->where ('user_batch.user_id',$id)
                            ->get();
                return $data;
        }

        public static function staticuserbatch($data){


            $data = DB::table('user_batch')
                            ->join('batch','user_batch.batch_id','=','batch.id')
                            ->join('scheme','batch.scheme_id','=','scheme.id')
                            ->whereIn ('user_batch.user_id', $data)
                            ->get();

                            //print_r($data); die;
                return $data;
        }



         public static function staticuserbatchold($data){


            $data = DB::table('user_batch')
                            ->join('batch','user_batch.batch_id','=','batch.id')
                            ->join('scheme','batch.scheme_id','=','scheme.id')
                            ->whereIn ('user_batch.user_id', $data)
                            ->get();

                            //print_r($data); die;
                return $data;
        }


         public static function loginuserbatchold($id){


            $data = DB::table('user_batch')
                            ->join('batch','user_batch.batch_id','=','batch.id')
                            ->join('scheme','batch.scheme_id','=','scheme.id')
                            ->where ('user_batch.user_id',$id)
                            ->get();
                return $data;
        }




        public static function  getalldataattendencenewfilter($batch,$date,$date1){
             $user = Auth::user();
             //print_r($user->id); die;

             $data = array(
              "a" => "55034",
              "b" => "64974"
                );
        
                if($user->id == 55034){

                    $date11 = date('Y-m-d', strtotime($date1. ' + 1 days'));
                        if(!empty($batch)){
                            $data1 = DB::table('user_attendance')
                            ->join('users','user_attendance.user_id','=','users.id')
                            ->join('batch','user_attendance.batch_id','=','batch.id')
                            ->orWhereNotNull('user_attendance.batch_id')
                            ->whereIn('users.parent_id',$data)
                            ->whereBetween('user_attendance.intime', [$date,  $date11])
                            ->where('user_attendance.batch_id',$batch)
                            ->get();
                            return $data1;
                                   
                        }else{

                            if($date == $date1){
                                // echo "hi ";die;
                                 $data1 = DB::table('user_attendance')
                                            ->join('users','user_attendance.user_id','=','users.id')
                                            ->join('batch','user_attendance.batch_id','=','batch.id')
                                            ->orWhereNotNull('user_attendance.batch_id')
                                            ->whereIn('users.parent_id',$data)
                                            ->whereDate('user_attendance.intime',$date)
                                             ->get();
                                             return $data1;
                            }else{
                                // DB::enableQueryLog();
                                 $data1 = DB::table('user_attendance')
                                            ->join('users','user_attendance.user_id','=','users.id')
                                            // ->join('batch','user_attendance.batch_id','=','batch.id')
                                            // ->orWhereNull('user_attendance.batch_id')
                                            // ->orWhereNotNull('user_attendance.intime')
                                            ->whereIn('users.parent_id',$data)
                                            ->whereBetween('user_attendance.intime', [$date,  $date11])
                                            ->get();
                                            // dd(DB::getQueryLog());
                                            // print_r(count($data1));  die;
                            return $data1;
                            }

                            

                        }

                }else{


            $date11 = date('Y-m-d', strtotime($date1. ' + 1 days'));
         //print_r($date11); die;
                if(!empty($batch)){
                    $data1 = DB::table('user_attendance')
                    ->join('users','user_attendance.user_id','=','users.id')
                    ->join('batch','user_attendance.batch_id','=','batch.id')
                    ->orWhereNotNull('user_attendance.batch_id')
                    ->where('users.parent_id',$user->id)
                    ->whereBetween('user_attendance.intime', [$date,  $date11])
                    ->where('user_attendance.batch_id',$batch)
                    ->get();
                    return $data1;
                           
                }else{

                    if($date == $date1){
                        // echo "hi ";die;
                         $data1 = DB::table('user_attendance')
                                    ->join('users','user_attendance.user_id','=','users.id')
                                    ->join('batch','user_attendance.batch_id','=','batch.id')
                                    ->orWhereNotNull('user_attendance.batch_id')
                                    ->where('users.parent_id',$user->id)
                                    ->whereDate('user_attendance.intime',$date)
                                     ->get();
                                     return $data1;
                    }else{
                        // DB::enableQueryLog();
                         $data1 = DB::table('user_attendance')
                                    ->join('users','user_attendance.user_id','=','users.id')
                                    // ->join('batch','user_attendance.batch_id','=','batch.id')
                                    // ->orWhereNull('user_attendance.batch_id')
                                    // ->orWhereNotNull('user_attendance.intime')
                                    ->where('users.parent_id',$user->id)
                                    ->whereBetween('user_attendance.intime', [$date,  $date11])
                                    ->get();
                                    // dd(DB::getQueryLog());
                                    // print_r(count($data1));  die;
                    return $data1;
                    }

                    

                }
         }       
        }   
   




}
