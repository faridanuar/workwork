<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{

    public static $autoIndex = true;
    public static $autoDelete = true;

	/**
	 * Fillable fields for an advert.
	 *
	 * @var array
	 */
    protected $fillable = [
    	'job_title',
        'salary',
        'description',
        'business_name',            
        'location',
        'street',
        'city',
        'zip',
        'state',
        'country',
        'skill',
        'category',
        'rate',
        'oku_friendly'
    ];


    /**
    *
    *
    *
    *
    *
    */
    public function scopeLocatedAt($query, $id, $job_title)
    {
        //$job_title = str_replace('-', ' ', $job_title);

        return $query->where(compact('id', 'job_title'));
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function ownedBy(User $user)
    {
        $employer = $user->employer()->first();

        if(!$employer)
        {
            return redirect('/');
        }

        return $this->employer_id == $employer->id;
    }

}
