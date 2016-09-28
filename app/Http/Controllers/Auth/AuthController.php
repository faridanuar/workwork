<?php

namespace App\Http\Controllers\Auth;

use Validator;

use App\User;
use App\Employer;
use App\Job_Seeker;

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
            $user->ftu_level = "lvl1";

            $user->save();

            //assign user a roles with permissions using "assignRole" method from hasRoles trait
            $user->assignRole('employer');

        }elseif($user->type === 'job_seeker'){

            $user->ftu_level = "lvl1";

            $user->save();

            //assign user a roles with permissions using "assignRole" method from hasRoles trait
            $user->assignRole('job_seeker');
        }

        return $user;
    }
}
