<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use App\Client;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ClientsApiTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_cannot_view_other_users_clients() {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->create(['user_id' => $user->id]);
        $otherClient = factory(Client::class)->create();

        $this->actingAs($user)->get("/clients/$client->id")->assertStatus(200);
        $this->actingAs($user)->get("/clients/$otherClient->id")->assertStatus(404);
    }

    /** @test */
    public function it_has_a_default_user_id() {
        $user = factory(User::class)->create();
        $client = factory(Client::class)->raw();

        $this->actingAs($user)->post('clients', $client)->assertCreated();

        $this->assertEquals(Client::first()->user_id, auth()->id());
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

    /** @test */
    public function it_destroys_a_users_client_and_returns_a_204_status() {
        $user = factory(User::class)->create();
        $myClient = factory(Client::class)->create(['user_id' => $user->id]);
        $client =  factory(Client::class)->create();

        $this->actingAs($user)->delete("/clients/$myClient->id")->assertStatus(200);
        $this->actingAs($user)->delete("/clients/$client->id")->assertStatus(404);

        $this->assertNull(Client::find($myClient->id));
    }
}
