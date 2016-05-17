<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Newsletter\Contracts\NewsletterContract;
use App\Services\Newsletter\Exceptions\UserAlreadySubscribedException;



class NewsletterController extends Controller
{
    protected $newsletter;

    public function __construct(NewsletterContract $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        try {
            $this->newsletter->subscribe(
                config('services.mailchimp.list'),
                $request->email
            );
        } catch (UserAlreadySubscribedException $e) {
            return redirect()->back()->withInput()->withErrors([
                'email' => $e->getMessage(),
            ]);
        }

        return redirect()->back();
    }
}
