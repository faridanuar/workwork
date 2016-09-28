<?php

namespace App\Http\Utilities;

class Category
{
	protected static $categories = [
		"Admin/Data Entry" => "Admin/Data Entry",
		"Sales" => "Sales",
		"Customer Svc/Call Centre" => "Customer Svc/Call Centre",
		"Marketing/PR/Designers" => "Marketing/PR/Designers",
		"Retail/Promoters" => "Retail/Promoters",
		"Accounting/Tax/Audit" => "Accounting/Tax/Audit",
		"Manufacturing" => "Manufacturing",
		"Engineering" => "Engineering",
		"IT (Technology)" => "IT (Technology)",
		"Food/Restaurants/Cafe" => "Food/Restaurants/Cafe",
		"Education/Training" => "Education/Training",
		"Transport/Delivery" => "Transport/Delivery",
		"Construction" => "Construction",
		"Bank/Finance/Insurance" => "Bank/Finance/Insurance",
		"Tourism/Hotels" => "Tourism/Hotels",
		"Cars/Vehicles-Related" => "Cars/Vehicles-Related",
		"Cleaning/Other Help" => "Cleaning/Other Help",
		"Property-Related" => "Property-Related",
		"Medical/Health" => "Medical/Health",
		"Events/Usherers" => "Events/Usherers",
		"Others" => "Others",
		
	];
	public static function all()
	{
		// return array_values(static::$countries);	
		return static::$categories;	
	}
}