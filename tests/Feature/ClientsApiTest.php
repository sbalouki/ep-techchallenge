<?php

namespace Tests\Feature;

use App\Actions\StoreClient;
use Illuminate\Support\Str;
use App\Client;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientsApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function user_can_view_only_their_clients() {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['user_id' => $user->id]);
        $otherClient = factory(Client::class)->create();

        $this->actingAs($user)->get("/clients/$client->id")->assertStatus(200);
        $this->actingAs($user)->get("/clients/$otherClient->id")->assertStatus(404);
    }

    /** @test */
    public function it_destroys_a_users_client_and_returns_a_204_status() {
        $user = factory(User::class)->create();
        $myClient = factory(Client::class)->create(['user_id' => $user->id]);
        $client =  factory(Client::class)->create();

        $this->actingAs($user)->delete("/clients/$myClient->id")->assertStatus(204);
        $this->actingAs($user)->delete("/clients/$client->id")->assertStatus(404);

        $this->assertNull(Client::find($myClient->id));
    }

    /** @test */
    public function it_fails_trying_to_delete_a_non_existing_client() {
        $user = factory(User::class)->create();

        $this->actingAs($user)->delete("/clients/-1")->assertStatus(404);
    }

    /** @test */
    public function it_creates_a_client_with_a_default_user_id() {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->make();

        $this->mock(StoreClient::class, function ($mock) use ($client, $user) {
            $mock->shouldReceive('execute')
                ->once()
                ->with(
                    $user->id,
                    $client->name, 
                    $client->email,
                    $client->phone,
                    $client->address,
                    $client->city,
                    $client->postcode
                )
                ->andReturn($client);
        });

        $this->actingAs($user)->post('clients', $client->toArray())->assertCreated();
    }

    /**
     * @test
     */
    public function it_validates_the_clients_name_before_creation() {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['name' => Str::random(200)]))->assertRedirect();
        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['name' => null]))->assertRedirect();

        $this->assertEquals(Client::count(), 0);

        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['name' => 'Anakin']))->assertCreated();

    }
    /**
     * @test
     */
    public function it_validates_the_clients_email_before_creation() {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['email' => null]))->assertRedirect();
        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['email' => 'wrong']))->assertRedirect();

        $this->assertEquals(Client::count(), 0);

        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['email' => 'sbalouki33@gmail.com']))->assertCreated();
    }
    /**
     * @test
     */
    public function it_validates_the_clients_phone_before_creation() {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['phone' => null]))->assertRedirect();
        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['phone' => 'or not']))->assertRedirect();

        $this->assertEquals(Client::count(), 0);

        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['phone' => '+3396556655']))->assertCreated();
        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['phone' => '01234567891']))->assertCreated();
        $this->actingAs($user)->post('clients', factory(Client::class)->raw(['phone' => '05 96 55 66 55']))->assertCreated();
    }
}
