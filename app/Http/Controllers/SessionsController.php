<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use App\Http\Requests;

class SessionsController extends Controller
{
    /**
     * Create a new sessions controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }
    /**
     * Show the login page.
     *
     * @return \Response
     */
    public function login()
    {
        return view('auth.login');
    }
    /**
     * Perform the login.
     *
     * @param  Request  $request
     * @return \Redirect
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, ['email' => 'required|email', 'password' => 'required|min:6']);

        if($this->signIn($request))
        {
            flash('Welcome back!','info');

            return redirect()->intended('/dashboard');
        }

        flash('The credentials did not match our records. Is your email verified?','error');

        return redirect()->back();
    }



    /**
     * Destroy the user's current session.
     *
     * @return \Redirect
     */
    public function logout()
    {
        Auth::logout();
        flash('You have now been signed out.','info');
        return redirect('/login');
    }



    /**
     * Attempt to sign in the user.
     *
     * @param  Request $request
     * @return boolean
     */
    protected function signIn(Request $request)
    {
        return Auth::attempt($this->getCredentials($request), $request->has('remember'));
    }



    /**
     * Get the login credentials and requirements.
     *
     * @param  Request $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'verified' => 1,
        ];
    }
}
