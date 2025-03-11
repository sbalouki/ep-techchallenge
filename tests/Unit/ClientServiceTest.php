<?php

namespace Tests\Unit;

use App\Client;
use App\Services\ClientService;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_an_array_of_clients_for_a_given_user()
    {
        $user = factory(User::class)->create();
        [$myFirstClient, $mySecondClient] = [
            factory(Client::class)->create(['user_id' => $user->id]),
            factory(Client::class)->create(['user_id' => $user->id])
        ];
        factory(Client::class)->create();

        $results = resolve(ClientService::class)->getClientsByUserId($user->id);

        $this->assertEquals([$myFirstClient->id, $mySecondClient->id], $results->pluck('id')->toArray());
    }
}
