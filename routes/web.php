<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\coursesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\lessonsController;
use App\Http\Controllers\newsController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ScheduleDetailController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\subjectsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\topicController;
use App\Http\Controllers\transactionDetailsController;
use App\Http\Controllers\transactionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Middleware\AuthParent;
use App\Http\Middleware\AuthStudent;
use App\Http\Middleware\AuthTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');


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

Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist'])->name('wishlist.add');
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
Route::post('/contact/send', [HomeController::class, 'sendContactMessage'])->name('home.contact.send');

Route::get('/search', [HomeController::class, 'search'])->name('home.search');

//About
Route::get('/about', [HomeController::class, 'about'])->name('home.about');

//Khóa học
Route::get('/coures', [HomeController::class, 'coures'])->name('home.coures');

//Sản phẩm
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'productDetails'])->name('shop.product.details');

//Hoạt động
Route::get('/activities', [HomeController::class, 'activities'])->name('home.activities');

//Tin tức
Route::get('/news', [HomeController::class, 'news'])->name('home.news');
Route::get('/news/{news_slug}', [HomeController::class, 'newsDetail'])->name('home.news.detail');

//User Account









// Chỉ Teacher
Route::middleware(['auth', AuthTeacher::class])->group(function () {
    Route::get('/teacher/classes', [TeacherController::class, 'classes'])->name('teacher.classes');
    Route::post('/teacher/grades', [TeacherController::class, 'updateGrades']);

    Route::get('/teacher/schedules', [TeacherController::class, 'schedules'])->name('teacher.schedules');
    Route::get('/teacher/schedules/filter', [TeacherController::class, 'filter'])->name('teacher.schedules.filter');

    Route::get('/teacher/schedule/{id}', [TeacherController::class, 'showSchedules'])->name('teacher.schedule.detail');
    Route::post('/teacher/attendance/save/{scheduleId}', [TeacherController::class, 'save'])->name('teacher.attendance.save');
    Route::post('/teacher/schedule/students/add', [TeacherController::class, 'store'])->name('teacher.schedule.addStudent');
    Route::post('/teacher/schedule/students/save', [TeacherController::class, 'saveStudent'])->name('teacher.schedule.saveStudent');
    
    Route::get('/teacher/lesson/{lessonId}/detail', [TeacherController::class, 'lessonDetail'])->name('teacher.lesson.detail');

    // Route cho giao bài tập
    Route::post('/teacher/homeworks', [TeacherController::class, 'homeworks'])->name('teacher.homeworks');

});

//Học sinh
Route::middleware(['auth', AuthStudent::class])->group(function () {
    Route::get('/student/registered-course', [StudentController::class, 'registeredCourses'])->name('student.registered-course');
    Route::get('/student/registered-class', [StudentController::class, 'registeredClass'])->name('student.registered-class');
    Route::get('/student/schedules', [StudentController::class, 'schedule'])->name('student.schedules');
    Route::get('/student/schedules/filter', [StudentController::class, 'filter'])->name('student.schedules.filter');
    Route::get('/student/lession/{id}', [StudentController::class, 'showLession'])->name('lessons.show');

    Route::get('/student/homework/{scheduleId}', [HomeworkController::class, 'getHomework'])->name('homework.get');
    Route::post('/student/homework/submit', [HomeworkController::class, 'submitHomework'])->name('homework.submit');



});

//phụ huynh
Route::middleware(['auth', AuthParent::class])->group(function () {
    Route::get('/parent/registered-course', [ParentController::class, 'registeredCourses'])->name('parent.registered-course');
    Route::get('/parent/registered-class', [ParentController::class, 'registeredClass'])->name('parent.registered-class');
    Route::get('/parent/schedules', [ParentController::class, 'schedule'])->name('parent.schedules');
    Route::get('/parent/schedules/filter', [ParentController::class, 'filter'])->name('parent.schedules.filter');
});












//Chỗ này cần xem lại
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');

    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.account.orders');
    Route::get('/account-order-details/{orderId}', [UserController::class, 'accountOrderDetails'])->name('user.account.orders.details');
    Route::put('account-order/cancel', [UserController::class, 'orderCancel'])->name('user.order.cancel');

    //Addresses
    Route::get('/account-addresses', [UserController::class, 'addresses'])->name('user.account.addresses');
    Route::get('/account-address/add', [UserController::class, 'addAddress'])->name('user.account.address.add');
    Route::post('/account-address/store', [UserController::class, 'storeAddress'])->name('user.account.address.store');
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
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    //User
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/user/add', [AdminController::class, 'addUser'])->name('admin.user.add');
    Route::post('/admin/user/store', [AdminController::class, 'storeUser'])->name('admin.user.store');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'editUser'])->name('admin.user.edit');
    Route::put('/admin/user/update', [AdminController::class, 'updateUser'])->name('admin.user.update'); /* Lưu �� cái này */
    Route::delete('/admin/user/{id}/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete'); /* Lưu �� cái này */

    //Settings
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::put('/admin/setting/changePassword', [AdminController::class, 'changePassword'])->name('admin.setting.changePassword');

    //Products
    Route::get('/admin/product/search', [AdminController::class, 'searchProduct'])->name('admin.product.search');
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'addProduct'])->name('admin.product.add');
    Route::post('/admin/product/store', [AdminController::class,'storeProduct'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.product.edit');
    Route::get('/admin/product/view/{id}', [AdminController::class, 'viewProduct'])->name('admin.product.view');
    Route::put('/admin/product/update', [AdminController::class, 'updateProduct'])->name('admin.product.update'); /* Lưu ý cái này */
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'deleteProduct'])->name('admin.product.delete'); /* Lưu ý cái này */

    //Brands
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brand/add', [AdminController::class, 'addBrand'])->name('admin.brand.add');
    Route::post('/admin/brand/store', [AdminController::class, 'storeBrand'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'editBrand'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [AdminController::class, 'updateBrand'])->name('admin.brand.update'); /* Lưu ý cái này */
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'deleteBrand'])->name('admin.brand.delete'); /* Lưu ý cái này */

    //Categories
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'addCategory'])->name('admin.category.add');
    Route::post('/admin/category/store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'editCategory'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class, 'updateCategory'])->name('admin.category.update'); /* Lưu ý cái này */
    Route::delete('/admin/category/{id}/delete', [AdminController::class, 'deleteCategory'])->name('admin.category.delete'); /* Lưu ý cái này */







    //Parents
    Route::get('/admin/parents', [AdminController::class, 'parents'])->name('admin.parents');
    Route::get('/admin/parent/add', [AdminController::class, 'addParent'])->name('admin.parent.add');
    Route::post('/admin/parent/store', [AdminController::class, 'storeParent'])->name('admin.parent.store');
    Route::get('/admin/parent/edit/{id}', [AdminController::class, 'editParent'])->name('admin.parent.edit');
    Route::get('/admin/parent/view/{id}', [AdminController::class, 'viewParent'])->name('admin.parent.view');
    Route::put('/admin/parents/{id}', [AdminController::class, 'updateParent'])->name('admin.parent.update');

    Route::delete('/admin/parent/{id}/delete', [AdminController::class, 'deleteParent'])->name('admin.parent.delete');

    Route::get('/admin/parents/filter', [AdminController::class, 'filterParents'])->name('admin.parents.filter');
    // Route::post('/parent/update-status', [AdminController::class, 'updateStatus'])->name('admin.parent.update-status');


    Route::post('/parent/appointment/create', [AdminController::class, 'createAppointment'])->name('admin.parent.appointment.create');
    Route::post('/parent/appointments', [AdminController::class, 'getAppointments'])->name('admin.parent.appointments');


    Route::post('/admin/parent/update-status', [AdminController::class, 'updateParentStatus'])->name('admin.parent.update-status');

    Route::get('/appointments/contact-again', [AdminController::class, 'getAllContactAgainAppointments'])->name('admin.appointments.contact_again');




    //Students
    Route::get('/admin/students', [AdminController::class, 'students'])->name('admin.students');
    Route::get('/admin/student/add', [AdminController::class, 'addStudent'])->name('admin.student.add');
    Route::post('/admin/student/store', [AdminController::class, 'storeStudent'])->name('admin.student.store');
    Route::get('/admin/student/edit/{id}', [AdminController::class, 'editStudent'])->name('admin.student.edit');
    Route::get('/admin/student/view/{id}', [AdminController::class, 'viewStudent'])->name('admin.student.view');
    Route::put('/admin/student/update/{id}', [AdminController::class, 'updateStudent'])->name('admin.student.update');
    Route::delete('/admin/student/delete/{id}', [AdminController::class, 'deleteStudent'])->name('admin.student.delete');
    Route::post('/admin/student/toggle-status', [AdminController::class, 'toggleStudentStatus'])->name('admin.student.toggle-status');

    Route::get('/admin/students/filter', [AdminController::class, 'filterStudents'])->name('admin.students.filter');


    //Teachers
    Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::get('/admin/teacher/add', [AdminController::class, 'addTeacher'])->name('admin.teacher.add');
    Route::post('/admin/teacher/store', [AdminController::class, 'storeTeacher'])->name('admin.teacher.store');
    Route::get('/admin/teacher/view/{id}', [AdminController::class, 'viewTeacher'])->name('admin.teacher.view');
    Route::get('/admin/teacher/edit/{id}', [AdminController::class, 'editTeacher'])->name('admin.teacher.edit');
    Route::delete('/admin/teacher/delete/{id}', [AdminController::class, 'deleteTeacher'])->name('admin.teacher.delete');
    Route::put('/admin/teacher/update/{id}', [AdminController::class, 'updateTeacher'])->name('admin.teacher.update');

    Route::post('/teacher/toggle-display', [AdminController::class, 'toggleDisplay'])->name('admin.teacher.toggle-display');
    Route::post('/teacher/update-status', [AdminController::class, 'updateStatus'])->name('admin.teacher.update-status');
    Route::get('/admin/teachers/filter', [AdminController::class, 'filterTeachers'])->name('admin.teachers.filter');











    //Tin tức
    Route::get('/admin/news', [newsController::class, 'index'])->name('admin.news');
    Route::get('/admin/news/add', [NewsController::class, 'create'])->name('admin.news.add');
    Route::post('/admin/news/store', [NewsController::class, 'store'])->name('admin.news.store');
    Route::get('/admin/news/edit/{id}', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('/admin/news/update/{id}', [NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('/admin/news/delete/{id}', [NewsController::class, 'destroy'])->name('admin.news.delete');
    Route::delete('/admin/news/view/{id}', [NewsController::class, 'view'])->name('admin.news.view');
    Route::get('/admin/news/filter', [newsController::class, 'filter'])->name('admin.news.filter');

    //Topics
    Route::get('/admin/topic', [topicController::class, 'index'])->name('admin.topic');
    Route::get('/admin/topic/add', [topicController::class, 'create'])->name('admin.topic.add');
    Route::post('/admin/topic/store', [topicController::class, 'store'])->name('admin.topic.store');
    Route::get('/admin/topic/edit/{id}', [topicController::class, 'edit'])->name('admin.topic.edit');
    Route::put('/admin/topic/update/{id}', [topicController::class, 'update'])->name('admin.topic.update');
    Route::delete('/admin/topic/delete/{id}', [topicController::class, 'destroy'])->name('admin.topic.delete');
    Route::delete('/admin/topic/view/{id}', [topicController::class, 'view'])->name('admin.topic.view');

    //khóa học
    Route::get('/admin/courses', [coursesController::class, 'index'])->name('admin.courses');
    Route::get('/admin/courses/add', [coursesController::class, 'create'])->name('admin.courses.add');
    Route::post('/admin/courses/store', [coursesController::class, 'store'])->name('admin.courses.store');
    Route::get('/admin/courses/edit/{id}', [coursesController::class, 'edit'])->name('admin.courses.edit');
    Route::put('/admin/courses/update/{id}', [coursesController::class, 'update'])->name('admin.courses.update');
    Route::delete('/admin/courses/delete/{id}', [coursesController::class, 'destroy'])->name('admin.courses.delete');
    Route::delete('/admin/courses/view/{id}', [coursesController::class, 'view'])->name('admin.courses.view');
    Route::get('/admin/courses/filter', [coursesController::class, 'filter'])->name('admin.courses.filter');


    //Môn học
    Route::get('/admin/subjects', [subjectsController::class, 'index'])->name('admin.subjects');
    Route::get('/admin/subjects/add', [subjectsController::class, 'create'])->name('admin.subjects.add');
    Route::post('/admin/subjects/store', [subjectsController::class, 'store'])->name('admin.subjects.store');
    Route::get('/admin/subjects/edit/{id}', [subjectsController::class, 'edit'])->name('admin.subjects.edit');
    Route::put('/admin/subjects/update/{id}', [subjectsController::class, 'update'])->name('admin.subjects.update');
    Route::delete('/admin/subjects/delete/{id}', [subjectsController::class, 'destroy'])->name('admin.subjects.delete');
    Route::delete('/admin/subjects/view/{id}', [subjectsController::class, 'view'])->name('admin.subjects.view');
    Route::get('/admin/subjects/filter', [subjectsController::class, 'filter'])->name('admin.subjects.filter');

    //Bài giảng của môn học
    Route::get('/admin/subjects/{id}/lessons', [lessonsController::class, 'index'])->name('admin.lessons');
    Route::get('/admin/subjects/{id}/lessons/add', [lessonsController::class, 'create'])->name('admin.lessons.add');
    Route::post('/admin/lessons/store', [lessonsController::class, 'store'])->name('admin.lessons.store');
    Route::get('/admin/lessons/edit/{id}', [lessonsController::class, 'edit'])->name('admin.lessons.edit');
    Route::put('/admin/lessons/update/{id}', [lessonsController::class, 'update'])->name('admin.lessons.update');
    Route::delete('/admin/lessons/delete/{id}', [lessonsController::class, 'destroy'])->name('admin.lessons.delete');
    Route::delete('/admin/lessons/view/{id}', [lessonsController::class, 'view'])->name('admin.lessons.view');
    Route::get('/admin/subjects/{id}/lessons/filter', [lessonsController::class, 'filter'])->name('admin.lessons.filter');








    // Lớp học
    Route::get('/class', [ClassController::class, 'index'])->name('class.index');
    Route::get('/class/create', [ClassController::class, 'create'])->name('class.create');
    Route::post('/class/store', [ClassController::class, 'store'])->name('class.store');
    Route::get('/class/{id}/edit', [ClassController::class, 'edit'])->name('class.edit');
    Route::put('/class/{id}', [ClassController::class, 'update'])->name('class.update');
    Route::delete('/class/{id}', [ClassController::class, 'destroy'])->name('class.destroy');
    Route::get('/class/{id}/schedule', [ClassController::class, 'showSchedule'])->name('class.schedule');
    Route::get('/admin/class/{id}/students', [ClassController::class, 'showStudents'])->name('class.students');


    Route::post('/students/add', [StudentController::class, 'store'])->name('students.add');
    Route::post('/save-student-selection', [StudentController::class, 'studentStore'])->name('students.studentStore');
    // routes/web.php
    Route::post('/classes/info', [ClassController::class, 'getClassInfo'])->name('api.class.info');
    Route::post('/save-class-selection', [ClassController::class, 'saveClassSelection'])->name('api.class.save');

    // Lịch học
    Route::get('/schedules', [SchedulesController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/add', [SchedulesController::class, 'add'])->name('schedules.add');
    Route::post('/schedules/store', [SchedulesController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{id}/update-teacher', [SchedulesController::class, 'updateTeacher'])->name('schedules.update-teacher');
    Route::get('/schedules/{id}/students', [SchedulesController::class, 'getScheduleStudents'])->name('schedules.students');


    // Lịch dạy của giáo viên
    Route::get('/teacherSchedule', [SchedulesController::class, 'teacherSchedule'])->name('schedules.teacherSchedule');

    // Chi tiết lịch học
    Route::get('/schedule/{id}', [ScheduleDetailController::class, 'show'])->name('schedules.detail');
    Route::post('/attendance/save/{scheduleId}', [ScheduleDetailController::class, 'save'])->name('attendance.save');
    Route::post('/schedule/students/add', [ScheduleDetailController::class, 'store'])->name('schedule.addStudent');
    Route::post('/schedule/students/save', [ScheduleDetailController::class, 'saveStudent'])->name('schedule.saveStudent');






















    //Giao dịch
    Route::get('/admin/transactions', [transactionsController::class, 'index'])->name('admin.transactions');
    Route::get('/admin/transactions/add', [transactionsController::class, 'create'])->name('admin.transactions.create');
    Route::post('/admin/transactions/store', [transactionsController::class, 'store'])->name('admin.transactions.store');
    Route::get('/admin/transactions/{id}/edit', [transactionsController::class, 'edit'])->name('admin.transactions.edit');
    Route::put('/admin/transactions/{id}/update', [transactionsController::class, 'update'])->name('admin.transactions.update');
    Route::get('/admin/transactions/{id}/details', [transactionsController::class, 'details'])->name('admin.transactions.details');
    Route::delete('/admin/transactions/{id}/delete', [transactionsController::class, 'delete'])->name('admin.transactions.delete');
    Route::get('/admin/transactions/filter', [transactionsController::class, 'filter'])->name('admin.transactions.filter');
    Route::get('/admin/accountsPayable', [transactionsController::class, 'accountsPayable'])->name('admin.transactions.accountsPayable');

    // Chi tiết giao dịch
    Route::get('/admin/transactions/{id}/detail', [transactionDetailsController::class, 'detail'])->name('admin.transactions.detail');
    Route::post('/admin/transactions/{id}/detail/store', [transactionDetailsController::class, 'store'])->name('admin.transactions.detail.store');


    //Xuất hóa đơn
    Route::get('admin/invoice/{id}/showDetail/{detaiId}', [InvoiceController::class, 'showDetail'])->name('admin.invoice.showDetail');
    Route::get('admin/invoice/{id}/show', [InvoiceController::class, 'show'])->name('admin.invoice.show');
    Route::get('admin/invoice/{id}/print', [InvoiceController::class, 'print'])->name('admin.invoice.print');
    Route::get('admin/invoice/{id}/printDetail/{detailId}', [InvoiceController::class, 'printDetail'])->name('admin.invoice.printDetail/{detailId}');

























    //Orders
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/{id}', [AdminController::class, 'orderDetail'])->name('admin.order.detail');

    //Coupon
    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add', [AdminController::class, 'addCoupon'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [AdminController::class, 'storeCoupon'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'editCoupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class, 'updateCoupon'])->name('admin.coupon.update'); /* Lưu �� cái này */
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'deleteCoupon'])->name('admin.coupon.delete'); /* Lưu �� cái này */

    //Orders
    Route::get('/admin/order', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/items/{orderId}', [AdminController::class, 'orderDetails'])->name('admin.order.items');
    Route::put('/admin/order/update', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status.update');
    Route::put('/admin/order/edit', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status.edit');

    //Slider
    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/add', [AdminController::class, 'addSlide'])->name('admin.slide.add');
    Route::post('/admin/slide/store', [AdminController::class, 'storeSlide'])->name('admin.slide.store');
    Route::get('/admin/slide/edit/{id}', [AdminController::class, 'editSlide'])->name('admin.slide.edit');
    Route::put('/admin/slide/update', [AdminController::class, 'updateSlide'])->name('admin.slide.update'); /* Lưu �� cái này */
    Route::delete('/admin/slide/{id}/delete', [AdminController::class, 'deleteSlide'])->name('admin.slide.delete'); /* Lưu �� cái này */

    //Contact
    Route::get('/admin/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::get('/admin/contact/{id}', [AdminController::class, 'viewContact'])->name('admin.contact.view');
    Route::delete('/admin/contact/delete/{id}', [AdminController::class, 'deleteContact'])->name('admin.contact.delete');

    Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
});
