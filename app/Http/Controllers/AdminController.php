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
use App\Models\ParentAppointment;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SuccessfulAppointment;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

    // Chỗ này cần check
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
            'image'=>'mimes:png,jpg,jpeg,gif,webp|max:2048'
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
            'slug'=>'required|unique:brands,slug,'.$request->id, /* lưu ý chỗ này */
            'image'=>'mimes:png,jpg,jpeg,gif,webp|max:2048'
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
            'image' => 'max:2048',
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
























   //Parents
    public function parents() {
        $parents = ParentModel::join('users', 'parents.user_id', '=', 'users.id')
            ->select('parents.*', 'users.full_name', 'users.email')
            ->orderBy('user_id', 'DESC')
            ->paginate(6);

        // Load tất cả subjects để map với ID
        $allSubjects = Subject::pluck('subject_name', 'id')->toArray();

        // Get status enum values
        $column = DB::select("SHOW COLUMNS FROM parents WHERE Field = 'status'");
        $type = $column[0]->Type;

        preg_match("/^enum\((.*)\)$/", $type, $matches);
        $statuses = [];
        if (!empty($matches)) {
            $values = explode(",", $matches[1]);
            foreach ($values as $value) {
                $statuses[] = trim($value, "'");
            }
        }
// Query the database to get total counts for each status
            $totalStats = ParentModel::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->all();
        $learning_formats = ParentModel::select('learning_format')->distinct()->get();

        return view('admin.parents.parents', compact('parents', 'statuses', 'learning_formats', 'allSubjects', 'totalStats'));
    }


    public function addParent() {
        $subjects = Subject::select('id','subject_name')->orderBy('id')->get();
        // dd($subjects);
        return view('admin.parents.parent-add', compact('subjects'));
    }

    public function storeParent(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:20',
            'address' => 'required|string',
            'school' => 'required|string',
            'grade' => 'required|string',
            'status' => 'required|string',
            'learning_format' => 'required|string',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'marketing_source' => 'required|string',
            'notes' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // 1. Tạo user mới
            $user = new User();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = bcrypt('123456');
            $user->utype = 'PARENT';
            $user->address = $request->address;

            if ($request->hasFile('image')) {
                $destinationPath = public_path('uploads/avatars');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image = $request->file('image');
                $fileName = time() . '.' . $image->extension();
                $image->move($destinationPath, $fileName);
                $user->image = $fileName;
            } else {
                $user->image = 'default.png';
            }
            $user->save();

            // 2. Tạo parent record
            $parent = new ParentModel();
            $parent->user_id = $user->id;
            $parent->status = $request->status;
            $parent->learning_format = $request->learning_format;
            $parent->school = $request->school;
            $parent->grade = $request->grade;
            $parent->subjects = json_encode($request->subjects);
            $parent->marketing_source = $request->marketing_source;
            $parent->notes = $request->notes;
            $parent->save();

            DB::commit();

            return redirect()->route('admin.parents')
                ->with('success', 'Thêm phụ huynh thành công!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function editParent($id)
    {
        $parent = DB::table('parents')
            ->join('users', 'parents.user_id', '=', 'users.id')
            ->select(
                'parents.*',
                'users.full_name',
                'users.mobile',
                'users.email',
                'users.address',
                'users.image' // Thay avatar bằng image
            )
            ->where('parents.user_id', '=', $id)
            ->first();

        if (!$parent) {
            return redirect()->route('admin.parents')->with('error', 'Không tìm thấy phụ huynh.');
        }

        $subjects = Subject::select('id', 'subject_name')->orderBy('id')->get();
        $selectedSubjects = json_decode($parent->subjects, true) ?? [];

        // Lấy danh sách trạng thái từ cột enum
        $column = DB::select("SHOW COLUMNS FROM parents WHERE Field = 'status'");
        $type = $column[0]->Type;
        preg_match("/^enum\((.*)\)$/", $type, $matches);
        $statuses = [];
        if (!empty($matches)) {
            $values = explode(",", $matches[1]);
            foreach ($values as $value) {
                $statuses[] = trim($value, "'");
            }
        }

        // Lấy danh sách learning_format duy nhất
        $learning_formats = ParentModel::select('learning_format')->distinct()->pluck('learning_format');

        // Truyền dữ liệu vào view
        return view('admin.parents.parent-edit', compact('parent', 'subjects', 'selectedSubjects', 'statuses', 'learning_formats'));
    }

   
    public function updateParent(Request $request, $id)
    {
        $parent = ParentModel::where('user_id', $id)->firstOrFail();
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
            'status' => 'required|in:pending,interested,exploring,doubtful,rejected,completed,reserved,inactive',
            'learning_format' => 'required|in:online,offline',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'integer|exists:subjects,id',
            'marketing_source' => 'required|in:none,ads_content,consultant,class_management,workshop,sales_marketing,teacher',
            'notes' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Update user data
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->address = $request->address;

            // Handle image upload
            if ($request->hasFile('image')) {
                if ($user->image && $user->image !== 'default.png') {
                    $oldImagePath = public_path('uploads/avatars/' . $user->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                $destinationPath = public_path('uploads/avatars');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $fileName);
                $user->image = $fileName;
            }

            $user->save();

            // Update parent data
            $parent->status = $request->status;
            $parent->learning_format = $request->learning_format;
            $parent->school = $request->school;
            $parent->grade = $request->grade;
            $parent->subjects = json_encode($request->subjects); // Store as JSON array
            $parent->marketing_source = $request->marketing_source;
            $parent->notes = $request->notes;
            $parent->save();

            DB::commit();

            return redirect()->route('admin.parents')
                ->with('success', 'Cập nhật phụ huynh thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating parent: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật phụ huynh. Vui lòng thử lại.')
                ->withInput();
        }


    }

    public function deleteParent($id)
    {
        // Tìm bản ghi user
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.parents')->with('error', 'Không tìm thấy người dùng.');
        }

        // Tìm bản ghi parent liên quan
        $parent = ParentModel::where('user_id', $id)->first();

        if (!$parent) {
            return redirect()->route('admin.parents')->with('error', 'Không tìm thấy phụ huynh liên quan đến người dùng này.');
        }

        DB::beginTransaction();

        try {
            // Xóa hình ảnh avatar nếu tồn tại
            if ($user->image && $user->image !== 'default.png') {
                $imagePath = public_path('uploads/avatars/' . $user->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            // Xóa bản ghi trong bảng parents trước
            $parent->delete();

            // Xóa bản ghi trong bảng users
            $user->delete();

            DB::commit();

            return redirect()->route('admin.parents')
                ->with('success', 'Xóa phụ huynh và người dùng thành công!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Lỗi cơ sở dữ liệu khi xóa phụ huynh: ' . $e->getMessage());
            return redirect()->route('admin.parents')
                ->with('error', 'Lỗi cơ sở dữ liệu: Không thể xóa do ràng buộc khóa ngoại hoặc lỗi dữ liệu.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi xóa phụ huynh: ' . $e->getMessage());
            return redirect()->route('admin.parents')
                ->with('error', 'Đã xảy ra lỗi khi xóa phụ huynh: ' . $e->getMessage());
        }
    }

    public function viewParent($id)
    {
        // Debug: Kiểm tra ID đầu vào
        Log::info('viewParent called with ID: ' . $id);

        $parent = DB::table('parents')
        ->join('users', 'parents.user_id', '=', 'users.id')
        ->select(
            'parents.*',
            'users.full_name',
            'users.mobile',
            'users.email',
            'users.address',
            'users.image',
            'users.created_at'
        )
        ->where('parents.user_id', '=', $id)
        ->first();
        // dd($parent);
        if (!$parent) {
            Log::error('Parent not found for user_id: ' . $id);
            return redirect()->route('admin.parents')->with('error', 'Không tìm thấy phụ huynh.');
        }

       $allSubjects = Subject::pluck('subject_name', 'id')->toArray();

        // Giải mã subjects từ JSON và ánh xạ sang tên môn học
        $subjects = json_decode($parent->subjects, true) ?? [];
        $subjectNames = array_map(function ($subjectId) use ($allSubjects) {
            return $allSubjects[$subjectId] ?? 'N/A';
        }, $subjects);

        return view('admin.parents.parent-view', [
            'parent' => $parent,
            'subjects' => $subjectNames,
            'allSubjects' => $allSubjects
        ]);
    }



    public function filterParents(Request $request)
{
    $query = ParentModel::query();

    // Keyword filter
    if ($request->filled('keyword')) {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->keyword . '%')
              ->orWhere('email', 'like', '%' . $request->keyword . '%')
              ->orWhere('mobile', 'like', '%' . $request->keyword . '%');
        });
    }

    // Marketing source filter
    if ($request->filled('marketing_source')) {
        $query->where('marketing_source', $request->marketing_source);
    }

    // Learning format filter
    if ($request->filled('learning_format')) {
        $query->where('learning_format', $request->learning_format);
    }

    // Status filter with contact_date_filter for contact_again and appointment_success
    if ($request->filled('status')) {
        $status = $request->status;

        if (in_array($status, ['contact_again', 'appointment_success']) && $request->filled('contact_date_filter')) {
            $contactDate = $request->contact_date_filter;
            $query->where('status', $status)
                  ->whereHas('appointments', function ($q) use ($contactDate) {
                      $q->whereDate('contact_date', $contactDate);
                  });
        } else {
            $query->where('status', $status);
        }
    }

    // Date range filter for created_at
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // Rows per page
    $perPage = $request->input('per_page', 4);

    // Get paginated results
    $parents = $query->join('users', 'parents.user_id', '=', 'users.id')
        ->with(['user', 'appointments'])
        ->paginate($perPage);
    // dd($parents);
    // Additional data
    $allSubjects = Subject::pluck('subject_name', 'id')->toArray();
    $statuses = ['pending', 'contacted', 'doubtful', 'completed', 'interested', 'exploring', 'inactive', 'reserved', 'rejected', 'contact_again', 'appointment_success'];

    return view('admin.parents.parents', compact('parents', 'allSubjects', 'statuses'));
}


    public function createAppointment(Request $request)
    {
        try {
            $request->validate([
                'parent_id' => 'required|exists:parents,user_id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:contact_again,appointment_success',
                 'contact_date' => 'nullable|date_format:Y-m-d H:i:s', // Validate as DATETIME
            ]);

            // Create the appointment
            $appointment = ParentAppointment::create([
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'content' => $request->content,
                'status' => $request->status,
                'contact_date' => $request->contact_date,
            ]);

            // Update the parent's status
            ParentModel::where('user_id', $request->parent_id)->update([
                'status' => $request->status,
            ]);

            return response()->json(['message' => 'Appointment created and parent status updated successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error creating appointment: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error creating appointment: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

      public function getAppointments(Request $request)
    {
        try {
            $request->validate([
                'parent_id' => 'required|exists:parents,user_id',
            ]);

            Log::info('Fetching appointments for parent_id: ' . $request->parent_id);

            $appointments = ParentAppointment::where('parent_id', $request->parent_id)->get();
            $statusLabels = [
                'pending' => 'Đang chờ',
                'contacted' => 'Đã liên hệ',
                'doubtful' => 'Nghi ngờ',
                'completed' => 'Hoàn thành',
                'interested' => 'Quan tâm',
                'exploring' => 'Tìm hiểu',
                'inactive' => 'Ngừng khai thác',
                'reserved' => 'Bảo lưu',
                'rejected' => 'Từ chối',
                'contact_again' => 'Liên hệ lại',
            ];

            $appointments = $appointments->map(function ($appointment) use ($statusLabels) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'content' => $appointment->content,
                    'status' => $appointment->status,
                    'status_label' => $statusLabels[$appointment->status] ?? ucfirst($appointment->status),
                    'contact_date' => $appointment->contact_date ? $appointment->contact_date->format('d/m/Y H:i') : null, // Include time
                    'created_at' => $appointment->created_at->format('d/m/Y H:i'),
                ];
            });

            return response()->json(['appointments' => $appointments], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching appointments: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error fetching appointments: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateContactAgainStatus(Request $request)
{
    try {
        // Validate với các trường bắt buộc
        $validated = $request->validate([
            'id' => 'required|exists:parent_appointments,id',
            'parent_id' => 'required|exists:parents,user_id',
            'status' => 'required|string'
        ]);
        
        // Rest of the method...
    } catch (Exception $e) {
        Log::error('Error updating appointment status: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
        ], 422);
    }
}


    public function updateParentStatus(Request $request)
    {
        try {
            // Validate chỉ các trường cần thiết
            $validated = $request->validate([
                'parent_id' => 'required|exists:parents,user_id',
                'status' => 'required|string',
                'id' => 'nullable|exists:parent_appointments,id' // Optional
            ]);

            DB::beginTransaction();

            // 1. Cập nhật trạng thái của parent
            $parent = ParentModel::where('user_id', $validated['parent_id'])->firstOrFail();
            $parent->status = $validated['status'];
            $parent->save();

            // 2. Cập nhật appointment nếu có
            if (!empty($validated['id'])) {
                $appointment = ParentAppointment::findOrFail($validated['id']);
                $appointment->status = $validated['status'];
                $appointment->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công'
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating parent status: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

     //Liên hệ lần 2
    public function getAllContactAgainAppointments(Request $request)
    {
        try {
            $date = $request->input('date', Carbon::today()->toDateString());
            $search = $request->input('search', '');

            $query = ParentAppointment::where('status', 'contact_again')
                ->whereDate('contact_date', $date)
                ->with(['parent' => function ($query) {
                    $query->with('user');
                }]);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('content', 'like', '%' . $search . '%');
                });
            }

            $appointments = $query->orderBy('contact_date', 'asc')->get();
            // dd($appointments);

            $statusLabels = [
                'pending' => 'Đang chờ',
                'contacted' => 'Đã liên hệ',
                'doubtful' => 'Nghi ngờ',
                'completed' => 'Hoàn thành',
                'interested' => 'Quan tâm',
                'exploring' => 'Tìm hiểu',
                'inactive' => 'Ngừng khai thác',
                'reserved' => 'Bảo lưu',
                'rejected' => 'Từ chối',
                'contact_again' => 'Liên hệ lại',
            ];

            $appointments = $appointments->map(function ($appointment) use ($statusLabels) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->title,
                    'content' => $appointment->content,
                    'status' => $appointment->status,
                    'status_label' => $statusLabels[$appointment->status] ?? ucfirst($appointment->status),
                    'contact_date' => $appointment->contact_date ? $appointment->contact_date->format('d/m/Y H:i') : null,
                    'raw_contact_date' => $appointment->contact_date ? $appointment->contact_date->toDateTimeString() : null, // Keep raw DATETIME
                    'created_at' => $appointment->created_at->format('d/m/Y H:i'),
                    'parent_name' => $appointment->parent && $appointment->parent->user ? $appointment->parent->user->name : 'N/A',
                ];
            });

            $groupedAppointments = $appointments->groupBy(function ($appointment) {
                return Carbon::parse($appointment['raw_contact_date'])->format('Y-m-d');
            });

            return view('admin.parents.contact_again', [
                'groupedAppointments' => $groupedAppointments,
                'selectedDate' => $date,
                'search' => $search,
                'statusLabels' => $statusLabels,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching contact again appointments: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Lỗi khi tải danh sách lịch hẹn: ' . $e->getMessage());
        }
    }


   
  

   




















    //Student
    public function students() {
        // $students = Student::with('user')->orderBy('user_id', 'DESC')->paginate(10);
        $parents = DB::table('parents')->join('users', 'parents.user_id', '=', 'users.id')->select('parents.*', 'users.full_name', 'users.mobile')->get();
        // $students = Student::with('user')->orderBy('user_id', 'DESC')->paginate(10);
        $students = Student::with(['user', 'parent'])->orderBy('user_id', 'DESC')->paginate(10);
        return view('admin.students', compact('parents', 'students'));
    }

    public function addStudent()
    {
        $subjects = Subject::select('id','subject_name')->orderBy('id')->get();
        // Thêm dòng này để lấy danh sách phụ huynh
        $parents = DB::table('parents')->join('users', 'parents.user_id', '=', 'users.id')->select('parents.*', 'users.full_name', 'users.mobile')->get();
        return view('admin.student-add', compact('subjects', 'parents'));
    }

    public function storeStudent(Request $request)
    {
        // Validate dữ liệu
        // dd($_POST);
        $request->validate([
            'full_name' => 'required|string|max:255',
            'parent_id' => 'required|exists:parents,user_id',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:20',
            'birthday' => 'required|date',
            'gender' => 'required|in:nam,nữ',
            'address' => 'required|string|max:255',
            'username' => 'required|unique:users,username|max:255',
            'password' => 'required|min:1', // Increased min length for security
            'notes' => 'required|string',
            'grade' => 'required',
            'school' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create user record
            $user = new User();
            $user->full_name = $request->full_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->birthday = $request->birthday;
            $user->gender = $request->gender;
            $user->utype = 'STUDENT';
            $user->address = $request->address;

            // Xử lý ảnh nếu có
            if ($request->hasFile('image')) {
                // Tạo thư mục uploads/avatars nếu chưa tồn tại
                $destinationPath = public_path('uploads/avatars');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                // Lưu ảnh mới
                $image = $request->file('image');
                $fileName = time() . '.' . $image->extension();
                $image->move($destinationPath, $fileName);
                $user->image = $fileName;
            } else {
                $user->image = 'default.png';
            }

            $user->save();

            // 2. Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'parent_id' => $request->parent_id,
                'notes' => $request->notes,
                'grade' => $request->grade,
                'school' => $request->school,

            ]);

            DB::commit();
            return redirect()->route('admin.students')
                ->with('success', 'Thêm học sinh thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student creation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()
                ->with('error', 'Đã xảy ra lỗi khi thêm học sinh. Vui lòng kiểm tra dữ liệu và thử lại.')
                ->withInput();
        }
    }

    private $statusLabels = [
        'pending' => 'Đang chờ',
        'contacted' => 'Đã liên hệ',
        'doubtful' => 'Nghi ngờ',
        'completed' => 'Hoàn thành',
        'interested' => 'Quan tâm',
        'exploring' => 'Tìm hiểu',
        'inactive' => 'Ngừng khai thác',
        'reserved' => 'Bảo lưu',
        'rejected' => 'Từ chối'
    ];

    private $marketingSourceLabels = [
        'ads_content' => 'Ads & Content',
        'consultant' => 'Tư vấn viên',
        'class_management' => 'CSKH - Quản lý lớp học',
        'workshop' => 'Hội thảo',
        'sales_marketing' => 'Sale & Maketing',
        'teacher' => 'Giáo viên',
    ];

    // ... (Các hàm hiện tại cho phụ huynh)


    /**
     * Display the specified student's details.
     */
    public function viewStudent($id)
    {
        Log::info('viewStudent called with ID: ' . $id);
        $student = Student::with('user')->where('user_id', $id)->first();

        $parent = DB::table('students')
        ->join('parents', 'students.parent_id', '=', 'parents.user_id')
        ->join('users', 'parents.user_id', '=', 'users.id')
        ->where('students.user_id', $id)
        ->select(
            'users.id as parent_user_id',
            'users.full_name as parent_name',
            'users.email as parent_email',
            'users.mobile as parent_mobile',
            'users.address as parent_address',
            'students.user_id as student_id',
            'parents.user_id as parent_id'
        )->first();
        // dd($parent);
        if (!$student) {
            Log::error('Student not found for user_id: ' . $id);
            return redirect()->route('admin.students')->with('error', 'Không tìm thấy học sinh.');
        }

        return view('admin.student-view', compact('student', 'parent'));
    }

    /**
     * Delete the specified student from storage.
     */
    public function deleteStudent($id)
{

    $student = Student::where('user_id', $id)->first();
    $user = User::find($id);

    if (!$student || !$user) {
        return redirect()->route('admin.students')->with('error', 'Không tìm thấy học sinh.');
    }

    DB::beginTransaction();
    try {
        // Delete user image if exists
        if ($user->image && $user->image !== 'default.png') {
            $imagePath = public_path('uploads/avatars/' . $user->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Delete student and user records
        $student->delete();
        $user->delete();

        DB::commit();
        return redirect()->route('admin.students')
            ->with('success', 'Xóa học sinh thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('admin.students')
            ->with('error', 'Đã xảy ra lỗi khi xóa học sinh. Vui lòng thử lại.');
    }
}

    /**
     * Toggle student status (Hoạt động).
     */
    public function toggleStudentStatus(Request $request)
    {
        $studentId = $request->input('student_id');
        $status = $request->input('status');

        $student = Student::find($studentId);
        if ($student) {
            $student->status = $status;
            $student->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Học sinh không tồn tại'], 404);
    }


    public function editStudent($id)
    {
        // Log::info('editStudent called with ID: ' . $id);
        $student = DB::table('students')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select(
                'students.*',
                'users.full_name',
                'users.mobile',
                'users.email',
                'users.address',
                'users.image',
                'users.birthday',
                'users.gender',
                'users.username',
                'users.password',
            )
            ->where('students.user_id', '=', $id)
            ->first();
        // dd($student);
        if (!$student) {
            Log::error('Student not found for user_id: ' . $id);
            return redirect()->route('admin.students')->with('error', 'Không tìm thấy học sinh.');
        }

        // Lấy danh sách phụ huynh để hiển thị trong dropdown
        $parents = DB::table('parents')->join('users', 'parents.user_id', '=', 'users.id')->select('parents.*', 'users.full_name', 'users.mobile')->get();


        return view('admin.student-edit', compact('student', 'parents'));
    }

    public function updateStudent(Request $request, $id)
    {
        // Log::info('updateStudent called with ID: ' . $id);
        $student = Student::with('user')->where('user_id', $id)->first();

        if (!$student) {
            Log::error('Student not found for user_id: ' . $id);
            return redirect()->route('admin.students')->with('error', 'Không tìm thấy học sinh.');
        }

        // Validation
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $student->user->id,
            'mobile' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|in:nam,nữ',
            'school' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
            'parent_id' => 'required|exists:users,id',
            'username' => 'required|string|max:255|unique:users,username,' . $student->user->id,
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|max:2048', // 2MB max
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật thông tin User
            $user = $student->user;
            $user->full_name = $request->input('full_name');
            $user->email = $request->input('email');
            $user->mobile = $request->input('mobile');
            $user->address = $request->input('address');
            $user->birthday = $request->input('birthday');
            $user->gender = $request->input('gender');
            $user->username = $request->input('username');

            // Cập nhật mật khẩu nếu có
            if ($request->filled('password')) {
                $user->password = bcrypt($request->input('password'));
            }

            if ($request->hasFile('image')) {
                if ($user->image && $user->image != 'default.png') {
                    $oldImagePath = public_path('uploads/avatars/' . $user->image);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
                $destinationPath = public_path('uploads/avatars');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image = $request->file('image');
                $fileName = time() . '.' . $image->extension();
                $image->move($destinationPath, $fileName);
                $user->image = $fileName;
            }


            $user->save();

            // Cập nhật thông tin Student
            $student->parent_id = $request->input('parent_id');
            $student->school = $request->input('school');
            $student->grade = $request->input('grade');
            $student->notes = $request->input('notes');
            $student->save();

            DB::commit();
            // Log::info('Student updated successfully for user_id: ' . $id);
            return redirect()->route('admin.students')->with('success', 'Cập nhật thông tin học sinh thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật: ' . $e->getMessage())->withInput();
        }


    }


    public function filterStudents(Request $request)
        {
            $query = Student::query()->with('user');

            // Keyword filter
            if ($request->filled('keyword')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('full_name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%')
                    ->orWhere('mobile', 'like', '%' . $request->keyword . '%');
                });
            }

            // Birthday range filter
            if ($request->filled('birthday_from')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->whereDate('birthday', '>=', $request->birthday_from);
                });
            }
            if ($request->filled('birthday_to')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->whereDate('birthday', '<=', $request->birthday_to);
                });
            }

            // Gender filter
            if ($request->filled('gender')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->where('gender', $request->gender);
                });
            }

            // Rows per page
            $perPage = $request->input('per_page', 4);

            // Get paginated results
            $students = $query->paginate($perPage);

            // Debug pagination
            Log::info('Student Pagination Debug', [
                'total' => $students->total(),
                'perPage' => $students->perPage(),
                'hasPages' => $students->hasPages(),
                'currentPage' => $students->currentPage(),
                'filterParams' => $request->all(),
            ]);

            return view('admin.students', compact('students'));
        }

        public function toggleStatus(Request $request)
        {
            $student = Student::findOrFail($request->student_id);
            $student->status = $request->status;
            $student->save();

            return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        }





















        //Teacher
        public function teachers() {
            $teachers = Teacher::with('user')->orderBy('user_id', 'DESC')->paginate(10);
            // dd($teachers->all());
            return view('admin.teachers', compact('teachers'));
        }

        public function addTeacher() {
            return view('admin.teacher-add');
        }


        public function storeTeacher(Request $request)
        {
            // Validate dữ liệu
            // dd($_POST);
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'username' => 'required|unique:users,username',
                'password' => 'required|min:1',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            DB::beginTransaction();

            try {
                // 1. Create user record
                $user = new User();
                $user->full_name = $request->full_name;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->username = $request->username;
                $user->password = bcrypt($request->password);
                $user->utype = 'TEACHER';
                $user->address = $request->address;
                $user->birthday = $request->birthday;
                $user->gender = $request->gender;

                if ($request->hasFile('image')) {
                    if ($user->image && $user->image != 'default.png') {
                        $oldImagePath = public_path('uploads/avatars/' . $user->image);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }
                    $destinationPath = public_path('uploads/avatars');
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }
                    $image = $request->file('image');
                    $fileName = time() . '.' . $image->extension();
                    $image->move($destinationPath, $fileName);
                    $user->image = $fileName;
                }


                $user->save();

                // dd($user);
                // 2. Create teacher record
                $teacher = Teacher::create([
                    'user_id' => $user->id,
                    'full_name' => $user->full_name, //
                    'teacher_code' => rand(1000, 9999),
                    'academic_degree' => $request->academic_degree,
                    'title' => $request->title,
                    'notes' => $request->notes,
                    'facebook' => $request->facebook,
                    'display_on_homepage' => 0, // Default value
                    'introduction' => $request->introduction,
                    'achievements' => $request->achievements,
                    'status' => 'active', // Default status
                ]);

                Log::info('Teacher created successfully with ID: ' . $teacher->id);

                DB::commit();
                return redirect()->route('admin.teachers')
                    ->with('success', 'Thêm giáo viên thành công!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Teacher creation failed: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
                return back()
                    ->with('error', 'Đã xảy ra lỗi khi thêm giáo viên: ' . $e->getMessage())
                    ->withInput();
            }
        }



        public function viewTeacher($id)
        {
            Log::info('viewTeacher called with ID: ' . $id);
            $teacher = Teacher::with('user')->where('user_id', $id)->first();

            if (!$teacher) {
                Log::error('Teacher not found for user_id: ' . $id);
                return redirect()->route('admin.teachers')->with('error', 'Không tìm thấy giáo viên.');
            }

            return view('admin.teacher-view', compact('teacher'));
        }

        /**
         * Show the form for editing the specified teacher.
         */
        public function editTeacher($id)
        {
            // Log::info('editTeacher called with ID: ' . $id);
            $teacher = DB::table('teachers')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->select(
                    'teachers.*',
                    'users.full_name',
                    'users.mobile',
                    'users.email',
                    'users.address',
                    'users.avatar',
                    'users.birthday',
                    'users.gender',
                    'users.username',
                    'users.password',
                    'users.image',
                )
                ->where('teachers.user_id', '=', $id)
                ->first();
                    // dd($teacher);
            if (!$teacher) {
                Log::error('Teacher not found for user_id: ' . $id);
                return redirect()->route('admin.teachers')->with('error', 'Không tìm thấy giáo viên.');
            }

            return view('admin.teacher-edit', compact('teacher'));
        }

        public function updateTeacher(Request $request, $id)
        {
            $teacher = Teacher::with('user')->where('user_id', $id)->first();

            if (!$teacher) {
                Log::error('Teacher not found for user_id: ' . $id);
                return redirect()->route('admin.teachers')->with('error', 'Không tìm thấy giáo viên.');
            }

            // Validation
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $teacher->user->id,
                'mobile' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'required|in:nam,nữ',
                'username' => 'required|string|max:255|unique:users,username,' . $teacher->user->id,
                'password' => 'nullable|string|min:6',
                'avatar' => 'nullable|image|max:2048', // 2MB max
                'academic_degree' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
                'facebook' => 'nullable|url|max:255',
                'introduction' => 'nullable|string',
                'achievements' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            DB::beginTransaction();
            try {
                // Cập nhật thông tin User
                $user = $teacher->user;
                $user->full_name = $request->input('full_name');
                $user->email = $request->input('email');
                $user->mobile = $request->input('mobile');
                $user->address = $request->input('address');
                $user->birthday = $request->input('birthday');
                $user->gender = $request->input('gender');
                $user->username = $request->input('username');

                // Cập nhật mật khẩu nếu có
                if ($request->filled('password')) {
                    $user->password = bcrypt($request->input('password'));
                }

                if ($request->hasFile('image')) {
                    if ($user->image && $user->image != 'default.png') {
                        $oldImagePath = public_path('uploads/avatars/' . $user->image);
                        if (File::exists($oldImagePath)) {
                            File::delete($oldImagePath);
                        }
                    }
                    $destinationPath = public_path('uploads/avatars');
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }
                    $image = $request->file('image');
                    $fileName = time() . '.' . $image->extension();
                    $image->move($destinationPath, $fileName);
                    $user->image = $fileName;
                }

                $user->save();

                // Cập nhật thông tin Teacher
                $teacher->academic_degree = $request->input('academic_degree');
                $teacher->title = $request->input('title');
                $teacher->facebook = $request->input('facebook');
                $teacher->full_name = $request->input('full_name'); //
                $teacher->introduction = $request->input('introduction');
                $teacher->achievements = $request->input('achievements');
                $teacher->notes = $request->input('notes');
                $teacher->save();

                DB::commit();
                // Log::info('Teacher updated successfully for user_id: ' . $id);
                return redirect()->route('admin.teachers')->with('success', 'Cập nhật thông tin giáo viên thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                // Log::error('Error updating teacher: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật: ' . $e->getMessage())->withInput();
            }
        }

        public function deleteTeacher($id)
        {
            Log::info('deleteTeacher called with ID: ' . $id);
            $teacher = Teacher::where('user_id', $id)->first();

            if (!$teacher) {
                Log::error('Teacher not found for user_id: ' . $id);
                return redirect()->route('admin.teachers')->with('error', 'Không tìm thấy giáo viên.');
            }

            DB::beginTransaction();
            try {
                // Xóa avatar nếu có
                if ($teacher->user->avatar && $teacher->user->avatar != 'default.png') {
                    $imagePath = public_path('uploads/avatars/' . $teacher->user->avatar);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }

                // Xóa bản ghi Teacher trước
                $teacher->delete();

                // Sau đó xóa bản ghi User
                $teacher->user->delete();

                DB::commit();
                Log::info('Teacher deleted successfully for user_id: ' . $id);
                return redirect()->route('admin.teachers')
                    ->with('success', 'Xóa giáo viên thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error deleting teacher: ' . $e->getMessage());
                return redirect()->route('admin.teachers')
                    ->with('error', 'Đã xảy ra lỗi khi xóa giáo viên: ' . $e->getMessage());
            }
        }


        public function filterTeachers(Request $request)
    {
        $query = Teacher::query()->with('user');

        // Keyword filter
        if ($request->filled('keyword')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('email', 'like', '%' . $request->keyword . '%')
                  ->orWhere('mobile', 'like', '%' . $request->keyword . '%')
                  ->orWhere('username', 'like', '%' . $request->keyword . '%');
            })->orWhere('teacher_code', 'like', '%' . $request->keyword . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Rows per page
        $perPage = $request->input('per_page', 4);

        // Get paginated results
        $teachers = $query->paginate($perPage);

        // Debug pagination
        Log::info('Teacher Pagination Debug', [
            'total' => $teachers->total(),
            'perPage' => $teachers->perPage(),
            'hasPages' => $teachers->hasPages(),
            'currentPage' => $teachers->currentPage(),
            'filterParams' => $request->all(),
        ]);

        return view('admin.teachers', compact('teachers'));
    }

    public function toggleDisplay(Request $request)
    {
        $teacher = Teacher::findOrFail($request->teacher_id);
        $teacher->display_on_homepage = $request->display_on_homepage;
        $teacher->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái hiển thị thành công']);
    }

    public function updateStatus(Request $request)
    {
        $teacher = Teacher::findOrFail($request->teacher_id);
        $teacher->status = $request->status;
        $teacher->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
    }









    //Product
    public function products()
    {
        $products = Product::orderBy('id', 'DESC')->paginate(10);
        $categories = Category::select('id', 'name')->first();
        $brands = Brand::select('id', 'name')->first();
        return view('admin.products', compact('products', 'categories', 'brands'));
    }

     public function searchProduct(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::select('id', 'name')->first();
        $brands = Brand::select('id', 'name')->first();

        if ($query) {
            $products = Product::orWhere('name', 'LIKE', "%{$query}%")
                ->orWhere('slug', 'LIKE', "%{$query}%")
                ->orderBy('id', 'DESC')
                ->paginate(10); // Sử dụng paginate thay vì take(8)
            $products->appends(['query' => $query]); // Giữ tham số query khi phân trang
        } else {
            $products = Product::orderBy('id', 'DESC')->paginate(10); // Hiển thị mặc định nếu không có query
        }

        return view('admin.products', compact('products', 'categories', 'brands', 'query'));
    }


    public function addProduct()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
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
            'image'=>'required|mimes:png,jpg,jpeg,webp|max:2048'
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
        //dd($request->all());
        try {
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
                'image'=>'required|mimes:png,jpg,jpeg,gif,webp|max:2048'
            ]);
        } catch(ValidationException $e) {
             dd($e->errors());
        }
        

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
                'image' =>'required|image|max:2048'
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

            $this->generateThumbnailImage($image, $fileName, 'uploads/slides', 1890, 650);
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
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
                $this->generateThumbnailImage($image, $fileName, 'uploads/slides', 1890, 650);
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
