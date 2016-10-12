<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecificSchedule extends Model
{
	protected $fillable = [
    	'start_date',
        'end_date',
        'start_time',
        'end_time',            
    ];

	public function advert()
    {
    	return $this->belongsTo(Advert::class);
    }

    protected $table = 'specific_schedules';
}
