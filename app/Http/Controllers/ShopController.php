<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request) {
        $size = $request->query('size') ? $request->query('size') : 12;
        $oColumn = "";
        $oOrder = "";
        $order = $request->query('order') ? $request->query('order') : -1;
        $f_brands = $request->query("brands");
        $f_categories = $request->query("categories");
        $minPrice = $request->query('min') ? $request->query('min') : 1;
        $maxPrice = $request->query('max') ? $request->query('max') : 500;
        switch ($order) {
            case 1:
                $oColumn = 'created_at';
                $oOrder = 'DESC';
                break;
            case 2:
                $oColumn = 'created_at';
                $oOrder = 'ASC';
                break;
            case 3:
                $oColumn = 'regular_price';
                $oOrder = 'ASC';
                break;
            case 4:
                $oColumn = 'regular_price';
                $oOrder = 'DESC';
                break;
            default:
                $oColumn = 'id';
                $oOrder = 'DESC';
                
        }
        $products = Product::where(function($query) use($f_brands){
            $query->whereIn('brand_id', explode(',', $f_brands))->orWhereRaw("'".$f_brands."'=''");
        })
        ->where(function($query) use($f_categories){
            $query->whereIn('category_id', explode(',', $f_categories))->orWhereRaw("'".$f_categories."'=''");
        })
        ->where(function($query) use($minPrice, $maxPrice) {
            $query->whereBetween('regular_price',[$minPrice, $maxPrice])->orWhereBetween('sale_price',[$minPrice, $maxPrice]);
        })
        ->orderBy($oColumn,$oOrder)->paginate($size);
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        return view('shop', compact('products', 'size','order','brands', 'f_brands','categories','f_categories','minPrice','maxPrice'));
    }

    public function productDetails($product_slug) {
        $product = Product::where('slug', $product_slug)->first();
        $rproducts = Product::where('slug','<>',$product_slug)->get()->take(8);
        return view('details', compact('product', 'rproducts'));
    }
}
