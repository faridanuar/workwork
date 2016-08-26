<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Braintree_ClientToken;
use \Braintree_Transaction;

use App\User;

use App\Http\Requests;

class SubscribeController extends Controller
{
    /**
	* Auhthenticate user
	*/
	public function __construct()
	{
	    $this->middleware('employer', ['except' => ['plans']]);
	}



	public function plans()
	{
		return view('subscriptions.plans');
	}

	

	public function subscribe()
	{
		return view('subscriptions.subscribe');
	}



	public function checkout(Request $request)
	{
		// fetch user authentication
		$user = $request->user();

		// fetch user selected plan
		$plan = $request->plan;

		// fetching the card token that has been given and set as a nounce from braintree server and set it as a variable.
		$nonceFromTheClient = $request->payment_method_nonce;


		if($user->subscribed('main')){

			// change to a new plan
			$subscribing = $user->subscription('main')->swap($plan);

		}else{

			// create a NEW subscribtion for the user
			$subscribing = $user->newSubscription('main', $plan)->create($nonceFromTheClient, [
			]);
		}

		// check if subscribtion is a success
		if($subscribing)
		{
			flash('you have successfully subscribe to a new plan', 'success');

			return redirect('/dashboard');

		}else{

			flash('Checkout was unsuccessful, please check back your paymnent info and try again', 'error');

			return redirect('/subscribe');
		}
	}



	public function invoices(Request $request)
	{
		$user = $request->user();

		$invoices = $user->invoices();

		//dd($invoices);

		return view('subscriptions.invoices', compact('invoices'));
	}



	public function download(Request $request, $invoiceId)
	{
		$user = $request->user();

		return $user->downloadInvoice($invoiceId, [
        'vendor'  => 'WorkWork.Com',
        'product' => 'WorkWork Subscription Plan',
        ]);
	}
}
