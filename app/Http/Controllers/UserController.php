<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\Transactions;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if($user->utype === 'STUDENT') {
            $student = Student::where('user_id', $user->id)->first();
            $transactions = Transactions::latest()
            ->with(['student', 'coursePackage', 'transactionDetails'])
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->where('student_id', $student->user_id)
            ->get();
            return view('user.index', compact('student', 'transactions'));
        }else if($user->utype === 'PARENT') {
            $student = Student::where('parent_id', $user->id)->first();
            $transactions = Transactions::latest()
            ->with(['student', 'coursePackage', 'transactionDetails'])
            ->withSum('transactionDetails as amount_paid', 'amount_paid')
            ->where('student_id', $student->user_id)
            ->get();
            return view('user.index', compact('student', 'transactions'));
        }
         // $transactions = Transactions::where('user_id', Auth::user()->id)->get();
         // dd($user);
        // dd($transactions);
         return view('user.index');
        
    }

    public function orders()
    {
        $orders = Order::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(10);
        return view('user.orders', compact('orders'));
    }

    public function accountOrderDetails($orderId)
    {
        $order = Order::where('user_id', '=', Auth::user()->id)->find($orderId);
        if ($order) {
            $orderItems = OrderItem::where('order_id', '=', $orderId)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', '=', $orderId)->first();
            return view('user.order-details', compact('order', 'transaction', 'orderItems'));
        } else {
            return redirect()->route('login');
        }
    }

    public function orderCancel(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 'canceled';
        $order->canceled_date = Carbon::now();
        $order->save();
        return back()->with('status', 'Order has been canceled successfully');
    }

    public function addresses()
    {
        $user_id = Auth::user()->id;
        $addresses = Address::where('user_id', '=', $user_id)->get();
        return view('user.addresses', compact('addresses'));
    }

    public function addAddress()
    {
        return view('user.address-add');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'phone' => 'required|numeric|digits:10',
            'zip' => 'required|numeric|digits:6',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'locality' => 'required',
            'landmark' => 'required'
        ]);
        $address = new Address;
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->zip = $request->zip;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->address = $request->address;
        $address->locality = $request->locality;
        $address->landmark = $request->landmark;
        $address->isdefault = $request->isdefault ?? '0';
        $address->country = 'Vietnam';
        $address->save();
        return redirect()->route('user.account.addresses')->with('status', 'Address added successfully');
    }

    public function editAddress($id)
    {
        $address = Address::find($id);
        return view('user.address-edit', compact('address'));
    }

    public function deleteAddress($id)
    {
        $address = Address::find($id);
        $address->delete();
        //return redirect()->route('user.account.addresses')->with('status', 'Address deleted successfully');
        return response()->json(['success' => 'Address deleted successfully']);
    }

    public function accountDetails()
    {
        $user = Auth::user();
        return view('user.account-details', compact('user'));
    }

    public function accountChangePassword()
    {
        $user = Auth::user();
        return view('user.account-changePassword', compact('user'));
    }

    public function updateAccountDetails(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'full_name' => 'required',
            'mobile' => 'required|max:10',
            'email' => 'required|email|unique',
            'email' => 'required|email|unique:users,email,' . $user->id,

        ]);
        $user->full_name = $request->full_name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->save();
        return back()->with('status', 'Cập nhật tài khoản thành công!');
    }

    public function changePasswordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:1|confirmed'
        ]);

        $user = Auth::user();
        if ($request->new_password != $request->new_password_confirmation) {
            return redirect()->route('user.account.change.password')->with('error', 'Mật khẩu mới không khớp!');
        } else if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('user.account.change.password')->with('success', 'Mật khẩu đã được cập nhật thành công!');
        } else {
            return redirect()->route('user.account.change.password')->with('error', 'Mật khẩu hiện tại không chính xác!');
        }
    }
}
