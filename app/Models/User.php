<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'uid', 'status','email', 'password','role_type','office_id','profile_image','parent_id','firebase_token','device_type','face_id','temp_face_id','temp_image','organization_id','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function office()
    {
        return $this->hasOne('\App\Models\Offices','id','office_id');
    }
    public static function parent($id)
    {
        $parent = self::where('id',$id)->first();
        return $parent;
    }
}
