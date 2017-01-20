<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;

use App\Http\Requests;

class AdminSessionsController extends Controller
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
        return view('admin.auth.admin_login');
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
            return redirect()->intended('/a/dashboard');
        }

        flash('The credentials did not match our records.','error');

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
        return redirect('/a/login');
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
            'type' => 'admin',
            'verified' => 1
        ];
    }
}
