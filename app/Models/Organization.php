<?php

namespace App\Models;

use App\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Organization extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(
            User::class,
            'organization_id',
            'id'
        );
    }

    public function offices()
    {
        return $this->hasMany(
            \App\Models\Offices::class,
            'organization_id',
            'id'
        );
    }

}
