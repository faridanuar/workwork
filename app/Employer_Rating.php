<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employer_Rating extends Model
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
        return $this->belongsTo(Job_Seeker::class);
    }

    /*
    * referencing which table to use for this MODEL
    */
    protected $table = 'employers_ratings';

}
