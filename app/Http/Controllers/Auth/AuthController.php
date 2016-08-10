<?php

namespace App\Http\Controllers\Auth;

use Mail;
use Validator;

use App\User;
use App\Employer;
use App\Job_Seeker;
use \Braintree_ClientToken;
use \Braintree_Transaction;

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
    protected $redirectTo = '/';

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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'contact' => $data['contact'],
            'type' => $data['type'],
        ]);
        

        if ( $user && $user->type == 'job_seeker'){

                $user->assignRole('job_seeker');

                $employer = new Job_Seeker;

                $employer->user()->associate($user);

                $employer->save();

                if($user){
                    Mail::send('mail.welcomeJobSeeker', compact('user'), function ($m) use ($user) {
                        $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

                        $m->to('farid@pocketpixel.com', $user->name)->subject('Welcome to WorkWork!');
                    });
                }

        }elseif($user && $user->type == 'employer'){

                $user->assignRole('employer');

                $employer = new Employer;

                $employer->user()->associate($user);

                $employer->save();

                if($user){
                    Mail::send('mail.welcomeEmployer', compact('user'), function ($m) use ($user) {
                        $m->from('postmaster@sandbox12f6a7e0d1a646e49368234197d98ca4.mailgun.org', 'WorkWork');

                        $m->to('farid@pocketpixel.com', $user->name)->subject('Welcome to WorkWork!');
                    });
                }

                $user->update([

                    'trial_ends_at' => Carbon::now()->addDays(7),

                ]);

                $user->save();

        }else{

             abort(401, 'You are not allowed.');

        }

        return $user;
        
        return redirect()->intended('defaultpage');


    }
}
