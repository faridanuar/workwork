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
    protected $fillable = [
        'business_name',
        'business_category',
        'business_contact',
        'business_website',
        'location',
        'street',
        'city',
        'zip',
        'state',
        'company_intro',
        'business_logo',
    ];


    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function advert()
    {
    	return $this->hasMany(Advert::class);
    }
}
