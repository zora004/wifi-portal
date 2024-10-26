<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $voucher)
    {
        $check_voucher = Voucher::where('code', $voucher)->first();
        if(!$check_voucher){
            return response()->json([
                'title' => 'Not found',
                'message' => 'Voucher not found!'
            ], 404);
        }

        if($check_voucher->expiry < date('Y-m-d H:i:s', strtotime(now()))){
            return response()->json([
                'title' => 'Expired',
                'message' => 'Your voucher has expired.'
            ], 419);
        }

        if($check_voucher->status === 1){
            return response()->json([
                'title' => 'Used',
                'message' => 'Sorry, it seems that your voucher is already been used.'
            ], 409);
        }
        $check_voucher->status = 1;
        $check_voucher->save();

        return response()->json([
                'title' => 'Success',
                'message' => 'Congratulations! You are now connected to the internet.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
