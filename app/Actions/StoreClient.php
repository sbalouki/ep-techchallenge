<?php

namespace App\Actions;

use App\Client;

class StoreClient {
    public function execute($userId, string $name, string $email, string $phone, string $address, string $city, string $postcode) {
        $client = new Client();
        $client->name = $name;
        $client->email = $email;
        $client->phone = $phone;
        $client->address = $address;
        $client->city = $city;
        $client->postcode = $postcode;
        $client->user_id = $userId;
        $client->save();

        return $client;
    }
}
