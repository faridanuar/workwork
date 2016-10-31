<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use App\User;
use App\Employer;
use App\JobSeeker;

use App\Http\Requests;

class RegistrationController extends Controller
{
    /**
     * Create a new registration instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['verify', 'verifyStatus', 'getEmail']]);
    }



    /**
     * Show the register page.
     *
     * @return \Response
     */
    public function register()
    {
        return view('auth.register');
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'contact' => 'required',
            'type' => 'required',
        ]);
        $user = User::create($request->all());

        // generate a random string to be used as a verifcation code
        $verification_code = str_random(30);
        $contact_verification_code = str_random(5);

        $user->verification_code = $verification_code;
        $user->contact_verification_code = $contact_verification_code;
        $user->save();

        // fetch mailgun attributes from SERVICES file
        $config = config('services.mailgun');

        // fetch website provided url
        $website = $config['site_url'];

        switch($user->type)
        {
            case 'employer':
                //assign user a roles with permissions using "assignRole" method from hasRoles trait
                $user->assignRole('employer');

                // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
                Mail::send('mail.welcomeEmployer', compact('user','website','verification_code'), function ($m) use ($user) {

                    // fetch mailgun attributes from SERVICES file
                    $config = config('services.mailgun');

                    // fetch mailgun provided domain
                    $domain = $config['sender'];

                    //recipient = $user->email;
                    $recipient = "farid@pocketpixel.com";

                    $recipientName = $user->name;

                    // set email sender stmp url and sender name
                    $m->from($domain, 'WorkWork');

                    // set email recepient and subject
                    $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
                });
            break;

            case 'job_seeker':
                //assign user a roles with permissions using "assignRole" method from hasRoles trait
                $user->assignRole('job_seeker');

                // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
                Mail::send('mail.welcomeJobSeeker', compact('user','website','verification_code'), function ($m) use ($user) {

                    // fetch mailgun attributes from SERVICES file
                    $config = config('services.mailgun');

                    // fetch mailgun provided domain
                    $domain = $config['sender'];

                    //$recipient = $user->email;
                    $recipient = "farid@pocketpixel.com";

                    $recipientName = $user->name;

                    // set email sender stmp url and sender name
                    $m->from($domain, 'WorkWork');

                    // set email recepient and subject
                    $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
                });
            break;

            default:
        }

        flash('We have sent you an email. Please verify your email first before logging in ');

        return redirect('/login');
    }



    public function verify($verification_code)
    {
        if(!$verification_code)
        {
            abort(403,'Unauthorized action');
        }

        $user = User::where('verification_code',$verification_code)->first();

        if(!$user)
        {
            return redirect('/verify/status');
        }

        $user->verified = 1;
        $user->verification_code = null;
        $user->save();

        return redirect('/verify/status');
    }



    public function verifyStatus(Request $request)
    {
        return view('auth.verification_status');
    }


    public function getEmail(Request $request)
    {
        return view('auth.passwords.email');
    }
}
