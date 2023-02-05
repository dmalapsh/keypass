<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(){
        $clients = Client::all();
        return response()->json($clients);
    }
    public function show(Client $client) {
        return response()->json($client);
    }

    public function store(Request $request) {
        $input = $request->all();
        $client = Client::create($input);
        return response()->json($client);
    }

    public function update(Request $request, Client $client){
        $input = $request->all();
        $client->update($input);
        return response()->json($client);
    }
    public function destroy($id){
        Client::destroy($id);
        return response()->json('ok');
    }

}
