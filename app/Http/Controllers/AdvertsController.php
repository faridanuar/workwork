<?php

namespace App\Http\Controllers;


use App\Advert;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\AdvertRequest;

class AdvertsController extends Controller
{

	/**
	 * Index - list of adverts
	 */
	public function index()
	{
		$adverts = Advert::all();

		return view('adverts.index', compact('adverts'));
	}

	/**
	 * Create a new advert
	 */
	public function create()
	{
		return view('adverts.create');
	}

	/**
	 * Store a newly created resource in storage
	 *
	 * @param AdvertRequest $request
	 */
	public function store(AdvertRequest $request)
	{
	// what do we need to do? if the request validates, the body below of this method will be hit
		// validate the form - DONE		
		// persist the advert - DONE
		Advert::create($request->all());
		// redirect to a landing page, so that people can share to the world DONE, kinda
		// next, flash messaging
		return redirect()->back();
	}


	/**
	*show existing data in storage
	*
	*calling locatedAt function from Advert MODEL
	*
	*@param $id, $job_title
	*/
	public function show($id, $job_title)
	{

		// display only the first retrieved
		$job = Advert::locatedAt($id, $job_title)->first();
		
		// display "show" page
		return view('adverts.show', compact('job'));


	}
}
