<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Notifications extends Authenticatable
{
    use Notifiable;

    protected $table = 'notification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sender_id', 'reciver_id','title','description','status','leave_id','type','notification_date'];

    public function reciver()
    {
        return $this->hasOne('\App\User','id','reciver_id');
    }

    public function sender()
    {
        return $this->hasOne('\App\User','id','sender_id');
    }

    public function leave()
    {
        return $this->hasOne('\App\Models\UserLeaveDetails','id','leave_id');
    }

}
