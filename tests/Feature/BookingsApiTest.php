<?php

namespace Tests\Feature;

use App\Booking;
use App\Client;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class BookingsApiTest  extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_destroys_a_users_related_booking_and_returns_a_204_status() {
        $user = factory(User::class)->create();

        $client = factory(Client::class)->create(['user_id' => $user->id]);
        $booking = factory(Booking::class)->create(['client_id' => $client->id]);
        $anotherBooking = factory(Booking::class)->create();

        $this->actingAs($user)->delete("/bookings/$booking->id")->assertStatus(Response::HTTP_NO_CONTENT);
        $this->actingAs($user)->delete("/bookings/$anotherBooking->id")->assertStatus(404);
        $this->actingAs($user)->delete("/bookings/-1")->assertStatus(404);
    }
}
