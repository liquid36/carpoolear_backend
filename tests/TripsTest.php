<?php
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \STS\Contracts\Logic\User as UserLogic;
use \STS\Contracts\Logic\Trip as TripsLogic;
use Carbon\Carbon;

class TripsTest extends TestCase { 
    use DatabaseTransactions;
 
 	public function testCreateTrip()
	{
        $user = factory(STS\User::class)->create();
        $tripManager = \App::make('\STS\Contracts\Logic\Trip');

        $data = [
            'is_passenger'          => 0,
            'from_town'             => 'Rosario, Santa Fe, Argentina',
            'to_town'               => 'Santa Fe, Santa Fe, Argentina',
            'trip_date'             => Carbon::now(),
            'total_seats'           => 5,
            'friendship_type_id'    => 0,
            'estimated_time'        => '05:00',
            'distance'              => 365,
            'co2'                   => 50,
            'description'           => 'hola mundo',
            'points' => [
                [
                    'address' => 'Rosario, Santa Fe, Argentina',
                    'json_address' => ['street' => 'Pampa'],
                    'lat' => 0,
                    'lng' => 0
                ] , [
                    'address' => 'Santa Fe, Santa Fe, Argentina',
                    'json_address' => ['street' => 'Pampa'],
                    'lat' => 0,
                    'lng' => 0
                ]
            ]
        ];

        $u = $tripManager->create($user, $data);
        $this->assertTrue($u != null); 
	}

    public function testUpdateTrip()
    {
        $tripManager = \App::make('\STS\Contracts\Logic\Trip');
        $trip = factory(STS\Entities\Trip::class)->create(); 
        $from = $trip->from_town;
        $trip = $tripManager->update($trip->user, $trip->id, ['from_town' => "Usuahia"]);
        $this->assertTrue($trip->from_town != $from);
         
    }

    public function testDeleteTrip()
    {
        $tripManager = \App::make('\STS\Contracts\Logic\Trip');
        $trip = factory(STS\Entities\Trip::class)->create();

        $from = $trip->from_town;
        $result = $tripManager->delete($trip->user, $trip->id);
        $this->assertTrue($result);
    }

    public function testShowTrip()
    {
        $tripManager = \App::make('\STS\Contracts\Logic\Trip');
        $trip = factory(STS\Entities\Trip::class)->create();

        $result = $tripManager->show($trip->user, $trip->id);
        $this->assertTrue($result != null);
    }

}
