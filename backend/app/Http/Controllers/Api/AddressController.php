<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Services\AccountAddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(private AccountAddressService $accountAddressService){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $addresses = $this->accountAddressService->getAddressList($request->user());
        return response()->json([
            'success' => true,
            'message' => 'Address List',
            'data' => $addresses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|in:Home,Work,Parents,Other',
            'receiver_name' => 'required|string',
            'company' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'required|boolean',
        ]);
        $this->accountAddressService->createAddress($request->user(), $validated);
        return response()->json([
            'success' => true,
            'message' => 'Address Created successfully',
        ]);
    }
    public function setDefault(Request $request,Address $address){
        $result = $this->accountAddressService->setDefault($request->user(), $address);
        if(!$result){
            return response()->json([
                'success' => true,
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Address Set to default',
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Address $address)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|in:Home,Work,Parents,Other',
            'receiver_name' => 'required|string',
            'company' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'country' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'nullable|string|max:20',
            'is_default' => 'required|boolean',
        ]);
        $this->accountAddressService->updateAddress($request->user(),$address, $validated);
        return response()->json([
            'success' => true,
            'message' => 'Address Updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Address $address)
    {
        $this->accountAddressService->deleteAddress($request->user(), $address);
        return response()->json([
            'success' => true,
            'message' => 'Address Deleted successfully'
        ]);
    }
}
