<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Braintree_ClientToken;
use \Braintree_Transaction;

use App\User;

use App\Http\Requests;


class StatusController extends Controller
{
    /**
	* Auhthenticate user
	*/
	public function __construct()
	{
	    $this->middleware('employer');
	}



	public function status(Request $request)
	{
		echo "Subscription status:";

		$user = $request->user();

		dd($user->subscribed('main'));
	}



	public function cancel(Request $request)
	{
		$user = $request->user();

		if($user->subscribed('main'))
		{	

			if($user->subscription('main')->cancel())
			{
				echo 'true';

			}else{

				echo 'false';
			}

		}else{

			echo 'On Grace Period:';
			dd($user->subscription('main')->onGracePeriod());
		}
	}



	public function resume(Request $request)
	{
		$user->subscription('main')->resume();
	}


}
