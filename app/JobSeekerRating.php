<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSeekerRating extends Model
{
    /**
     * Fillable fields for an application.
     *
     * @var array
     */
    protected $fillable = [

    	'rating',
        'comment',
        'postedBy',

    ];



    public function employer()
    {
    	return $this->belongsTo(Employer::class);
    }

    public function jobSeeker()
    {
        return $this->belongsTo(JobSeeker::class);
    }

    /*
    * referencing which table to use for this MODEL
    */
    protected $table = 'job_seekers_ratings';

}
