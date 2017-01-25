<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $fillable = [
		'activity',
		'description',
		'table',
		'user'
	];

	protected $table = 'activities';

}
