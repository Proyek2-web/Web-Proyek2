<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('carts')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('carts.user_id', '=', Auth::user() == null ? '' : Auth::user()->id)
            ->where('carts.status', '=', 'pending')
            ->select(
                'carts.id as id',
                'carts.qty as qty',
                'products.id as product_id',
                'products.nama as nama',
                'products.featured_image as featured_image',
                'products.harga as harga',
                'categories.name as category_name'
            )
            ->get();
        $cart_count = Cart::all()->where('user_id', '=', Auth::user() == null ? '' : Auth::user()->id)->where('status', '=', 'pending')->count();
        $sub_total = 0;
        foreach ($data as $d) {
            $sub_total += $d->qty * $d->harga;
        }
        return view('user.cart', compact('data', 'cart_count', 'sub_total'));
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
        $cart = new Cart();
        $cart->user_id = $request->user_id;
        $cart->product_id = $request->product_id;
        $cart->status = $request->status;
        $cart->qty = $request->quantity;
        $cart->save();
        Alert::success('Berhasil Ditambahkan', '');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {

        Cart::destroy($cart->id);
        $cart_count = Cart::all()
            ->where('user_id', '=', Auth::user() == null ? '' : Auth::user()->id)
            ->where('status', '=', 'pending')
            ->count();
        if($cart_count < 1){
            Alert::error('Keranjang Kosong');
            return redirect('/produk');
        }
        return redirect()->back();
    }
}