<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AdvertsController extends Controller
{
	/**
	 * Create a new advert
	 */
	public function create()
	{
		return view('adverts.create');
	}
}
