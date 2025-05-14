<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Schedules;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{
   

    public function index(Request $request)
{
    $query = Classes::select(
        'classes.id',
        'classes.class_code AS ma_lop',
        'classes.class_name AS ten_lop',
        'classes.learning_format AS hinh_thuc',
        DB::raw("COALESCE(t1.full_name, 'Chưa có') AS giao_vien_phu_trach_chinh"),
        DB::raw("(SELECT COUNT(*) FROM student_classes 
                WHERE student_classes.class_id = classes.id 
                AND student_classes.status = 'active') AS so_hoc_sinh"),
        'classes.status AS trang_thai_lop_hoc'
    )
    ->leftJoin('teachers AS t1', 'classes.teacher_id', '=', 't1.user_id');

    // Bộ lọc - Chỉ áp dụng nếu có giá trị được nhập
    if ($request->filled('keyword') && $request->filled('truong_tim_kiem')) {
        $field = $request->truong_tim_kiem;
        $keyword = $request->keyword;
        if ($field === 'code') {
            $query->where('classes.class_code', 'like', "%{$keyword}%");
        } elseif ($field === 'name') {
            $query->where('classes.class_name', 'like', "%{$keyword}%");
        }
    }

    if ($request->filled('giao_vien') && $request->giao_vien != '') {
        $query->where('classes.teacher_id', $request->giao_vien);
    }

    if ($request->filled('trang_thai') && $request->trang_thai != '') {
        $query->where('classes.status', $request->trang_thai);
    }

    if ($request->filled('hinh_thuc') && $request->hinh_thuc != '') {
        $query->where('classes.learning_format', $request->hinh_thuc);
    }

    // Số dòng mỗi trang
    $perPage = $request->input('per_page', 4);

    // Phân trang
    $classes = $query->orderBy('classes.id')->paginate($perPage);

    // Dữ liệu cho bộ lọc
    $teachers = Teacher::join('users', 'teachers.user_id', '=', 'users.id')
        ->select('teachers.user_id', 'users.full_name')
        ->orderBy('users.full_name')
        ->get();
    $statuses = ['0' => 'Đang học', '1' => 'Kết thúc'];
    $learningFormats = ['online' => 'Online', 'offline' => 'Offline'];
    $searchFields = ['code' => 'Mã lớp', 'name' => 'Tên lớp'];

    // Log::info('Class Filter Debug', [
    //     'total' => $classes->total(),
    //     'isEmpty' => $classes->isEmpty(),
    //     'perPage' => $classes->perPage(),
    //     'currentPage' => $classes->currentPage(),
    //     'params' => $request->all(),
    // ]);

    if ($request->ajax()) {
        return response()->json([
            'table' => view('schedules.partials.class_table', compact('classes'))->render(),
            'pagination' => view('pagination::bootstrap-5', ['paginator' => $classes])->render(),
        ]);
    }

    return view('schedules.index', compact('classes', 'teachers', 'statuses', 'learningFormats', 'searchFields'));
}







    public function add() {
        
        return view('schedules.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'required|exists:lessons,id',
            'lesson_detail' => 'required',
        ]);

        Schedules::create($request->all()); // Thay Schedule bằng model thực tế

        return response()->json(['message' => 'Lịch học đã được thêm']);
    }


    public function teacherSchedule()
    {
       // Lấy danh sách lớp học với thông tin giáo viên, khóa học, môn học và lịch học
        $classes = DB::table('classes')
            ->leftJoin('teachers', 'classes.teacher_id', '=', 'teachers.user_id')
            ->leftJoin('users', 'teachers.user_id', '=', 'users.id')
            ->leftJoin('courses', 'classes.course_id', '=', 'courses.id')
            ->leftJoin('subjects', 'subjects.course_id', '=', 'courses.id')
            ->leftJoin('schedules', 'classes.id', '=', 'schedules.class_id')
            ->select(
                'classes.id',
                'classes.class_name',
                'classes.class_code',
                'schedules.notes',
                'classes.learning_format',
                'classes.learning_format as status',
                'users.full_name as teacher_name',
                'courses.course_name',
                'subjects.subject_name',
                'subjects.id as subject_id',
                'schedules.start_date',
                'schedules.start_time',
                'schedules.end_time'
            )
            ->orderBy('classes.id')
            ->get()
            ->map(function ($class, $index) {
                // Lấy lessons dựa vào subject_id
                if ($class->subject_id) {
                    $class->lessons = DB::table('lessons')
                        ->where('subject_id', $class->subject_id)
                        ->pluck('lesson_name')
                        ->toArray();
                } else {
                    $class->lessons = [];
                }
                $class->stt = $index + 1;
                return $class;
            });

        // Thêm STT cho mỗi lớp học
        $classes = $classes->map(function ($class, $index) {
            $class->stt = $index + 1;
            return $class;
        });
        // Trả về view với dữ liệu
        return view('schedules.teacher-schedule', compact('classes'));
    }


}
