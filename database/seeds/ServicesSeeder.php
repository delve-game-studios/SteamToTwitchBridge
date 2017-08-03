<?php

use Illuminate\Database\Seeder;
use App\Service;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
        	'title' => 'Steam',
        	'slug' => 'service-steam'
    	]);

        Service::create([
        	'title' => 'Twitch',
        	'slug' => 'service-twitch'
    	]);

        Service::create([
        	'title' => 'Youtube',
        	'slug' => 'service-youtube'
    	]);

        Service::create([
        	'title' => 'Facebook',
        	'slug' => 'service-facebook'
    	]);

        Service::create([
        	'title' => 'Obs',
        	'slug' => 'service-obs'
    	]);
    }
}
