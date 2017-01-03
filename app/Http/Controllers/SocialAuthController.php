<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SocialAccountService;

use App\User;

use Socialite;

class SocialAuthController extends Controller
{
     public function redirect()
    {
        return Socialite::driver('facebook')->redirect();   
    }   

    public function callback(SocialAccountService $service)
    {
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());

        if($user->email)
        {
            $user->verified = 1;
        }
        
        $user->save();

        auth()->login($user);

        return redirect()->to('/dashboard');
    }
}
