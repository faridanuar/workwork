<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Employer;

use App\Http\Requests;
use App\Http\Requests\EmployerRequest;

class CreateCompanyController extends Controller
{
	public function __construct()
    {
        $this->middleware('noCompanyProfile');
    }


    public function create(Request $request)
    {
        $user = $request->user();

        $done = 0;
        $notDone = -3;

        return view('profiles.company.company_create', compact('user','done','notDone'));
    }



    public function store(EmployerRequest $request)
    {
        // store user info in variable
        $user = $request->user();

        // create a new company profile and store it in employers table
        $employer = $user->employer()->create([

            // 'column' => request->'field'
            'business_name' => $request->business_name,
            'business_category' => $request->business_category,
            'business_contact' => $request->business_contact,
            'company_intro' => $request->company_intro,
        ]);

        // user proceed to create advert
        $user->ftu_level = 1;

        // set user ftu
        $user->save();

        // redirect to dashboard
        return redirect('/adverts/create');
    }
}
