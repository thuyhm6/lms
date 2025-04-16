<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishlistController extends Controller
{
    public function index() {
        $productItems = Cart::instance('wishlist')->content();
        return view('wishlist', compact('productItems'));
    }
    public function addToWishlist(Request $request) {
        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function removeFromWishlist($rowId) {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }

    public function clearFromWishlist() {
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }

    public function moveToCartFromWishlist($rowId){
        $productItem = Cart::instance('wishlist')->get($rowId);
        Cart::instance('wishlist')->remove($rowId);
        Cart::instance('cart')->add($productItem->id, $productItem->name, 1, $productItem->price)->associate('App\Models\Product');
        return redirect()->back();
    }
}
