<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Braintree_ClientToken;
use \Braintree_Transaction;
use \Braintree_Customer;

use Carbon\Carbon;

use App\User;
use App\Advert;

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



	protected function checkout(Request $request)
	{
		// fetch user authentication
		$user = $request->user();

		// fetch user selected plan
		$plan = $request->plan;

        // fetching the card token that has been given and set as a nounce from braintree server and set it as a variable.
		$nonceFromTheClient = $request->payment_method_nonce;

		if($user->braintree_id === null){

			$result = Braintree_Customer::create([
			    'firstName' => $user->name,
			    'company' => $user->employer->business_name,
			    'email' => $user->email,
			    'phone' => $user->contact,
			    'paymentMethodNonce' => $nonceFromTheClient
			]);

			$user->braintree_id = $result->customer->id;
			$user->save();

			if($result->success) { 

			}else{

			    foreach($result->errors->deepAll() AS $error){

			        echo($error->code . ": " . $error->message . "\n");
			    }
			}
		}

        if($plan === "1_Month_Plan"){

        	$singleCharge = $user->invoiceFor($plan, 7.50);

        	$days = 30;

        	$user->plan_ends_at = Carbon::now()->addDays($days);

        }elseif($plan === "2_Month_Plan"){

        	$singleCharge = $user->invoiceFor($plan, 12.25);

        	$days = 60;

        	$user->plan_ends_at = Carbon::now()->addDays($days);

        }elseif($plan === "Pioneer_Promo"){

        	$singleCharge = $user->invoiceFor($plan, 3.70);

        	$days = 30;

        	$user->plan_ends_at = Carbon::now()->addDays($days);
        }
        $user->save();

        $employerID = $user->employer->id;

        if($employerID != null)
        {
	        $adverts = Advert::where('employer_id', $employerID)->get();

	        if($adverts != null)
	        {
	        	foreach($adverts as $advert)
	        	{
	        		$advert->ends_at = Carbon::now()->addDays($days);
	        		$advert->save();
	        	}
	        }
	    }

        if($singleCharge)
        {
        	flash('you have successfully purchased a new plan', 'success');

			return redirect('/dashboard');
			
        }else{

        	flash('Checkout was unsuccessful, please check back your paymnent info and try again', 'error');

			return redirect('/subscribe');
        }

		/*
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
			flash('you have successfully purchase a new plan', 'success');

			return redirect('/dashboard');

		}else{

			flash('Checkout was unsuccessful, please check back your paymnent info and try again', 'error');

			return redirect('/subscribe');
		}
		*/
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
