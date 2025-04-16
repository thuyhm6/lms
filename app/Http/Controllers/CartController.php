<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Coupon;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index() {
        $cartItems = Cart::instance('cart')->content();
        return view('cart', compact('cartItems'));
    }

    public function addToCart(Request $request) {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increaseItemQuantity($rowId) {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function updateQty($rowId, Request $request)
    {
        $quantity = $request->input('quantity');
        Cart::instance('cart')->update($rowId, $quantity);

        $cartItem = Cart::instance('cart')->get($rowId);
        $subtotal = $cartItem->subtotal();

        return response()->json([
            'success' => true,
            'subtotal' => $subtotal
        ]);
    }

    public function reduceItemQuantity($rowId) {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function removeCartItem($rowId) {
        Cart::instance('cart')->remove($rowId);
        return response()->json(['success' => 'Cart Item removed successfully']);
    }

    public function clearCart() {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    //Mã giảm giá
    public function applyCouponCode(Request $request) {
        $couponCode = $request->coupon_code;
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->where('expiry_date','>=',Carbon::today())->where('cart_value','<=', Cart::instance('cart')->subtotal())->first();
            if (!$coupon) {
            return redirect()->back()->with('error', 'Invalid coupon code');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success', 'Coupon has been applied successfully');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid coupon code');
        }
    }

    public function calculateDiscount() {
        $discount = 0;
        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = Cart::instance('cart')->subtotal() * Session::get('coupon')['value'] / 100;
            }

            $subtotalAferDiscount = Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount = ($subtotalAferDiscount * config('cart.tax')) / 100;
            $totalAfterDiscount = $subtotalAferDiscount + $taxAfterDiscount;

            Session::put('discounts', [
                'discount' => number_format(floatval($discount),2,'.',','),
                'subtotal' => number_format(floatval($subtotalAferDiscount),2,'.',','),
                'tax' => number_format(floatval($taxAfterDiscount),2,'.',','),
                'total' => number_format(floatval($totalAfterDiscount),2,'.',','),
            ]);
        }
    }

    public function removeCouponCode() {
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back();
    }

    public function checkout() {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $address = Address::where('user_id', Auth::user()->id)->where('isdefault',1)->first();
        return view('checkout',compact('address'));
    }

    public function placeAnOrder(Request $request) {
        $userId = Auth::user()->id;
        $address = Address::where('user_id',$userId)->where('isdefault', true)->first();
        if (!$address) {
            $request->validate([
                'name'=> 'required|max:100',
                'phone'=> 'required|numeric|digits:10',
                'zip'=> 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required'
            ]);
            $address = new Address;
            $address->user_id = $userId;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->isdefault = true;
            $address->country = 'Vietnam';
            $address->save();
        }

        $this->setAmountForCheckout();

        $order = new Order();
        $order->user_id = $userId;
        $order->subtotal = floatval(str_replace(',', '', session()->get('checkout')['subtotal']));
        $order->discount = session()->get('checkout')['discount'];
        $order->tax = session()->get('checkout')['tax'];
        $order->total = floatval(str_replace(',', '', session()->get('checkout')['total']));
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->zip = $address->zip;
        $order->state = $address->state;
        $order->city = $address->city;
        $order->address = $address->address;
        $order->locality = $address->locality;
        $order->landmark = $address->landmark;
        $order->country = $address->country;
        $order->save();

        foreach(Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->save();
        }

        if ($request->mode == "card") {

        } else if ($request->mode == "paypal") {

        }
        else if ($request->mode == "cod") {
            $transaction = new Transaction();
            $transaction->user_id = $userId;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('discounts');
        Session::forget('coupon');
        Session::put('order_id', $order->id);
        return redirect()->route('cart.order.contirmation');
    }

    public function setAmountForCheckout() {
        if(!Cart::instance('cart')->count() > 0) {
            session()->forget('checkout');
            return;
        }

        if (session()->has('coupon')) {
            session()->put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total'],
            ]);
        } else {
            session()->put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }
    }

    public function orderConfirmation() {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            $transaction = Transaction::where('order_id', '=', Session::get('order_id'))->first();
            return view('order-confirmation', compact('order', 'transaction'));
        }
        return redirect()->route('cart.index');

    }
}
