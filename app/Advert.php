<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{

    public static $autoIndex = true;
    public static $autoDelete = true;

	/**
	 * Fillable fields for an advert.
	 *
	 * @var array
	 */
    protected $fillable = [
    	'job_title',
        'salary',
        'description',          
        'location',
        'street',
        'city',
        'zip',
        'state',
        'country',
        'skill',
        'category',
        'rate',
        'oku_friendly',
        'schedule_type',
        'daily_start_date',
        'daily_end_date',
    ];

    // identify the date to make carbon instance
    protected $dates = ['plan_ends_at'];


   /**
    * Scope Together the query
    *
    *
    */
    public function scopeLocatedAt($query, $id, $job_title)
    {
        //$job_title = str_replace('-', ' ', $job_title);

        return $query->where(compact('id', 'job_title'));
    }

   /**
    * Get advert assosicated with the given application request 
    *
    *
    */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

   /**
    * Get advert assosicated with the given employer
    *
    *
    */
    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

   /**
    * Get the logged in user id and compare with the given advert's employer id
    *
    *
    */
    public function ownedBy(User $user)
    {
        $user = $user;

        return $this->employer->user->id == $user->id;
    }

   /**
    * Get tags assosicated with the given advert
    *
    *
    */
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    public function dailySchedule()
    {
        return $this->belongsToMany(DailySchedule::class, 'advert_daily', 'advert_id', 'daily_schedule_id')->withPivot('start_time','end_time');
    }

    /**
    * Get tags assosicated with the given advert
    *
    *
    */
    public function specificSchedule()
    {
        return $this->hasOne(SpecificSchedule::class);
    }

}
