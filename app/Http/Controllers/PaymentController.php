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
	    $this->middleware('auth');
	}

	public function selection()
	{
		$clientToken = Braintree_ClientToken::generate();

		return view('subscriptions.subscribe', compact('clientToken'));
	}



	public function pay(Request $request)
	{
		$user = $request->user();

		$nonceFromTheClient = $request->payment_method_nonce;

		dd($nonceFromTheClient);

		$result = Braintree_Transaction::sale([
		  'amount' => '10.00',
		  'paymentMethodNonce' => $nonceFromTheClient,
		  'options' => [
		    'submitForSettlement' => True
		  ]
		]);

		if($result){

			echo 'success';
		}


		//$user->newSubscription('1MonthAdvertisement', '1MonthAdverts')->create($creditCardToken);

		
	}


}
