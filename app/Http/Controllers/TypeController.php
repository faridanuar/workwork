<?php

namespace App\Http\Controllers;

use Mail;

use App\User;
use App\Employer;
use App\JobSeeker;

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
    public function choose()
    {
        return view('auth.types.choose_type');
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
        $user->verified = 1;
        $user->save();
        // fetch mailgun attributes from SERVICES file
        // $config = config('services.mailgun');

        // fetch website provided url
        // $website = $config['site_url'];

        if($user->type === 'employer')
        {
            //assign user a roles with permissions using "assignRole" method from hasRoles trait
            $user->assignRole('employer');

            /**
            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeEmployer', compact('user', 'website'), function ($m) use ($user) {

                $config = config('services.mailgun');

                $domain = $config['sender'];

                //recipient = $user->email;
                $recipient = 'farid@pocketpixel.com';

                $recipientName = $user->name;

                // set email sender stmp url and sender name
                $m->from($domain, 'WorkWork');

                // set email recepient and subject
                $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
            });
            */

        }elseif($user->type === 'job_seeker'){

            //assign user a roles with permissions using "assignRole" method from hasRoles trait
            $user->assignRole('job_seeker');

            /**
            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeJobSeeker', compact('user','website'), function ($m) use ($user) {

                $config = config('services.mailgun');

                $domain = $config['sender'];

                //recipient = $user->email;
                $recipient = 'farid@pocketpixel.com';

                $recipientName = $user->name;

                // set email sender stmp url and sender name
                $m->from($domain, 'WorkWork');

                // set email recepient and subject
                $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
            });
            */
        }

        // check if save is successful
        if($user){

            //check what is user selected type and if user profile info is not created
            if ($user->type === 'job_seeker' && !$user->jobSeeker)
            {   
                // redirect to create profile page
                return redirect('/profile/create');

            }elseif($user->type === 'employer' && !$user->employer){

                // redirect to create company profile page
                return redirect('/company/create');

            }else{

                // redirect to 
                return redirect('/dashboard');
            }
        }
    }
}
