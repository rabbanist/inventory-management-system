<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
       try{
              $user_id = $request->header('user_id');
              $customers = Customer::where('user_id', $user_id)->get();
                if ($customers->isEmpty()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No customers found',
                    ], 404);
                }
              return response()->json([
                'status' => 'success',
                'customers' => $customers,
              ]);
            }catch (\Throwable $th){
           return response()->json([
               'status' => 'error',
               'message' => 'Customer not found',
           ], 404);
       }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email|max:255',
            'mobile' => 'required|string|max:15',
        ]);
        try{
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $request->header('user_id'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'customer' => $customer,
            ], 201);
        }catch(\Throwable $th){
            return response()->json([
                'status' => 'error',
                'message' => 'Customer failed to create',
            ], 404);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : JsonResponse
    {
        try{
            $user_id = request()->header('user_id');
            $customer = Customer::where('user_id', $user_id)->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'customer' => $customer,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$id.'|max:255',
            'mobile' => 'required|string|max:15',
        ]);
        try{
            $user_id = $request->header('user_id');
            $customer = Customer::where('user_id', $user_id)->findOrFail($id);
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_id' => $request->header('user_id'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer updated successfully',
                'customer' => $customer,
            ]);
        }catch (\Throwable $th){
            return response()->json([
                'status' => 'error',
                'message' => 'Customer failed to update',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request , string $id) : JsonResponse
    {
        try{
            $user_id = $request->header('user_id');
            $customer = Customer::where('user_id', $user_id)->findOrFail($id);
            $customer->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Customer deleted successfully',
            ]);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found',
            ], 404);
        }

    }
}
