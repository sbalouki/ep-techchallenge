<?php

namespace Tests\Feature;

use App\Client;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cannot_view_other_users_clients() {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['user_id' => $user->id]);
        $otherClient = factory(Client::class)->create();

        $this->actingAs($user)->get("/clients/$client->id")->assertStatus(200);
        $this->actingAs($user)->get("/clients/$otherClient->id")->assertStatus(404);
    }

    /** @test */
    public function it_destroys_a_client_and_returns_a_204_status() {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create();

        $this->actingAs($user)->delete("/clients/$client->id")->assertStatus(200);
        $this->assertNull(Client::find($client->id));
    }
}
