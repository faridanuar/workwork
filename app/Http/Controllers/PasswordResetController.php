<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;

use App\User;

use App\Http\Requests;

class PasswordResetController extends Controller
{
	public function getEmail()
	{
		return view('auth.passwords.email');
	}

	public function sendResetLink(Request $request)
	{
		$this->validate($request, [
            'email' => 'required|email',
        ]);

		$user = $request->user();

		$reset_code = $user->password;

		// fetch mailgun attributes from SERVICES file
        $config = config('services.mailgun');

        // fetch website provided url
        $website = $config['site_url'];

        // use send method form Mail facade to send email. ex: send('view', 'info / array of data', fucntion)
        Mail::send('auth.emails.password_reset', compact('user','website','reset_code'), function ($m) use ($user) {

            // fetch mailgun attributes from SERVICES file
            $config = config('services.mailgun');

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

        flash('we have sent your reset link. Please check our email','success');

        return redirect()->back();
	}



	public function getNewPassword()
	{
		return view('auth.passwords.password');
	}


	public function updatePassword()
	{
		return view('auth.passwords.email');
	}
}
