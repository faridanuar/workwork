<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
	* Get job seekers assosicated with the given categories
	*
	*/
    public function jobSeekers()
    {
    	return $this->belongsToMany(Job_Seeker::class, 'category_jobSeeker', 'job_seeker_id');
    }

    protected $table = 'categories';
}
