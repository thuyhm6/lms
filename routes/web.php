<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'productDetails'])->name('shop.product.details');

//Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/increaseQuantity/{rowId}', [CartController::class, 'increaseItemQuantity'])->name('cart.increase.qty');
Route::put('/cart/reduceQuantity/{rowId}', [CartController::class, 'reduceItemQuantity'])->name('cart.reduce.qty');
Route::put('/cart/update-qty/{rowId}', [CartController::class, 'updateQty'])->name('cart.update.qty');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'removeCartItem'])->name('cart.remove');
Route::delete('/cart/clear/{rowId}', [CartController::class, 'clearCart'])->name('cart.clear');

//Mã giảm giá
Route::post('/cart/apply-coupon', [CartController::class, 'applyCouponCode'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon', [CartController::class, 'removeCouponCode'])->name('cart.coupon.remove');

Route::post('/wishlist/add',[WishlistController::class, 'addToWishlist'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
Route::delete('/wishlist/clear', [WishlistController::class, 'clearFromWishlist'])->name('wishlist.clear');
Route::post('/wishlist/moveToCart/{rowId}', [WishlistController::class, 'moveToCartFromWishlist'])->name('wishlist.move.to.cart');

//Checkout
Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'placeAnOrder'])->name('cart.place.an.order');
Route::get('/order-confirmation', [CartController::class, 'orderConfirmation'])->name('cart.order.contirmation');

//Contact
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact/send', [HomeController::class,'sendContactMessage'])->name('home.contact.send');

Route::get('/search', [HomeController::class, 'search'])->name('home.search');

//About
Route::get('/about', [HomeController::class, 'about'])->name('home.about');

//User Account
//HMT Chỗ này cần xem lại
Route::middleware(['auth'])->group(function() {
    Route::get('/account-dashboard',[UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.account.orders');
    Route::get('/account-order-details/{orderId}', [UserController::class, 'accountOrderDetails'])->name('user.account.orders.details');
    Route::put('account-order/cancel', [UserController::class, 'orderCancel'])->name('user.order.cancel');

    //Addresses
    Route::get('/account-addresses', [UserController::class, 'addresses'])->name('user.account.addresses');
    Route::get('/account-address/add', [UserController::class, 'addAddress'])->name('user.account.address.add');
    Route::post('/account-address/store', [UserController::class,'storeAddress'])->name('user.account.address.store');
    Route::get('/account-address/edit/{id}', [UserController::class, 'editAddress'])->name('user.account.address.edit');
    Route::put('/account-address/update', [UserController::class, 'updateAddress'])->name('user.account.address.update');
    Route::delete('/account-address/delete/{id}', [UserController::class, 'deleteAddress'])->name('user.account.address.delete');

    //Details
    Route::get('/account-details', [UserController::class, 'accountDetails'])->name('user.account.details');
    Route::put('/account-details/update', [UserController::class, 'updateAccountDetails'])->name('user.account.details.update');
    Route::get('/account-changePassword', [UserController::class, 'accountChangePassword'])->name('user.account.change.password');
    Route::put('/account-details/changePassword', [UserController::class, 'changePasswordUpdate'])->name('user.account.change.password.update');

    //Password
    Route::get('/account-password', [UserController::class, 'password'])->name('user.account.password');
    Route::put('/account-password/update', [UserController::class, 'updatePassword'])->name('user.account.password.update');
});
Route::middleware(['auth', AuthAdmin::class])->group(function() {
    Route::get('/admin',[AdminController::class, 'index'])->name('admin.index');

    //User
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/user/add', [AdminController::class, 'addUser'])->name('admin.user.add');
    Route::post('/admin/user/store', [AdminController::class,'storeUser'])->name('admin.user.store');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::put('/admin/user/update', [AdminController::class, 'updateUser'])->name('admin.user.update'); /* HMT Lưu �� cái này */
    Route::delete('/admin/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete'); /* HMT Lưu �� cái này */

    //Settings
    Route::get('/admin/settings', [AdminController::class,'settings'])->name('admin.settings');
    Route::put('/admin/setting/changePassword', [AdminController::class, 'changePassword'])->name('admin.setting.changePassword');

    //Products

    //Brands
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand/add', [AdminController::class, 'addBrand'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [AdminController::class,'storeBrand'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'editBrand'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [AdminController::class, 'updateBrand'])->name('admin.brand.update'); /* HMT Lưu ý cái này */
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'deleteBrand'])->name('admin.brand.delete'); /* HMT Lưu ý cái này */

    //Categories
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'addCategory'])->name('admin.category.add');
    Route::post('/admin/category/store', [AdminController::class,'storeCategory'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'editCategory'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class, 'updateCategory'])->name('admin.category.update'); /* HMT Lưu ý cái này */
    Route::delete('/admin/category/{id}/delete', [AdminController::class, 'deleteCategory'])->name('admin.category.delete'); /* HMT Lưu ý cái này */

    //Products
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'addProduct'])->name('admin.product.add');
    Route::post('/admin/product/store', [AdminController::class,'storeProduct'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.product.edit');
    Route::get('/admin/product/view/{id}', [AdminController::class, 'viewProduct'])->name('admin.product.view');
    Route::put('/admin/product/update', [AdminController::class, 'updateProduct'])->name('admin.product.update'); /* HMT Lưu ý cái này */
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.product.delete'); /* HMT Lưu ý cái này */

    //Orders
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/{id}', [AdminController::class, 'orderDetail'])->name('admin.order.detail');

    //Coupon
    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add', [AdminController::class, 'addCoupon'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [AdminController::class,'storeCoupon'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'editCoupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class, 'updateCoupon'])->name('admin.coupon.update'); /* HMT Lưu �� cái này */
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'deleteCoupon'])->name('admin.coupon.delete'); /* HMT Lưu �� cái này */

    //Orders
    Route::get('/admin/order', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/items/{orderId}', [AdminController::class, 'orderDetails'])->name('admin.order.items');
    Route::put('/admin/order/update', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status.update');

    //Slider
    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/add', [AdminController::class, 'addSlide'])->name('admin.slide.add');
    Route::post('/admin/slide/store', [AdminController::class,'storeSlide'])->name('admin.slide.store');
    Route::get('/admin/slide/edit/{id}', [AdminController::class, 'editSlide'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class, 'updateSlide'])->name('admin.slide.update'); /* HMT Lưu �� cái này */
    Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'deleteSlide'])->name('admin.slide.delete'); /* HMT Lưu �� cái này */

    //Contact
    Route::get('/admin/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::get('/admin/contact/{id}', [AdminController::class, 'viewContact'])->name('admin.contact.view');
    Route::delete('/admin/contact/delete/{id}', [AdminController::class, 'deleteContact'])->name('admin.contact.delete');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');

});
