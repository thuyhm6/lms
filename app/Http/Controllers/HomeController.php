<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    
    public function index()
    {
        $slides = Slide::where('status','=',1)->get()->take(3);
        $categories = Category::orderBy('name')->get();
        $sproducts = Product::whereNotNull('sale_price')->where('sale_price','<>','')->inRandomOrder()->get()->take(8);
        $fproducts = Product::where('featured',1)->get()->take(8);
        return view('index', compact('slides','categories','sproducts','fproducts'));
    }

    public function contact(Request $request) {
        return view('contact');
    }

    public function sendContactMessage(Request $request) {
        $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:100',
            'phone' =>'required|numeric|digits:10',
            'comment' =>'required|string|max:2000',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;
        $contact->save();

        // Send email notification

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    public function search(Request $request) {
        $query = $request->input('query');
        $results =  Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }

    public function about() {
        return view('about');
    }
}
