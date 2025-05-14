<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClassController extends Controller
{
    public function index(Request $request)
{
    // Build the query
    $query = Classes::select(
        'classes.id AS sap_xep',
        'classes.class_code AS ma_lop',
        'classes.class_name AS ten_lop',
        'classes.description AS mo_ta',
        'classes.schedule AS lich_hoc',
        DB::raw("COALESCE(t1.full_name, 'Chưa có') AS giao_vien_phu_trach_chinh"),
        DB::raw("COALESCE(t2.notes, 'Chưa có') AS nhan_vien"),
        'classes.learning_format AS hinh_thuc',
        DB::raw("(SELECT COUNT(*) FROM attendance WHERE attendance.class_id = classes.id) AS so_buoi_hoc"),
        DB::raw("(SELECT COUNT(DISTINCT attendance_detail.attendance_id) 
            FROM attendance_detail 
            JOIN attendance ON attendance_detail.attendance_id = attendance.id 
            WHERE attendance.class_id = classes.id) AS so_buoi_hoc_co_diem_danh"),
        DB::raw("(SELECT COUNT(*) FROM student_classes 
            WHERE student_classes.class_id = classes.id 
            AND student_classes.status = 'active') AS so_hoc_sinh"),
        'classes.status AS trang_thai_lop_hoc',
        'classes.created_at AS ngay_tao_lop_hoc',
        'classes.active_days AS active_days'
    )
        ->leftJoin('schedules', 'classes.id', '=', 'schedules.class_id')
        ->leftJoin('teachers AS t1', 'classes.teacher_id', '=', 't1.user_id')
        ->leftJoin('students AS t2', 'schedules.assistant_teacher_id', '=', 't2.user_id');

    // Apply filters
    if ($request->filled('keyword') && $request->filled('truong_tim_kiem')) {
        $field = $request->truong_tim_kiem;
        $keyword = $request->keyword;
        if ($field === 'code') {
            $query->where('classes.class_code', 'like', "%{$keyword}%");
        } elseif ($field === 'name') {
            $query->where('classes.class_name', 'like', "%{$keyword}%");
        }
    }

    if ($request->filled('giao_vien') && $request->giao_vien !== '') {
        $query->where('classes.teacher_id', $request->giao_vien);
    }

    if ($request->filled('trang_thai') && $request->trang_thai !== '') {
        $query->where('classes.status', $request->trang_thai);
    }

    if ($request->filled('hinh_thuc') && $request->hinh_thuc !== '') {
        $query->where('classes.learning_format', $request->hinh_thuc);
    }

    // Group by necessary columns
    $query->groupBy(
        'classes.id',
        'classes.class_code',
        'classes.class_name',
        'classes.learning_format',
        'classes.status',
        'classes.description',
        'classes.created_at',
        'classes.schedule',
        't1.full_name',
        't2.notes',
        'classes.active_days'
    );

    // Number of items per page
    $perPage = $request->input('per_page', 4);

    // Paginate the results
    $classes = $query->orderBy('classes.id')->paginate($perPage);

    // Map the results to format the schedule
    $classes->getCollection()->transform(function ($class) {
        $schedule = json_decode($class->lich_hoc, true);
        if (is_array($schedule) && !empty($schedule)) {
            $dayMap = [
                'thu-hai' => 'Thứ Hai',
                'thu-ba' => 'Thứ Ba',
                'thu-tu' => 'Thứ Tư',
                'thu-nam' => 'Thứ Năm',
                'thu-sau' => 'Thứ Sáu',
                'thu-bay' => 'Thứ Bảy',
                'chu-nhat' => 'Chủ Nhật',
            ];
            $formattedSchedule = array_map(function ($day) use ($dayMap) {
                return $dayMap[$day] ?? $day;
            }, $schedule);
            $class->lich_hoc = implode(', ', $formattedSchedule);
        } else {
            $class->lich_hoc = 'Chưa có lịch học';
        }
        return $class;
    });

    // Data for filters
    $teachers = Teacher::join('users', 'teachers.user_id', '=', 'users.id')
        ->select('teachers.user_id', 'users.full_name')
        ->orderBy('users.full_name')
        ->get();
    $statuses = ['1' => 'Đang học', '0' => 'Kết thúc'];
    $learningFormats = ['online' => 'Online', 'offline' => 'Offline'];
    $searchFields = ['code' => 'Mã lớp', 'name' => 'Tên lớp'];

    // Modified to handle AJAX request by returning just the HTML content that needs to be updated
    if ($request->ajax()) {
        return view('class.index', compact('classes', 'teachers', 'statuses', 'learningFormats', 'searchFields'));
    }

    return view('class.index', compact('classes', 'teachers', 'statuses', 'learningFormats', 'searchFields'));
}

    public function addStudents() {
        
    }
    public function create()
    {
        // Lấy danh sách giáo viên (type = TEACHER)
        $teachers = DB::table('teachers')
            ->select('teachers.user_id', 'teachers.full_name', 'teachers.teacher_code')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->where('users.utype', 'TEACHER')
            ->get();

        // Lấy danh sách khóa học (giả định bảng courses)
        $courses = DB::table('courses')
            ->select('id', 'course_name')
            ->get();

        // Dữ liệu tĩnh cho các trường khác
        $learningFormats = [
            ['id' => 'online', 'name' => 'Online'],
            ['id' => 'offline', 'name' => 'Offline']
        ];
        $statuses = [
            ['id' => 1, 'name' => 'Hoạt động'],
            ['id' => 0, 'name' => 'Tạm ngưng']
        ];

        return view('class.create', compact('teachers', 'courses', 'learningFormats', 'statuses'));
    }
    public function store(Request $request)
    {
        // dd($request);
        // Nếu schedule là chuỗi, chuyển thành mảng
        //         $schedule = $request->schedule;
        //         $schedule = $request->schedule;
        // if (is_string($schedule)) {
        //     $schedule = explode(',', $schedule);
        //     $schedule = array_map('trim', $schedule);
        //     // Kiểm tra giá trị hợp lệ
        //     $validDays = ['thu-hai', 'thu-ba', 'thu-tu', 'thu-nam', 'thu-sau', 'thu-bay', 'chu-nhat'];
        //     $schedule = array_filter($schedule, fn($day) => in_array($day, $validDays));
        //     $request->merge(['schedule' => $schedule]);
        // }
        // Xác thực dữ liệu
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'class_code' => 'required|string|max:50|unique:classes,class_code', // Thay 'classes' bằng tên bảng của bạn
            'learning_format' => 'required',
            'course_id' => 'required|integer|exists:courses,id', // Kiểm tra khóa học tồn tại
            'status' => 'required',
            'active_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'schedule' => 'required|array|min:1', // Lịch học là mảng, ít nhất 1 phần tử
            'schedule.*' => 'string|in:thu-hai,thu-ba,thu-tu,thu-nam,thu-sau,thu-bay,chu-nhat', // Kiểm tra giá trị lịch học
            'teacher_id' => 'nullable|integer|exists:users,id', // Giáo viên có thể không bắt buộc
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Tạo lớp học mới
        try {
            $class = Classes::create([
                'class_name' => $request->class_name,
                'class_code' => $request->class_code,
                'learning_format' => $request->learning_format,
                'course_id' => $request->course_id ? $request->course_id : null,
                'status' => $request->status,
                'active_days' => $request->active_days,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'teacher_id' => $request->teacher_id ? $request->teacher_id : null,
                'description' => $request->description,
                'schedule' => json_encode($request->schedule),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lớp học đã được thêm thành công!',
                'data' => $class
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    // Phương thức hiển thị lịch học của lớp
    public function showSchedule($id)
    {
        // Lấy thông tin lớp học
        $class = Classes::select(
            'classes.id',
            'classes.class_code AS ma_lop',
            'classes.class_name AS ten_lop',
            'classes.description AS mo_ta',
            'classes.learning_format AS hinh_thuc',
            DB::raw("COALESCE(t1.full_name, 'Chưa có') AS giao_vien_phu_trach_chinh"),
            DB::raw("(SELECT COUNT(*) FROM student_classes 
                WHERE student_classes.class_id = classes.id 
                AND student_classes.status = 'active') AS so_hoc_sinh"),
            'classes.active_days AS active_days',
            DB::raw("DATE_ADD(classes.created_at, INTERVAL classes.active_days DAY) AS ngay_ket_thuc"),
            'classes.status AS trang_thai_lop_hoc',
            'classes.created_at AS ngay_tao_lop_hoc'
        )
            ->leftJoin('teachers AS t1', 'classes.teacher_id', '=', 't1.user_id')
            ->where('classes.id', $id)
            ->first();

        if (!$class) {
            abort(404, 'Lớp học không tồn tại');
        }

        // Lấy lịch học của lớp, cùng với thông tin khóa học
        $schedules = DB::table('schedules')
            ->select(
                'schedules.start_date',
                'schedules.start_time',
                'schedules.end_time',
                DB::raw("COALESCE(t2.notes, 'Chưa có') AS tro_giang"),
                'courses.id AS course_id',
                'courses.course_name AS ten_khoa_hoc'
            )
            ->leftJoin('students AS t2', 'schedules.assistant_teacher_id', '=', 't2.user_id')
            ->leftJoin('courses', 'schedules.course_id', '=', 'courses.id') // Join với courses qua schedules
            ->where('schedules.class_id', $id)
            ->orderBy('schedules.start_date')
            ->get();

        // Lấy danh sách khóa học duy nhất từ lịch học
        $courseIds = $schedules->pluck('course_id')->unique()->filter();

        // Lấy danh sách môn học (subjects) cho tất cả các khóa học liên quan
        $subjects = DB::table('subjects')
            ->select(
                'subjects.id',
                'subjects.course_id',
                'subjects.subject_name AS ten_mon_hoc'
            )
            ->whereIn('subjects.course_id', $courseIds)
            ->orderBy('subjects.id')
            ->get();

        // Lấy danh sách bài học (lessons) cho từng môn học
        $subjectsWithLessons = $subjects->map(function ($subject) {
            $subject->lessons = DB::table('lessons')
                ->select(
                    'lessons.id',
                    'lessons.lesson_name AS ten_bai_hoc'
                )
                ->where('lessons.subject_id', $subject->id)
                ->orderBy('lessons.id')
                ->get();
            return $subject;
        });

        // Nhóm subjects theo course_id để hiển thị
        $subjectsByCourse = $subjectsWithLessons->groupBy('course_id');

        return view('class.schedule', compact('class', 'schedules', 'subjectsByCourse'));
    }

    public function showStudents($id)
    {
        // Lấy thông tin lớp học
        $class = Classes::select(
            'classes.id',
            'classes.class_code AS ma_lop',
            'classes.class_name AS ten_lop',
            DB::raw("COALESCE(t1.full_name, 'Chưa có') AS giao_vien_phu_trach_chinh"),
            DB::raw("(SELECT COUNT(*) FROM student_classes 
                WHERE student_classes.class_id = classes.id 
                AND student_classes.status = 'active') AS so_hoc_sinh")
        )
            ->leftJoin('users AS t1', 'classes.teacher_id', '=', 't1.id')
            ->where('classes.id', $id)
            ->first();

        if (!$class) {
            abort(404, 'Lớp học không tồn tại');
        }

        // Lấy danh sách học sinh của lớp và thông tin phụ huynh
        $students = DB::table('student_classes')
            ->select(
                'student_classes.id',
                'users.full_name AS ho_ten',
                'users.mobile AS dien_thoai',
                'students.school AS truong_hoc',
                'student_classes.status AS trang_thai',
                'student_classes.created_at AS ngay_tham_gia',
                'student_classes.updated_at AS ngay_cap_nhat',
                'students.notes AS ghi_chu',
                DB::raw("COALESCE(parent.full_name, 'Chưa có') AS ten_phu_huynh")
            )
            ->leftJoin('users', 'student_classes.student_id', '=', 'users.id')
            ->leftJoin('students', 'student_classes.student_id', '=', 'students.user_id')
            ->leftJoin('users AS parent', 'students.parent_id', '=', 'parent.id') // Join để lấy thông tin phụ huynh
            ->where('student_classes.class_id', $id)
            ->where('users.utype', 'STUDENT')
            ->orderBy('student_classes.id')
            ->get();

            // dd($class);
        return view('class.students', compact('class', 'students'));
    }

    public function edit($id)
    {
        // Lấy thông tin lớp học hiện tại
        $class = Classes::findOrFail($id);

        // Lấy danh sách giáo viên
        $teachers = DB::table('teachers')
            ->select('teachers.user_id', 'teachers.full_name', 'teachers.teacher_code')
            ->join('users', 'teachers.user_id', '=', 'users.id')
            ->where('users.utype', 'TEACHER')
            ->get();

        // Lấy danh sách khóa học
        $courses = DB::table('courses')
            ->select('id', 'course_name')
            ->get();

        // Dữ liệu tĩnh
        $learningFormats = [
            ['id' => 1, 'name' => 'online'],
            ['id' => 2, 'name' => 'offline'],
            // ['id' => 3, 'name' => 'Hybrid'],
        ];
        $statuses = [
            ['id' => 1, 'name' => 'Hoạt động'],
            ['id' => 0, 'name' => 'Tạm ngưng'],
        ];
        $schedule = json_decode($class->schedule, true) ?: [];
        // dd($class->schedule);
        // Chuyển đổi schedule từ JSON sang mảng để hiển thị trong form
        // $schedule = json_decode($class->schedule, true) ?: [];
        $schedule = $class->schedule ?? [];

        return view('class.edit', compact('class', 'teachers', 'courses', 'learningFormats', 'statuses', 'schedule'));
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $class = Classes::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string|max:255',
            'class_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('classes')->ignore($class->id),
            ],
            'learning_format' => 'required|integer',
            'course_id' => 'required|integer|exists:courses,id', // Kiểm tra khóa học tồn tại
            'status' => 'required',
            'active_days' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'schedule' => 'required|array|min:1', // Lịch học là mảng, ít nhất 1 phần tử
            'schedule.*' => 'string|in:thu-hai,thu-ba,thu-tu,thu-nam,thu-sau,thu-bay,chu-nhat', // Kiểm tra giá trị lịch học
            'teacher_id' => 'nullable|integer|exists:users,id', // Giáo viên có thể không bắt buộc
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $class->update([
                'class_name' => $request->class_name,
                'class_code' => $request->class_code,
                'learning_format' => $request->learning_format,
                'course_id' => $request->course_id,
                'status' => $request->status,
                'active_days' => $request->active_days,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'teacher_id' => $request->teacher_id,
                'description' => $request->description,
                'schedule' => json_encode($request->schedule),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lớp học thành công!',
                'data' => $class
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        // dd($id);
        $class = Classes::findOrFail($id);

        try {
            $class->delete();
            return redirect()->route('class.index')->with('success', 'Xóa lớp học thành công!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }

    // 
    // app/Http/Controllers/ClassController.php
    public function getClassInfo(Request $request)
    {
        try {
            $classId = $request->input('classId'); // Lấy classId từ body của request
            // Lấy thông tin lớp học hiện tại
            $class = Classes::findOrFail($classId);

            // Lấy danh sách giáo viên
            $teachers = DB::table('teachers')
                ->select('teachers.user_id', 'teachers.full_name', 'teachers.teacher_code')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->where('users.utype', 'TEACHER')
                ->get();

            // Lấy giáo viên được chọn (dựa trên teacher_id của lớp)
            $selectedTeacher = DB::table('teachers')
                ->select('teachers.user_id', 'teachers.full_name', 'teachers.teacher_code')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->where('users.utype', 'TEACHER')
                ->where('teachers.user_id', $class->teacher_id)
                ->first();

            // Lấy danh sách khóa học
            // $courses = DB::table('courses')
            //     ->select('id', 'course_name', 'course_code')
            //     ->get();

            // Lấy khóa học được chọn
            $selectedCourse = DB::table('courses')
                ->select('id', 'course_name', 'course_code')
                ->where('id', $class->course_id)
                ->first();

            // Lấy danh sách chủ đề của khóa học
            $subjects = DB::table('subjects')
                ->select('id', 'subject_name')
                ->where('course_id', $class->course_id)
                ->get()
                ->map(function ($subject) {
                    // Lấy danh sách bài học cho từng chủ đề
                    $subject->lessons = DB::table('lessons')
                        ->select('id', 'lesson_name')
                        ->where('subject_id', $subject->id)
                        ->get();
                    return $subject;
                });

            // return view('schedules.index', compact('classId', 'class'));
            return response()->json([
                'classId' => $classId,
                'class' => $class,
                'teachers' => $teachers, // Danh sách tất cả giáo viên
                'selectedTeacher' => $selectedTeacher, // Giáo viên được chọn
                // 'courses' => $courses,
                'subjects' => $subjects,
                'selectedCourse' => $selectedCourse,
                'message' => 'Đã nhận classId thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
    public function saveClassSelection(Request $request)
    {
        try {
            // dd($request->all());
            // Ghi log dữ liệu đầu vào để debug
            Log::info('saveClassSelection input:', $request->all());
            $data = $request->validate([
                'classId' => 'required|integer|exists:classes,id',
                'scheduleDate' => 'required|date',
                'startTime' => 'required|date_format:H:i',
                'endTime' => 'required|date_format:H:i|after:startTime',
                'teacher_id' => 'required|integer|exists:teachers,user_id',
                'assistant_teacher_id' => 'nullable|integer|exists:teachers,user_id', // Cho phép null
                'subject_id' => 'required|integer|exists:subjects,id',
                'lesson_ids' => 'required|array',
                'lesson_ids.*' => 'integer|exists:lessons,id',
                 'note' => 'nullable|string|max:255',
                 'course_id' => 'required|integer|exists:courses,id' // Thêm validate cho course_id
            ]);
            // Ghi log dữ liệu đã xác thực
            Log::info('Validated data:', $data);

            // Kiểm tra xem lesson_ids có thuộc subject_id không
            $invalidLessons = DB::table('lessons')
                ->whereIn('id', $data['lesson_ids'])
                ->where('subject_id', '!=', $data['subject_id'])
                ->pluck('id')
                ->toArray();

            if (!empty($invalidLessons)) {
                throw ValidationException::withMessages([
                    'lesson_ids' => ['Các bài học không hợp lệ: ' . implode(', ', $invalidLessons) . ' không thuộc chủ đề đã chọn.']
                ]);
            }
            // Cập nhật teacher_id cho lớp học
            $class = Classes::findOrFail($data['classId']);
            $class->teacher_id = $data['teacher_id'];
            $class->save();

            // Kiểm tra xem lịch học đã tồn tại cho class_id, ngày và thời gian chưa
            $schedule = DB::table('schedules')
                ->where('class_id', $data['classId'])
                ->where('start_date', $data['scheduleDate'])
                ->where('start_time', $data['startTime'])
                ->first();
            if ($schedule) {
                // Cập nhật lịch học hiện có
                DB::table('schedules')
                    ->where('id', $schedule->id)
                    ->update([
                        'teacher_id' => $data['teacher_id'],
                        'assistant_teacher_id' => $data['assistant_teacher_id'],
                        'subject_id' => $data['subject_id'],
                        'course_id' => $data['course_id'], // Lưu course_id
                        'end_time' => $data['endTime'],
                        'notes' => $data['note'],
                        'updated_at' => now()
                    ]);
            } else {
                // Tạo bản ghi lịch học mới
                DB::table('schedules')->insert([
                    'class_id' => $data['classId'],
                    'start_date' => $data['scheduleDate'],
                    'start_time' => $data['startTime'],
                    'end_time' => $data['endTime'],
                    'teacher_id' => $data['teacher_id'],
                    'assistant_teacher_id' => $data['assistant_teacher_id'],
                    'subject_id' => $data['subject_id'],
                    'course_id' => $data['course_id'], // Lưu course_id
                    'notes' => $data['note'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Ghi log
            Log::info('Saved schedule for class', [
                'class_id' => $data['classId'],
                'start_date' => $data['scheduleDate'],
                'start_time' => $data['startTime'],
                'end_time' => $data['endTime'],
                'teacher_id' => $data['teacher_id'],
                'assistant_teacher_id' => $data['assistant_teacher_id'],
                'subject_id' => $data['subject_id'],
                'lesson_ids' => $data['lesson_ids'],
                'note' => $data['note']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dữ liệu đã được lưu thành công'
            ]);
        } catch (ValidationException $e) {
            // Xử lý lỗi validate
            Log::warning('Validation failed: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage()
            ], 500);
        }
    }
}
