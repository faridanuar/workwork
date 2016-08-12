<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
<<<<<<< HEAD
    	$this->call(AdvertsTableSeeder::class);
=======
        $this->call(AdvertsTableSeeder::class);
>>>>>>> 0723d62534a431b67953c72df6c8fed30d433fc9
        $this->call(RolesTableSeeder::class);
    }
}
