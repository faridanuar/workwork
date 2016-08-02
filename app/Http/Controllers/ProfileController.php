<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ProfileController extends Controller
{
    public function create()
    {
    	return view('profiles.company_create');
    }



    public function store(Request $request)
    {
    	$user = $request->user();

    	$employer = $user->employer()->first();

    	$employer->update([

				'business_name' => $request->business_name,
				'street' => $request->street,
				'city' => $request->city,
				'zip' => $request->zip,
				'state' => $request->state,
				'company_intro' => $request->company_intro,
    	]);

    	$employer->save();

    	redirect('/photo');
    }



    public function photo()
    {
    	return view('profiles.upload');
    }



    public function upload(Request $request)
    {
    	$employer = $request->user()->employer()->first();

    	$file = $request->file('business_logo');

    	$name = time() . '-' .$file->getClientOriginalName();

    	$file->move('profile_images', $name);

    	$employer->update([

				'business_logo' => "/profile_images/{$name}"
    	]);

    	$employer->save();

    	return 'Done';
    }
}
