<?php

use Illuminate\Database\Seeder;
use STS\Entities\Trip;
use STS\Entities\TripPoint;

class TripsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $t1 = factory(Trip::class)->create();
        $t1->points()->save(factory(TripPoint::class, 'rosario')->make());
        $t1->points()->save(factory(TripPoint::class, 'mendoza')->make());
    }
}
