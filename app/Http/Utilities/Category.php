<?php

namespace App\Http\Utilities;

class Category
{
	protected static $categories = [
		"Computer" => "Computer",
		"Food & Beverages" => "Food & Beverages",
		"Business" => "Business",
		"Facory" => "Factory",
		
		
	];
	public static function all()
	{
		// return array_values(static::$countries);	
		return static::$categories;	
	}
}