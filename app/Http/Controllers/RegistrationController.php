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
        $this->middleware('guest', ['except' => ['getToken', 'sendToken', 'sent', 'verify', 'verifyStatus', 'getEmail']]);
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
        //validate fields
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'contact' => 'required',
            'type' => 'required',
        ]);

        // create a new user with the given field
        $user = User::create($request->all());

        // generate a random string
        $verification_code = str_random(30);

        // store the generated token/verification code to the selected user
        $user->verification_code = $verification_code;
        $user->save();

        // fetch mailgun attributes from SERVICES file
        $config = config('services.mailgun');
        // applications domain
        $domain = $config['sender'];
        // fetch website provided url
        $website = $config['site_url'];

        // set the values in array
        $data = ['user' => $user, 'website' => $website, 'verification_code' => $verification_code];
        $parameter = ['user' => $user, 'domain' => $domain];

        // check what user registered as
        switch($user->type)
        {
            case 'employer':
                // assign user a roles with permissions using "assignRole" method from hasRoles trait
                $user->assignRole('employer');

                // set the email view
                $emailView = 'mail.welcome_employer';
                break;
            case 'job_seeker':
                // assign user a roles with permissions using "assignRole" method from hasRoles trait
                $user->assignRole('job_seeker');

                // set the email view
                $emailView = 'mail.welcome_job_seeker';
                break;
            default:
        }

        // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
        Mail::send($emailView, $data, function ($message) use ($parameter) {

            // Recipient Test Email => $recipient = "farid@pocketpixel.com";

            // get the necessary required values for mailgun
            $appDomain = $parameter['domain'];
            $recipient = $parameter['user']->email;
            $recipientName = $parameter['user']->name;

            // set email sender stmp url and sender name
            $message->from($appDomain, 'WorkWork');

            // set email recepient and subject
            $message->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
        });

        // flash info message after registered
        flash('We have sent you an email. Please verify your email first before logging in', 'info');

        return redirect('/login');
    }



    public function getToken()
    {
        return view('auth.get_email');
    }



    public function sendToken(Request $request)
    {
        // generate a random string
        $verification_code = str_random(30);

        // find the user with the given email
        $user = User::where('email', $request->email)->fisrt();

        // store the generated token/verification code to the selected user
        $user->verification_code = $verification_code;
        $user->save();

        // fetch mailgun attributes from SERVICES file
        $config = config('services.mailgun');

        // fetch website provided url
        $website = $config['site_url'];

        // set the values in array
        $data = ['website' => $website, 'verification_code' => $verification_code];
        $parameter = ['user' => $user, 'domain' => $domain];

        // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
        Mail::send('auth.emails.verify_email', compact('website','verification_code'), function ($message) use ($parameter) {

            // Recipient Test Email => $recipient = "farid@pocketpixel.com";

            // get the necessary required values for mailgun
            $appDomain = $parameter['domain'];
            $recipient = $parameter['user']->email;
            $recipientName = $parameter['user']->name;

            // set email sender stmp url and sender name
            $message->from($appDomain, 'WorkWork');

            // set email recepient and subject
            $message->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
        });

        return redirect('/link/sent');
    }



    public function sent()
    {
        return view('auth.verifications.sent_link_message');
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
        return view('auth.verifications.verification_status');
    }



    public function getEmail(Request $request)
    {
        return view('auth.passwords.email');
    }
}
