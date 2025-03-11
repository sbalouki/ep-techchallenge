<?php

namespace App\Http\Controllers;

use App\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index(ClientService $clientService)
    {
        $clients = $clientService->getClientsByUserId(auth()->id());

        foreach ($clients as $client) {
            $client->append('bookings_count'); // TODO : It causes a request on each client. To refactor
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show($clientId)
    {
        $client = Client::with('bookings')->find($clientId);

        $this->authorize('view', $client);

        return view('clients.show', ['client' => $client]);
    }

    public function store(Request $request)
    {
        $client = new Client;
        $client->name = $request->get('name');
        $client->email = $request->get('email');
        $client->phone = $request->get('phone');
        $client->address = $request->get('address');
        $client->city = $request->get('city');
        $client->postcode = $request->get('postcode');
        $client->save();

        return $client;
    }

    public function destroy($client)
    {
        Client::destroy($client);
    }
}
