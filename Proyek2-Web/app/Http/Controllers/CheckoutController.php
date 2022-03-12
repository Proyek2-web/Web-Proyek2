<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Payment\TripayController;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tripay = new TripayController();
        $channels = $tripay->getPaymentChannels();
        $subtotal = $request->sub_total;
        $user_id = $request->user_id;
        $provinces = Province::pluck('name', 'province_id');
        $cart_count = Cart::all()->where('user_id', '=', $user_id == null ? '' : $user_id)->where('status', '=', "pending")->count();
        $data = DB::table('carts')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('carts.user_id', '=', $user_id == null ? '' : $user_id)
            ->where('carts.status', '=', 'pending')
            ->select('carts.id as id', 'carts.qty as qty', 'products.id as product_id', 'products.nama as nama',
             'products.featured_image as featured_image', 'products.harga as harga', 'categories.name as category_name','carts.status as status')
            ->get();
        return view('layouts.form-order',compact('subtotal','provinces', 'cart_count','data','channels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}