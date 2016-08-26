<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use App\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles, Billable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar','contact', 'type', 'trial_ends_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // identify the date to make carbon instance
    protected $dates = ['trial_ends_at'];


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

    public function avatarBy(User $user)
    {
        $userID = $user->id;

        if(!$userID)
        {
            return redirect('/');
        }

        return $this->id == $userID;
    }
}
