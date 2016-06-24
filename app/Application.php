<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /**
     * Fillable fields for an application.
     *
     * @var array
     */
    protected $fillable = [

    	'status',

    ];

    public function jobSeeker()
    {
    	return $this->belongsTo(Job_Seeker::class);
    }

    public function advert()
    {
    	return $this->belongsTo(Advert::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    /*
    * referencing which table to use for this MODEL
    */
    protected $table = 'applications';

}
