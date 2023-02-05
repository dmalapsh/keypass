<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function index(Request $request){
        $accessesQuery = Access::query();
        if ($request->clint_id){
            $accessesQuery->where('client_id', $request->clint_id);
        }

        $accessesQuery->when($request->clint_id, function ($query) use ($request) {
            $query->where('client_id', $request->clint_id);
        });

        return response()->json($accessesQuery->get());
    }
    public function show($id) {
        $access = Access::find($id);
        return response()->json($access);
    }

    public function store(Request $request, $client_id) {
        $input = $request->all();
        $result = Access::create($input);
        return response()->json($result);
    }

    public function update(Request $request, Access $access){
        $input = $request->all();

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
            $regex =  "/%{$key}%/";
            $sample = preg_replace_callback($regex, function ($match) use ($key, $payload) {
                return "{$payload}";
            }, $sample);
        }

        $result = $access->only('id', 'client_id', 'sample_id', 'name', 'data');
        $result['data'] = json_decode($sample);

        return response()->json($result);
    }
}
