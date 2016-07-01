<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TypeController extends Controller
{

    /**
    * Auhthenticate user with no roles assign to them
    */
    public function __construct()
    {
        $this->middleware('noRoleType');
    }



    /**
     * Show the choose type page
     *
     * @return \Illuminate\Http\Response
     */
    public function type()
    {
        
        return view('auth.types.fb_type');

    }



    /**
     * Assign the role that the user has selected
     *
     * @param $request
     */
    public function assignType(Request $request)
    {
        //fetch log in user data
        $user = $request->user();

        //update user record
        $user->update([

            'type' => $request->type,

        ]);


        //save changes made
        $user->save();


        //check what kind of role have been selected and assign roles premission to the user
        if ( $user && $user->type == 'job_seeker')
        {
            //assign roles permissions with "assignRole" method from hasRoles trait
            $user->assignRole('job_seeker');

        }elseif($user && $user->type == 'employer'){

            $user->assignRole('employer');

            //create new model from Employer
            $employer = new Employer;

            //set user_id in the Employer model using associate method
            $employer->user()->associate($user);

            //save changes
            $employer->save();

        }else{

            //abort if condition is not met
            abort(401, 'You are not allowed.');

        }

        return redirect('/home');
    }

}
