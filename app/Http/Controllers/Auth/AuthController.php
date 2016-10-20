<?php

namespace App\Http\Controllers\Auth;

use Validator;
use Mail;

use App\User;
use App\Employer;
use App\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'contact' => 'required',
            'type' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // create and store a new row of record for newly registered user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'contact' => $data['contact'],
            'type' => $data['type'],
        ]);

        if($user->type === 'employer')
        {
            //assign user a roles with permissions using "assignRole" method from hasRoles trait
            $user->assignRole('employer');

            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeEmployer', compact('user'), function ($m) use ($user) {

                $config = config('services.mailgun');

                $domain = $config['sender'];

                $recipient = $user->email;
                //$recipient = "farid@pocketpixel.com";

                $recipientName = $user->name;

                // set email sender stmp url and sender name
                $m->from($domain, 'WorkWork');

                // set email recepient and subject
                $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
            });

        }elseif($user->type === 'job_seeker'){

            //assign user a roles with permissions using "assignRole" method from hasRoles trait
            $user->assignRole('job_seeker');

            // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
            Mail::send('mail.welcomeJobSeeker', compact('user'), function ($m) use ($user) {

                $config = config('services.mailgun');

                $domain = $config['sender'];

                $recipient = $user->email;
                //$recipient = "farid@pocketpixel.com";

                $recipientName = $user->name;

                // set email sender stmp url and sender name
                $m->from($domain, 'WorkWork');

                // set email recepient and subject
                $m->to($recipient, $recipientName)->subject('Welcome to WorkWork!');
            });
        }

        return $user;
    }
}
