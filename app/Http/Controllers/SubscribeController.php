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

	

	public function checkout(Request $request, $id)
	{
		$user = $request->user();

		$plan = $request->plan;

		if($plan === "Trial")
		{
			$advert = Advert::find($id);

			if($advert->trial_used != 0){

				flash('Sorry, the trial plan is not valid anymore');

				return redirect()->route('plan', [$id]);

			}else{

				$days = 30;

				$advert->plan_ends_at = Carbon::now()->addDays($days);

				$advert->trial_used = 1;

				$saved = $advert->save();

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

		return view('subscriptions.checkout', compact('id','plan','user','done','notDone'));
	}



	protected function charge(Request $request, $id)
	{
		// fetch user authentication
		$user = $request->user();

		// fetch user selected plan
		$plan = $request->plan;

        // fetched the card token that has been given and set as a nounce by braintree server and set the nounce as a variable.
		$nonceFromTheClient = $request->payment_method_nonce;

		//check if user has purchase a plan before
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

				flash('Checkout was unsuccessful, please try again later', 'error');

				return redirect('/subscribe');

			    foreach($result->errors->deepAll() AS $error){

			        echo($error->code . ": " . $error->message . "\n");
			    }
			}
		}

		$advert = Advert::find($id);

        switch ($plan)
		{
			case "Pioneer_Promo":

				$singleCharge = $user->invoiceFor($plan, 7.50);

	        	$days = 30;

	        	$advert->current_plan = $plan;

	        	$advert->plan_ends_at = Carbon::now()->addDays($days);
				break;

			case "1_Month_Plan":

				$singleCharge = $user->invoiceFor($plan, 7.50);

	        	$days = 30;

	        	$advert->current_plan = $plan;

	        	$advert->plan_ends_at = Carbon::now()->addDays($days);
				break;

			default:

				flash('Your checkout was unsuccessful', 'error');
				return redirect()->back();
		}
        $saved = $advert->save();

        if($user->ftu_level === 2)
		{
			$user->ftu_level = 3;
			$user->save();
		}elseif($advert->advert_level < 3){
			$advert->advert_level = 2;
			$advert->save();
		}

        /**
        if($saved)
        {
	        $config = config('services.algolia');

			$index = $config['index'];

			$indexFromAlgolia = $search->index($index);

			$object = $indexFromAlgolia->addObject(
		
			    [
			    	'id' => $advert->id,
			        'job_title' => $advert->job_title,
			        'salary'  => (float)$advert->salary,
			        'description'  => $advert->description,
			        'business_name'  => $advert->business_name,
			        'location'  => $advert->location,
			        'street'  => $advert->street,
			        'city'  => $advert->city,
			        'zip'  => $advert->zip,
			        'state'  => $advert->state,
			        'country'  => $advert->country,
			        'created_at'  => $advert->created_at->toDateTimeString(),
			        'updated_at'  => $advert->updated_at->toDateTimeString(),
			        'employer_id'  => $advert->employer_id,
			        'skill'  => $advert->skill,
			        'category'  => $advert->category,
			        'rate'  => $advert->rate,
			        'oku_friendly'  => $advert->oku_friendly,
			        'open' => $advert->open,
			        'avatar'  => $advert->avatar,
			        'schedule_id'  => $advert->schedule_id,
			    ],
			    $advert->id
			);
		}
		*/

        if($saved)
        {
        	flash('You have successfully purchased a new plan. check back your advert details before publishing', 'info');

        	return redirect()->route('show', [$id,$advert->job_title]);
			
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
        'vendor'  => 'WorkWork.my',
        'product' => 'WorkWork Subscription Plan',
        ]);
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
