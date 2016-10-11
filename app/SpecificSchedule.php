<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecificSchedule extends Model
{
	public function advert()
    {
    	return $this->belongsTo(Advert::class);
    }

    protected $table = 'specific_schedules';
}
