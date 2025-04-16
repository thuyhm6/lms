<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\Slide;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index() {
        $orders = Order::orderBy('created_at','DESC')->get()->take(10);
        $dashboardDatas = DB::selectOne("Select sum(total) as totalAmount,
                                        sum(if(status='ordered',total,0)) as totalOrderedAmount,
                                        sum(if(status='delivered',total,0)) as totalDeliveredAmount,
                                        sum(if(status='canceled',total,0)) as totalCanceledAmount,
                                        count(id) as totalOrders,
                                        sum(if(status='ordered',1,0)) as totalOrdered,
                                        sum(if(status='delivered',1,0)) as totalDelivered,
                                        sum(if(status='canceled',1,0)) as totalCanceled
                                        From orders
                                        ");
        $monthlyDatas = DB::select("SELECT M.id As MonthNo, M.name As MonthName,
                                        IFNULL(D.TotalAmount,0) As TotalAmount,
                                        IFNULL(D.TotalOrderedAmount,0) As TotalOrderedAmount,
                                        IFNULL(D.TotalDeliveredAmount,0) As TotalDeliveredAmount,
                                        IFNULL(D.TotalCanceledAmount,0) As TotalCanceledAmount
                                    FROM month_names M
                                    LEFT JOIN (
                                        Select DATE_FORMAT(created_at, '%b') As MonthName,
                                            MONTH(created_at) As MonthNo,
                                            sum(total) As TotalAmount,
                                            sum(if(status='ordered',total,0)) As TotalOrderedAmount,
                                            sum(if(status='delivered',total,0)) As TotalDeliveredAmount,
                                            sum(if(status='canceled',total,0)) As TotalCanceledAmount
                                        From Orders
                                        WHERE YEAR(created_at) = YEAR(NOW())
                                        GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                                        Order By MONTH(created_at)
                                    ) D On D.MonthNo = M.id
                                    ");
        $AmountM = implode(',', collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $TotalOrderedAmountM = implode(',', collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $TotalDeliveredAmountM = implode(',', collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $TotalCanceledAmountM = implode(',', collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

        $TotalAmount = collect($monthlyDatas)->sum('TotalAmount'); 
        $TotalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount'); 
        $TotalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount'); 
        $TotalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount'); 

        return view('admin.index', compact('orders', 'dashboardDatas', 'monthlyDatas',
                    'AmountM','TotalOrderedAmountM','TotalDeliveredAmountM','TotalCanceledAmountM','TotalAmount','TotalOrderedAmount','TotalDeliveredAmount','TotalCanceledAmount'));
    }

    public function users() {
        $users = User::orderBy('id', 'DESC')->paginate(12);
        return view('admin.users', compact('users'));
    }

    public function settings() {
        $user = User::find(Auth::user()->id)->first();
        return view('admin.settings', compact('user'));
    }

    public function changePassword(Request $request) {
        $request->validate([
            'old_password'=>'required',
            'new_password'=>'required|min:1|confirmed'
        ]);

        $user = Auth::user();
        if($request->new_password != $request->new_password_confirmation) {
            return redirect()->route('admin.settings')->with('error', 'Password is not match!');
        } else if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('admin.settings')->with('success', 'Password has been updated successfully!');
        } else {
            return redirect()->route('admin.settings')->with('error', 'Current password is incorrect!');
        }
    }

    //HMT  Chỗ này cần check
    public function brands() {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function addBrand() {
        return view('admin.brand-add');
    }

    public function storeBrand(Request $request) {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug',
            'image'=>'mimes:png,jpg,jpeg,gif|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $image = $request->file('image');
        $fileExtention = $request->file('image')->extension();
        $fileName = Carbon::now()->timestamp . '.'.$fileExtention;
        $this->generateThumbnailImage($image, $fileName, 'uploads/brands', 124, 124);
        $brand->image = $fileName;
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Record has been added successfully!');
    }

    public function editBrand($id) {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function updateBrand(Request $request) {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:brands,slug,'.$request->id, /* HMT lưu ý chỗ này */
            'image'=>'mimes:png,jpg,jpeg,gif|max:2048'
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/brands').'/'.$brand->image)) {
                File::delete(public_path('uploads/brands').'/'.$brand->image);
            }
            $image = $request->file('image');
            $fileExtention = $request->file('image')->extension();
            $fileName = Carbon::now()->timestamp . '.'.$fileExtention;
            $this->generateThumbnailImage($image, $fileName, 'uploads/brands', 124, 124);
            $brand->image = $fileName;
        }
        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Record has been updated successfully!');
    }

    public function deleteBrand($id) {
        $brand = Brand::find($id);
        if(File::exists(public_path('uploads/brands').'/'.$brand->image)) {
            File::delete(public_path('uploads/brands').'/'.$brand->image);
        }
        $brand->delete();
        return redirect()->route('admin.brands')->with('status', 'Brand has deleted successfully!');
    }

    //Categories
    public function categories() {
        $categories = Category::orderBy('id','DESC')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function addCategory() {
        return view('admin.category-add');
    }

    public function storeCategory(Request $request) {
        $request->validate([
            'name'=> 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:png,jpg,jpeg,gif|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $image = $request->file('image');
        $fileExtention = $request->file('image')->extension();
        $fileName = Carbon::now()->timestamp . '.'.$fileExtention;
        $this->generateThumbnailImage($image, $fileName, 'uploads/categories', 124, 124);
        $category->image = $fileName;
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Record has been added successfully!');
    }

    public function editCategory($id) {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function updateCategory(Request $request) {
        $request->validate([
            'name'=> 'required',
            'slug' => 'required|unique:categories,slug,'.$request->id,
            'image' => 'mimes:png,jpg,jpeg,gif|max:2048',
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->slug = Str::slug($request->name);
        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/categories').'/'.$category->image)) {
                File::delete(public_path('uploads/categories').'/'.$category->image);
            }
            $image = $request->file('image');
            $fileExtention = $request->file('image')->extension();
            $fileName = Carbon::now()->timestamp . '.'.$fileExtention;
            $this->generateThumbnailImage($image, $fileName, 'uploads/categories', 124, 124);
            $category->image = $fileName;
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Record has been updated successfully!');
    }

    public function deleteCategory($id) {
        $category = Category::find($id);
        if(File::exists(public_path('uploads/categories').'/'.$category->image)) {
            File::delete(public_path('uploads/categories').'/'.$category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category has deleted successfully!');
    }

    //Product
    public function products() {
        $products = Product::orderBy('id','DESC')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function addProduct() {
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }

    public function storeProduct(Request $request) {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug',
            'category_id'=>'required',
            'brand_id'=>'required',            
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/products').'/'.$product->image)) {
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)) {
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }

            $image = $request->file('image');
            $imageName = $current_timestamp.'.'.$image->extension();
            $this->generateProductThumbnailImage($image, $imageName, 'uploads/products', 'uploads/products/thumbnails/');
            $product->image = $imageName;
        }

        $galleryArr = [];
        $galleryImages = "";
        $counter = 1;

        if($request->hasFile('images')) {
            $oldGImage = explode(",", $product->images);
            foreach($oldGImage as $gimage) {
                if(File::exists(public_path('uploads/products').'/'.trim($gimage))) {
                    File::delete(public_path('uploads/products').'/'.trim($gimage));
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.trim($gimage))) {
                    File::delete(public_path('uploads/products/thumbnails').'/'.trim($gimage));
                }
            }
            $allowedFileExtensions = ['jpg', 'png', 'jpeg', 'gif'];
            $files = $request->file('images');
            foreach($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                if(in_array($gextension, $allowedFileExtensions)) {
                    $gfileName = $current_timestamp.'-'.$counter.'-'.$gextension;
                    $this->generateProductThumbnailImage($file, $gfileName, 'uploads/products', 'uploads/products/thumbnails/');
                    array_push($galleryArr, $gfileName);
                    $counter += 1;
                }
            }
            $galleryImages = implode(',', $galleryArr);
        }
        $product->images = $galleryImages;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been added successfully!');
    }

    public function viewProduct($id) {
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-view', compact('product', 'categories', 'brands'));
    }

    public function editProduct($id) {
        $product = Product::find($id);
        $categories = Category::select('id','name')->orderBy('name')->get();
        $brands = Brand::select('id','name')->orderBy('name')->get();
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function updateProduct(Request $request) {
        $request->validate([
            'name'=>'required',
            'slug'=>'required|unique:products,slug,'.$request->id,
            'category_id'=>'required',
            'brand_id'=>'required',            
            'short_description'=>'required',
            'description'=>'required',
            'regular_price'=>'required',
            'sale_price'=>'required',
            'SKU'=>'required',
            'stock_status'=>'required',
            'featured'=>'required',
            'quantity'=>'required',
            'image'=>'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $current_timestamp = Carbon::now()->timestamp;

        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/products').'/'.$product->image)) {
                File::delete(public_path('uploads/products').'/'.$product->image);
            }
            if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)) {
                File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
            }

            $image = $request->file('image');
            $imageName = $current_timestamp.'.'.$image->extension();
            $this->generateProductThumbnailImage($image, $imageName, 'uploads/products', 'uploads/products/thumbnails/');
            $product->image = $imageName;
        }

        $galleryArr = [];
        $galleryImages = "";
        $counter = 1;

        if($request->hasFile('images')) {
            $oldGImage = explode(",", $product->images);
            foreach($oldGImage as $gimage) {
                if(File::exists(public_path('uploads/products').'/'.trim($gimage))) {
                    File::delete(public_path('uploads/products').'/'.trim($gimage));
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.trim($gimage))) {
                    File::delete(public_path('uploads/products/thumbnails').'/'.trim($gimage));
                }
            }
            $allowedFileExtensions = ['jpg', 'png', 'jpeg', 'gif'];
            $files = $request->file('images');
            foreach($files as $file) {
                $gextension = $file->getClientOriginalExtension();
                if(in_array($gextension, $allowedFileExtensions)) {
                    $gfileName = $current_timestamp.'-'.$counter.'-'.$gextension;
                    $this->generateProductThumbnailImage($file, $gfileName, 'uploads/products', 'uploads/products/thumbnails/');
                    array_push($galleryArr, $gfileName);
                    $counter += 1;
                }
            }
            $galleryImages = implode(',', $galleryArr);
        }
        $product->images = $galleryImages;
        $product->save();
        return redirect()->route('admin.products')->with('status', 'Product has been updated successfully!');
    }

    public function deleteProduct($id) {
        $product = Product::find($id);
        if(File::exists(public_path('uploads/products').'/'.$product->image)) {
            File::delete(public_path('uploads/products').'/'.$product->image);
        }
        if(File::exists(public_path('uploads/products/thumbnails').'/'.$product->image)) {
            File::delete(public_path('uploads/products/thumbnails').'/'.$product->image);
        }

        $oldGImage = explode(",", $product->images);
            foreach($oldGImage as $gimage) {
                if(File::exists(public_path('uploads/products').'/'.trim($gimage))) {
                    File::delete(public_path('uploads/products').'/'.trim($gimage));
                }
                if(File::exists(public_path('uploads/products/thumbnails').'/'.trim($gimage))) {
                    File::delete(public_path('uploads/products/thumbnails').'/'.trim($gimage));
                }
            }

        $product->delete();
        return redirect()->route('admin.products')->with('status','Product has been deleted successfully!');
    }

    public function generateThumbnailImage($image, $imageName, $dir, $width, $height) {
        $destinationPath = public_path($dir);
        $img = Image::read($image->path());
        $img->cover($width,$height,'top');
        $img->resize($width,$height, function($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
    }
    public function generateProductThumbnailImage($image, $imageName, $dir, $dirThumbnail) {
        $destinationPathThumbnail = public_path($dirThumbnail);
        $destinationPath = public_path($dir);
        $img = Image::read($image->path());
        $img->cover(540,680,'top');
        $img->resize(540,680, function($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);

        $img->cover(124,124,'top');
        $img->resize(124,124, function($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);
    }

    public function coupons() {
        $coupons = Coupon::orderBy('expiry_date','DESC')->paginate(12);
        return view('admin.coupons', compact('coupons'));
    }

    public function addCoupon() {
        return view('admin.coupon-add');
    }

    public function storeCoupon(Request $request) {
        $request->validate([
            'code'=> 'required',
            'type'=> 'required',
            'value'=> 'required|numeric',
            'cart_value'=> 'required|numeric',
            'expiry_date'=> 'required|date'
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been added successfully!');
    }

    public function editCoupon($id) {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function updateCoupon(Request $request) {
        $request->validate([
            'code'=> 'required',
            'type'=> 'required',
            'value'=> 'required|numeric',
            'cart_value'=> 'required|numeric',
            'expiry_date'=> 'required|date'
        ]);

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been updated successfully!');
    }

    public function deleteCoupon($id) {
        $coupon  =Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'Coupon has been deleted successfully!');
    }

    public function orders() {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function orderDetails($orderId) {
        $order = Order::find($orderId);
        $orderItems = OrderItem::where('order_id', $orderId)->orderBy('id')->paginate(12);
        $transaction = Transaction::where('order_id', $orderId)->first();
        return view('admin.order-details', compact('order','orderItems','transaction'));
    }

    public function updateOrderStatus(Request $request) {
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;
        if ($request->order_status == 'delivered') {
            $order->delivered_date = Carbon::now();
        } else if ($request->order_status == 'canceled') {
            $order->canceled_date = Carbon::now();
        }
        $order->save();
        if ($request->order_status == 'delivered') {
            $transaction = Transaction::where('order_id','=',$request->order_id)->first();
            $transaction->status = 'approved';
            $transaction->save();
        }
        return back()->with('status', 'Status changed successfully!');
    }

    public function slides() {
        $slides = Slide::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.slides', compact('slides'));
    }

    public function addSlide() {
        return view('admin.slide-add');
    }

    public function storeSlide(Request $request) {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'status' =>'required',
            'link' => 'required',
            'image' =>'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->status = $request->status;
        $slide->link = $request->link;
        $image = $request->file('image');
        $fileExtention = $request->file('image')->extension();
        $fileName = Carbon::now()->timestamp.'.'.$fileExtention;
        
        $this->generateThumbnailImage($image, $fileName, 'uploads/slides', 690, 400);
        $slide->image = $fileName;
        $slide->save();
        return redirect()->route('admin.slides')->with('status', 'Slide has been added successfully!');
    }

    public function editSlide($id) {
        $slide = Slide::find($id);
        return view('admin.slide-edit', compact('slide'));
    }

    public function updateSlide(Request $request) {
        $request->validate([
            'tagline' =>'required',
            'title' =>'required',
           'subtitle' =>'required',
           'status' =>'required',
            'link' =>'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->status = $request->status;
        $slide->link = $request->link;
        if($request->hasFile('image')) {
            if(File::exists(public_path('uploads/slides').'/'.$slide->image)) {
                File::delete(public_path('uploads/slides').'/'.$slide->image);
            }
            $image = $request->file('image');
            $fileExtention = $request->file('image')->extension();
            $fileName = Carbon::now()->timestamp . '.'.$fileExtention;
            $this->generateThumbnailImage($image, $fileName, 'uploads/slides', 690, 400);
            $slide->image = $fileName;
        }
        $slide->save();
        return redirect()->route('admin.slides')->with('status', 'Slide has been updated successfully!');
    }

    public function deleteSlide($id) {
        $slide = Slide::find($id);
        if(File::exists(public_path('uploads/slides').'/'.$slide->image)) {
            File::delete(public_path('uploads/slides').'/'.$slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with('status', 'Slide has been deleted successfully!');
    }

    public function contacts() {
        $contacts = Contact::orderBy('created_at', 'DESC')->paginate(12);
        return view('admin.contacts', compact('contacts'));
    }
    public function deleteContact($id) {
        $contact = Contact::find($id);
        $contact->delete();
        return redirect()->route('admin.contacts')->with('status', 'Contact has been deleted successfully!');
    }

    public function search(Request $request) {
        $query = $request->input('query');
        $results =  Product::where('name','LIKE',"%{$query}%")->get()->take(8);
        return response()->json($results);
    }
    
}
