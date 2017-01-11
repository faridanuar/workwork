<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;

use Mail;
use Crypt;

use App\User;

use App\Http\Requests;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }


	public function getEmail()
	{
		return view('auth.passwords.email');
	}



	public function sendResetLink(Request $request)
	{
		$this->validate($request, [
            'email' => 'required|email',
        ]);

		$user = User::where('email',$request->email)->first();

        if(!$user)
        {
            flash('The email you provided does not exist','error');
            return redirect()->back();
        }

        $reset_token = Crypt::encrypt($request->email);

		// fetch mailgun attributes from SERVICES file
        $config = config('services.mailgun');

        // fetch website provided url
        $website = $config['site_url'];

        // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
        Mail::send('auth.emails.password_reset', compact('user','website','reset_token'), function ($m) use ($user,$config) {

            // fetch mailgun attributes from SERVICES file
            //$config = config('services.mailgun');

            // fetch mailgun provided domain
            $domain = $config['sender'];

            $recipient = $user->email;
            //$recipient = "farid@pocketpixel.com";

            $recipientName = $user->name;

            // set email sender stmp url and sender name
            $m->from($domain, 'WorkWork');

            // set email recepient and subject
            $m->to($recipient, $recipientName)->subject('Password');
        });

        flash('we have sent your reset link. Please check your email','info');

        return redirect()->back();
	}



	public function getNewPassword($reset_token)
	{
		return view('auth.passwords.reset', compact('reset_token'));
	}



	public function updatePassword(Request $request)
	{
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $decryptedToken = Crypt::decrypt($request->token);

        } catch (DecryptException $e) {

            return redirect('/');
        }

        if($decryptedToken === $request->email)
        {
            $user = User::where('email', $decryptedToken)->firstOrFail();
            $user->password = $request->password;
            $user->save();
        }

        flash('your password has been successfully reset','success');
		return redirect('login');
	}
}
