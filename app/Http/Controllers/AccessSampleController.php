<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Validator;
use App\Models\AccessSample;
use App\Models\AccessType;
use Illuminate\Http\Request;

class AccessSampleController extends Controller
{
    public function index(Request $request)
    {
        $samples = AccessSample::when($request->client_id,
            function ($query) use ($request) {
                $query->where('client_id', $request->client_id);
        })
            ->get();
        return response()->json($samples);
    }

    public function show($id)
    {
        $sample = AccessSample::find($id);
        return response()->json($sample);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $data = $input['data'];
        $type = AccessType::find($input['type_id']);
        $struct = json_decode($type->data, true);
//        $result = (new Validator)->run($struct, $data, $input['name']);

//        if (!($result['valid'] === 1)) {
//            return response()->json([
//                "Don't compolete valid!",
//                $result
//            ]);
//        }

        $sample = AccessSample::create([
            'name' => $input['name'],
            'type_id' => $input['type_id'],
            'data' => $input['data']
        ]);

        $result = $sample->only('id', 'type_id', 'name', 'data');
        $result['data'] = json_decode($result['data']);

        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $sample = AccessSample::find($id);

        $sample->name = $input['name'];
        $sample->data = $input['data'];
        $sample->save();

        $result = $sample->only('id', 'type_id', 'name', 'data');
        $result['data'] = json_decode($result['data']);

        return response()->json($result);
    }

    public function destroy($id)
    {
        $sample = AccessSample::find($id);
        return response()->json('ok');
    }

    public function valid($id)
    {
        $sample = AccessSample::find($id);
        $data = json_decode($sample->data, true);
        $struct = json_decode($sample->access_type->data, true);
        $result = (new Validator)->run($struct, $data, $sample->name);
        return response()->json($result);
    }
}
