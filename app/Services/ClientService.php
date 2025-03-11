<?php

namespace App\Services;

use App\Client;

class ClientService {
    public function getClientsByUserId(int $userId) {
        return Client::whereUserId($userId)->get();
    }
}