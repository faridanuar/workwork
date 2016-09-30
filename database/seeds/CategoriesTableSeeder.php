<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
        	[
	            'name' => 'Admin/Data Entry',
	            'label' => 'Admin/Data Entry',
            ],
            [
            	'name' => 'Sales',
	            'label' => 'Sales',
            ],
            [
            	'name' => 'Customer Svc/Call Centre',
	            'label' => 'Customer Svc/Call Centre',
            ],
            [
            	'name' => 'Marketing/PR/Designers',
	            'label' => 'Marketing/PR/Designers',
            ],
            [
            	'name' => 'Retail/Promoters',
	            'label' => 'Retail/Promoters',
            ],
            [
            	'name' => 'Accounting/Tax/Audit',
	            'label' => 'Accounting/Tax/Audit',
            ],
            [
            	'name' => 'Manufacturing',
	            'label' => 'Manufacturing',
            ],
            [
            	'name' => 'Engineering',
	            'label' => 'Engineering',
            ],
            [
                'name' => 'IT (Technology)',
                'label' => 'IT (Technology)',
            ],
            [
                'name' => 'Food/Restaurants/Cafe',
                'label' => 'Food/Restaurants/Cafe',
            ],
            [
                'name' => 'Education/Training',
                'label' => 'Education/Training',
            ],
            [
                'name' => 'Transport/Delivery',
                'label' => 'Transport/Delivery',
            ],
            [
                'name' => 'Construction',
                'label' => 'Construction',
            ],
            [
                'name' => 'Bank/Finance/Insurance',
                'label' => 'Bank/Finance/Insurance',
            ],
            [
                'name' => 'Tourism/Hotels',
                'label' => 'Tourism/Hotels',
            ],
            [
                'name' => 'Cars/Vehicles-Related',
                'label' => 'Cars/Vehicles-Related',
            ],
            [
                'name' => 'Cleaning/Other Help',
                'label' => 'Cleaning/Other Help',
            ],
            [
                'name' => 'Property-Related',
                'label' => 'Property-Related',
            ],
            [
                'name' => 'Medical/Health',
                'label' => 'Medical/Health',
            ],
            [
                'name' => 'Events/Usherers',
                'label' => 'Events/Usherers',
            ],
            [
                'name' => 'Others',
                'label' => 'Others',
            ],
        ]);
    }
}
