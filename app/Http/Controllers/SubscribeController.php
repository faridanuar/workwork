<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \Braintree_ClientToken;
use \Braintree_Transaction;
use \Braintree_Customer;

use App\User;
use App\Advert;

use App\Http\Requests;

use App\Contracts\Search;

use Carbon\Carbon;

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



	public function choosePlan(Request $request, $id)
	{
		$user = $request->user();
		$advert = Advert::find($id)->firstOrFail();

		//perform this if user does not own this advert
		if(! $advert->ownedBy($user))
		{
			return $this->unauthorized($request);
		}

		if($user->ftu_level < 4)
		{
			// ftu level
			$done = 2;
        	$notDone = 1;
    	}else{
    		// advert level
    		$done = 1;
	        $notDone = 0;
    	}
		
		return view('subscriptions.choose_plan', compact('id','user','done','notDone'));
	}



	public function setPlan(Request $request, $id)
	{
		$advert = Advert::find($id);
		$advert->current_plan = $request->plan;
		$advert->save();

		return redirect()->route('checkout', [$advert->id]);
	}

	

	public function checkout(Request $request, $id)
	{
		$advert = Advert::find($id);
		$plan = $advert->current_plan;
		$user = $request->user();
		$customerID = "";

		//perform this if user does not own this advert
		if(! $advert->ownedBy($user))
		{
			return $this->unauthorized($request);
		}

		if($plan != "1_Month_Plan" && $plan != "2_Month_Plan")
		{
			switch ($plan)
			{
				case "Trial":
					if($user->trial_used != 1)
					{
						$days = 14;
						$advert->plan_ends_at = Carbon::now()->addDays($days);
						$advert->current_plan = "Trial";
						$advert->sms_count = 5;
						$user->trial_used = 1;	
					}else{
						flash('Sorry, the trial plan is not valid anymore');
						return redirect()->route('plan', [$id]);
					}
					break;
				case "Free":
					if($advert->free_plan_used != 1)
					{
						$days = 7;
						$advert->plan_ends_at = Carbon::now()->addDays($days);
						$advert->current_plan = "Free";
						$advert->free_plan_used = 1;
					}else{
						flash('Sorry, the free plan is not valid anymore');
						return redirect()->route('plan', [$id]);
					}
					break;
				default:
			}

			$saved = $advert->save();
			$user->save();

			if($saved)
			{
				if($user->ftu_level < 4)
				{
					$user->ftu_level = 3;
					$user->save();
				}elseif($advert->advert_level < 3){
					$advert->advert_level = 2;
					$advert->save();
				}
			}

			return redirect()->route('show', [$id,$advert->job_title]);
		}

    	if($user->braintree_id)
    	{
    		$customerID = $user->braintree_id;
    	}

    	$token = Braintree_ClientToken::generate([
		    "customerId" => $customerID
		]);
    	

    	if($user->ftu_level < 4)
		{
			// ftu level
			$done = 2;
        	$notDone = 1;
    	}else{
    		// advert level
    		$done = 1;
	        $notDone = 0;
    	}

		return view('subscriptions.checkout', compact('id','plan','user','done','notDone','token'));
	}



	protected function charge(Request $request, $id)
	{
		// fetch user authentication
		$user = $request->user();

		// fetch user selected plan
		$advert = Advert::find($id);
		$plan = $advert->current_plan;
		$savePayment = $request->savePayment;
		
		// default ID value
		$customerID = "";

		if($user->braintree_id)
		{
			// fetch user's customer ID
			$customerID = $user->braintree_id;
		}

        // fetched the card token that has been given and set as a nounce by braintree server
		$nonceFromTheClient = $request->payment_method_nonce;

		// RUN if PAYMENT NONCE is recieved.
		if($nonceFromTheClient)
		{
			//check if user already have a Customer ID
			if($customerID)
			{
				$result = Braintree_Customer::update($customerID, 
					[
					    'firstName' => $user->name,
					    'email' => $user->email,
					    'phone' => $user->contact,
					    'paymentMethodNonce' => $nonceFromTheClient
					]
				);
			}else{
				$result = Braintree_Customer::create([
				    'firstName' => $user->name,
				    'email' => $user->email,
				    'phone' => $user->contact,
				    'paymentMethodNonce' => $nonceFromTheClient
				]);
			}

			if($result->success)
			{ 
				$user->braintree_id = $result->customer->id;
				$user->save();

				switch ($plan)
				{
					case "2_Month_Plan":
						$price = 11.10;
			        	$days = 60;
						break;
					case "1_Month_Plan":
						$price = 6.57;
			        	$days = 30;
						break;
					default:
						$price = 0;
			        	$days = 0;
				}

				// Charging the user ONE TIME with INVOICE provided
				$charge = $user->invoiceFor($plan, $price);

				if($charge->success)
				{
				    $advert->plan_ends_at = Carbon::now()->addDays($days);
			        $saved = $advert->save();

			        if($user->ftu_level === 2)
					{
						$user->ftu_level = 3;
						$user->save();
					}elseif($advert->advert_level < 3){
						$advert->advert_level = 2;
						$advert->save();
					}

					flash('You have successfully purchased a new plan. check back your advert details before publishing', 'info');

		        	return redirect()->route('show', [$id,$advert->job_title]);
			    }else{
			    	flash('Checkout was unsuccessful, please check back your paymnent info and try again', 'error');

					return redirect()->route('checkout', [$advert->id]);
			    }				
			}

			flash('Checkout was unsuccessful, please try again', 'error');

			return redirect()->route('checkout', [$advert->id]);

			/*
		    foreach($result->errors->deepAll() AS $error){

		        echo($error->code . ": " . $error->message . "\n");
		    }
		    */

		}else{
			flash('There is something wrong when processing your payment info. Please check your info again', 'error');

			return redirect()->back();
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
        'vendor'  => 'WorkWork.my',
        'product' => 'WorkWork Subscription Plan',
        ]);
	}



	/**
	 * Perform this process if user is not authorized
	 *
	 * @param $request
	 */
	protected function unauthorized(Request $request)
	{
		if($request->ajax())
		{
			return response(['message' => 'No!'], 403);
		}

		flash('Sorry, you are not the owner of that page');

		return redirect('/');
	}
	

	/*
	public function subscribe(Request $request, $id)
	{
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
	}
	*/
}
