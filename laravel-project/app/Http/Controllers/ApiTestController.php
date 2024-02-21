<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestRequest;
use Illuminate\Support\Facades\DB;

class ApiTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::all();
        return response()->json([
            'status' => true,
            'tests' => $tests,
            'aa' => 'tester'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        return response()->json([
            'status' => true,
            'message' => "Test data retrieved successfully!",
            'test' => $test
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();
            foreach ($data as $item) {
                Log::info($item);
                Test::create($item);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Products created successfully!",
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Error: " . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test)
    {
        $test->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Test updated successfully!',
            'test' => $test
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        $test->delete();
        return response()->json([
            'status' => true,
            'message' => "Test data destroy successfully!",
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     * 不要
     */
    public function create(){}


    /**
     * Show the form for editing the specified resource.
     * 不要
     */
    public function edit(Test $test){}

}
