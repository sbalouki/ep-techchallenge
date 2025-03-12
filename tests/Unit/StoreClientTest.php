<?php

namespace Tests\Unit;

use App\Actions\StoreClient;
use App\Client;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreClientTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_new_client() {
        $clientData = factory(Client::class)->raw();
        $user = factory(User::class)->create();

        $this->assertInstanceOf(Client::class, resolve(StoreClient::class)
            ->execute(
                $user->id,
                $clientData['name'],
                $clientData['email'],
                $clientData['phone'],
                $clientData['address'],
                $clientData['city'],
                $clientData['postcode']
            ));

        $this->assertEquals(Client::count(), 1);
        $this->assertEquals(Client::first()->name, $clientData['name']);
    }
}
