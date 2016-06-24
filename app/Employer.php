<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
	/**
	 * Fillable fields for an employer.
	 *
	 * @var array
	 */
    //protected $fillable = [];


    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function advert()
    {
    	return $this->hasMany(Advert::class);
    }
}
