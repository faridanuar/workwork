<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_Seeker extends Model
{
    /**
     * Fillable fields for an job_seeker.
     *
     * @var array
     */
    protected $fillable = [
    
        'age',
        'contact',
        'location',
        'street',
        'city',
        'zip',
        'state',
        'country',

    ];

    /**
     * defining "job_seekers" table relationship with "users" table
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_seeker_id');
    }

    public function rating()
    {
        return $this->hasMany(Employer_Rating::class, 'job_seeker_id');
    }

    public function ownRating()
    {
        return $this->hasMany(Job_Seeker_Rating::class, 'job_seeker_id');
    }

    /**
    * Get categories assosicated with the given job seeker
    *
    *
    */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_jobSeeker', 'job_seeker_id')->withTimestamps();
    }

    /*
    * referencing which table to use for this MODEL
    */
    protected $table = 'job_seekers';
}
