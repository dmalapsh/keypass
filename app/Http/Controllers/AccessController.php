<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function index($client_id){
        $accesses = Access::where('client_id', $client_id)->get();
        $result = [];
        foreach($accesses as $access){
            array_push($result, $access->only('id', 'name'));
        }
        return response()->json($result);
    }
    public function show($client_id, $id) {
        $access = Access::where('client_id', $client_id)->find($id);

        $result = $access->only('id', 'client_id', 'sample_id', 'name', 'data');
        $result['data'] = json_decode($result['data']);

        return response()->json($result);
    }

    public function store(Request $request, $client_id) {
        $input = $request->all();

        $access = Access::create([
            'name' => $input['name'],
            'sample_id' => $input["sample_id"],
            'client_id' => $client_id,
            'data' => json_encode($input['data'])
        ]);

        $result = $access->only('id', 'client_id', 'sample_id', 'name', 'data');
        $result['data'] = json_decode($result['data']);

        return response()->json($result);
    }

    public function update(Request $request, $client_id, Access $access){
        $input = $request->all();
//        dd($id, $client_id);
//        $access = Access::find($id);

        dd($access);

        $access->sample_id = $input['sample_id'];
        $access->name = $input['name'];
        $access->data = json_encode($input['data']);
        $access->save();

        $result = $access->only('id', 'client_id', 'sample_id', 'name', 'data');
        $result['data'] = json_decode($result['data']);

        return response()->json($result);
    }
    public function destroy($client_id, $id){
        Access::destroy($id);
        return response()->json('ok');
    }

    public function compil ($client_id, $id) {
        $access = Access::where('client_id', $client_id)->find($id);
        $payloads = json_decode($access->data, true);
        $sample = $access->access_sample->data;

        foreach ($payloads as $key => $payload) {
            $regex =  '/'."%{$key}%".'/';
            $sample = preg_replace_callback($regex, function ($match) use ($key, $payload) {
                return "{$payload}";
            }, $sample);
        }

        $result = $access->only('id', 'client_id', 'sample_id', 'name', 'data');
        $result['data'] = json_decode($sample);

        return response()->json($result);
    }
}
