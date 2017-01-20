<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Mail;

use App\User;
use App\Employer;

use App\Http\Requests;

class AdminRegistrationController extends Controller
{
    /**
     * Create a new sessions controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Show the register page.
     *
     * @return \Response
     */
    public function register()
    {
        return view('admin.auth.admin_register');
    }



    /**
     * Perform the registration.
     *
     * @param  Request   $request
     * @param  AppMailer $mailer
     * @return \Redirect
     */
    public function postRegister(Request $request)
    {
        //validate fields
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // create a new user with the given field
        $user = User::create([
                   'name' => $request->name,
                   'email' => $request->email,
                   'password' => $request->password,
                   'type' => "admin",
                   'avatar' => "/images/defaults/default.jpg"
                ]);

        // assign user a roles with permissions using "assignRole" method from hasRoles trait
        $user->assignRole('admin');

        // create a new company profile and store it in employers table
        $employer = $user->employer->create([
            // 'column' => request->'field'
            'business_name' => "WorkWork",
            'business_category' => "Others",
        ]);

        // flash info message after registered
        flash('You can now Login', 'info');

        return redirect('/a/login');
    }

}
