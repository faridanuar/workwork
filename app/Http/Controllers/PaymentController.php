<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use \Braintree_ClientToken;
use \Braintree_Transaction;

class PaymentController extends Controller
{
    /**
	* Auhthenticate user
	*/
	public function __construct()
	{
	    $this->middleware('employer', ['except' => ['plans', 'subscribe']]);
	}



	public function plans()
	{
		return view('subscriptions.plans');
	}



	public function subscribe()
	{

		return view('subscriptions.subscribe');
	}

	public function trial()
	{

	}



	public function checkout(Request $request)
	{
		$user = $request->user();

		$plan = $request->plan;


		if($user->subscribed('main')){

			echo 'You have already subscribed to a plan';

		}else{

			// fetching the card token that has been given and set as a nounce from braintree server and set it as a variable.
			$nonceFromTheClient = $request->payment_method_nonce;

			// create a NEW subscribtion for the user
			$subscribing = $user->newSubscription('main', $plan)->create($nonceFromTheClient);

			// check if subscribtion is a success
			if($subscribing)
			{
				echo 'success';

			}else{

				echo 'error';
			}

		}

		
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

			echo 'on Grace Period:';
			dd($user->subscription('main')->onGracePeriod());

		}

	}



	public function resume(Request $request)
	{
		$user->subscription('main')->resume();
	}



	public function status(Request $request)
	{
		echo "subscription status:";

		$user = $request->user();

		dd($user->subscribed('main'));
	}



}
