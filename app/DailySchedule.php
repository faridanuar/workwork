<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailySchedule extends Model
{
    protected $fillable = [
    	'start_time',
    	'end_time',
    ];

    public function adverts()
    {
    	return $this->belongsToMany(Advert::class);
    }

    protected $table = 'daily_schedules';
}
