<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class UserDetails extends Authenticatable
{
    use Notifiable;

    
    protected $table = "user_details";
    //  * @var array
    //  */
    // protected $fillable = ['user_id', 'address1', 'address2', 'city', 'state', 'country','pincode'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }



    public static function loginuserbatch($id){


    $data1 = DB::table('user_batch')
                    ->join('batch','user_batch.batch_id','=','batch.id')
                    ->join('scheme','batch.scheme_id','=','scheme.id')
                    ->where ('user_batch.user_id',$id)
                    ->get();

                    
        return $data1;
}
}
