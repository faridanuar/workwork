<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;


class User extends Authenticatable
{
    use HasRoles, Billable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar','contact', 'type'
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
     * defining "users" table relationship with "job_seekers" table
     */
    public function jobSeeker()
    {
        return $this->hasOne(Job_Seeker::class);
    }

    public function employer()
    {
        return $this->hasOne(Employer::class);
    }

}
