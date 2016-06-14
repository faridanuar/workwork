<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
class Job_Seeker extends Model
{
    protected $fillable = [

    	'biodata',

    ];


    /**
     * defining "job_seekers" table relationship with "users" table
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }


    /*
    * referencing which table to use for this MODEL
    */
    protected $table = 'job_seekers';
}
