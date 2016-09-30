<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
	protected $fillable = [
		'name'
	];

   /**
	* retrieve the advert related to this skill
	*
	*/
    public function adverts()
    {
    	return $this->belongsToMany(Advert::class);
    }
}
