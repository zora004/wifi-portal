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
        return view('contents.dashboard');
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
                'title' => 'Invalid Voucher',
                'message' => 'The voucher code you entered is invalid. Please double-check your code.'
            ], 404);
        }

        if($check_voucher->expiry < date('Y-m-d H:i:s', strtotime(now()))){
            return response()->json([
                'title' => 'Expired Voucher',
                'message' => 'Oops! It looks like your voucher has expired.'
            ], 419);
        }

        if($check_voucher->status === 1){
            return response()->json([
                'title' => 'Voucher Status Update',
                'message' => 'Unfortunately, your voucher has already been used. Please check your voucher details.'
            ], 409);
        }
        $check_voucher->status = 1;
        $check_voucher->save();

        return response()->json([
                'title' => 'Success',
                'message' => 'Congratulations! You are now successfully connected to the internet. Enjoy your shuttle browsing experience!'
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
