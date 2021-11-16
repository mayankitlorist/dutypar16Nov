<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserLeaveDetails extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_leave_details';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'from_date', 'to_date', 'category', 'reason','leave_type','status'];

    public function user()
    {
        return $this->hasOne('App\User','id');
    }

    public function categoryDetails()
    {
        return $this->hasOne('App\Models\LevelCategory','id','category');
    }
}
