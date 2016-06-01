<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
	/**
	 * Fillable fields for an advert.
	 *
	 * @var array
	 */
    protected $fillable = [
    	'job-title',
        'salary',
        'description',
        'business-name',            
        'location',
        'street',
        'city',
        'zip',
        'state',
        'country'
    ];
}
