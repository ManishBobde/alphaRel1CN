<?php

use App\CN\CNEvents\Event;
use App\CN\CNEvents\Events;
use Faker\Factory as Faker;

class EventsTableSeeder extends \Illuminate\Database\Seeder {
	
	/**
	 * Fills the salon users table with fake data
	 */
	public function run() {
		$faker = Faker::create ();
		//foreach ( range ( 1, 10 ) as $index ) {
			Events::create ( [
					'eventId' => 1,
					'eventTitle' => 'Event title',
					'eventDesc' => 'Event description',
					'eventDate' => $faker->dateTime,
					'eventTime' => $faker->dateTime,
					'userId' => 1,
					'updated_at' => $faker->dateTime,
					'created_at' => $faker->dateTime
			] );
		}
	//}
}