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
use Illuminate\Support\Facades\Log;

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

    public function add()
    {

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
        // Tăng giới hạn GROUP_CONCAT
        DB::statement('SET SESSION group_concat_max_len = 1000000');

        // Lấy lịch học và thông tin liên quan
        $teacherSchedules = DB::table('schedules')
            ->leftJoin('users AS u', 'schedules.teacher_id', '=', 'u.id')
            ->leftJoin('classes', 'schedules.class_id', '=', 'classes.id')
            ->leftJoin('schedule_lessons', 'schedules.id', '=', 'schedule_lessons.schedule_id')
            ->leftJoin('lessons', 'schedule_lessons.lesson_id', '=', 'lessons.id')
            ->leftJoin('subjects', 'lessons.subject_id', '=', 'subjects.id')
            ->leftJoin('courses', 'subjects.course_id', '=', 'courses.id')
            ->leftJoin('student_classes', 'classes.id', '=', 'student_classes.class_id')    // Thêm join với bảng student_classes
            ->leftJoin('schedule_detail', 'schedules.id', '=', 'schedule_detail.schedule_id') // Thêm join với bảng schedule_detail
            ->select(
                'u.id AS teacher_id',
                DB::raw("COALESCE(u.full_name, 'Chưa có') AS teacher_name"),
                'classes.id as class_id',
                'classes.class_name',
                'classes.class_code',
                'classes.learning_format',
                'schedules.id AS schedule_id',
                'schedules.start_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.notes AS schedule_notes',
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT courses.course_name SEPARATOR ', '), 'Chưa có') AS courses"),
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT subjects.subject_name SEPARATOR ', '), 'Chưa có') AS subjects"),
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT lessons.lesson_name SEPARATOR ', '), 'Chưa có') AS lessons"),
                // DB::raw("COUNT(DISTINCT student_classes.student_id) AS student_count") // Đếm số học sinh
            )
            ->groupBy(
                'schedules.id', // Nhóm theo ID lịch học (có thể là duy nhất)
                'u.id',
                'u.full_name',
                'classes.id',
                'classes.class_name',
                'classes.class_code',
                'classes.learning_format',
                'schedules.start_date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.notes'
            )
            ->orderBy('u.full_name')
            ->orderBy('schedules.start_date')
            ->get();
        // ->map(function ($schedule, $index) {
        //     $schedule->stt = $index + 1;
        //     return $schedule;
        // });

        // Đếm học sinh riêng
        $teacherSchedules = $teacherSchedules->map(function ($schedule, $index) {
            $studentCount = DB::table('student_classes')
                ->where('class_id', $schedule->class_id)
                ->select('student_id')
                ->union(
                    DB::table('schedule_detail')
                        ->where('schedule_id', $schedule->schedule_id)
                        ->select('student_id')
                )
                ->distinct()
                ->count('student_id');

            $schedule->student_count = $studentCount;
            $schedule->stt = $index + 1;
            return $schedule;
        });

        // Debug: Kiểm tra số lượng lịch học (schedules)
        $schedulesCount = DB::table('schedules')->count();
        Log::info('TeacherSchedule: total schedules count', ['count' => $schedulesCount]);

        // dd($teacherSchedules);

        return view('schedules.teacher-schedule', compact('teacherSchedules'));
    }

    public function updateTeacher(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'giao_vien_id' => 'required|exists:teachers,user_id',
        ], [
            'giao_vien_id.required' => 'Vui lòng chọn giáo viên',
            'giao_vien_id.exists' => 'Giáo viên không tồn tại trong hệ thống',
        ]);

        try {
            // Lấy lịch học cần cập nhật
            $schedule = Schedules::findOrFail($id);

            // Cập nhật thông tin giáo viên cho lịch học
            $schedule->teacher_id = $request->giao_vien_id;
            $schedule->save();

            // Redirect nếu là form submission thông thường
            return redirect()->back()->with('success', 'Cập nhật giáo viên thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function getScheduleStudents($scheduleId)
    {
        // Lấy thông tin lịch học
        $scheduleData = DB::table('schedules')
            ->where('schedules.id', $scheduleId)
            ->leftJoin('users as teachers', 'schedules.teacher_id', '=', 'teachers.id')
            // ->leftJoin('users as assistant_teachers', 'schedules.assistant_teacher_id', '=', 'assistant_teachers.id')
            ->leftJoin('classes', 'schedules.class_id', '=', 'classes.id')
            ->select(
                'schedules.*',
                'teachers.full_name as teacher_name',
                'teachers.mobile',
                // 'assistant_teachers.name as assistant_teacher_name',
                // 'classes.class_name',
                'classes.class_name',
                'classes.class_code',
                'classes.id AS class_id'
            )
            ->first();

        if (!$scheduleData) {
            abort(404); // Hoặc xử lý trường hợp không tìm thấy lịch học
            // return redirect()->route('schedules.index')->with('error', 'Lịch học không tồn tại.');
        }

        // Khởi tạo students là collection rỗng
        $students = collect();

        // Lấy thông tin lớp học (nếu chưa có trong $scheduleData)
        if (isset($scheduleData->class_id)) {
            // $class = DB::table('classes')
            //     ->where('id', $scheduleData->class_id)
            //     ->first();

            // Lấy danh sách học sinh từ student_classes
            $studentUserIdsFromClass = DB::table('student_classes')
                ->where('class_id', $scheduleData->class_id)
                ->pluck('student_id')
                ->toArray();

            // Lấy danh sách học sinh từ schedule_detail
            $studentUserIdsFromSchedule = DB::table('schedule_detail')
                ->where('schedule_id', $scheduleId)
                ->pluck('student_id')
                ->toArray();

            // Hợp nhất danh sách student_id từ cả hai nguồn (loại bỏ trùng lặp)
            $studentUserIds = array_unique(array_merge($studentUserIdsFromClass, $studentUserIdsFromSchedule));

            // Lấy thông tin chi tiết học sinh và dữ liệu phụ
            $students = DB::table('users')
                ->select(
                    'users.id',
                    'users.full_name AS ho_ten',
                    // 'users.email',
                    'users.mobile AS dien_thoai',
                    // 'users.avatar',
                    // 'users.status AS trang_thai_user',
                    // 'users.created_at AS ngay_tao_user',

                    // Thông tin từ bảng students
                    'students.school AS truong_hoc',
                    // 'students.grade AS lop',
                    'students.notes AS ghi_chu',
                    'students.status AS trang_thai',
                    'students.registered_sessions AS sb_dk',
                    'students.attended_sessions AS sb_tg',
                    'students.remaining_sessions AS sb_cl',
                    // 'students.created_at AS ngay_tao_student',

                    // Thông tin phụ huynh
                    'parent.full_name AS ten_phu_huynh',
                    // 'parent.mobile AS sdt_phu_huynh',
                    // 'parent.email AS email_phu_huynh',

                    // Thông tin từ student_classes
                    // 'student_classes.status AS trang_thai',
                    'student_classes.created_at AS ngay_tham_gia',
                    'student_classes.updated_at AS ngay_cap_nhat',

                    // Thông tin từ schedule_detail (chưa dùng)
                    // 'schedule_detail.status AS trang_thai_lich',
                    // 'schedule_detail.attendance_status AS trang_thai_diem_danh',
                    // 'schedule_detail.notes AS ghi_chu_lich',
                    // 'schedule_detail.created_at AS ngay_tao_lich_chi_tiet'
                )
                ->leftJoin('students', 'users.id', '=', 'students.user_id')
                ->leftJoin('users AS parent', 'students.parent_id', '=', 'parent.id')
                ->leftJoin('student_classes', function ($join) use ($scheduleData) {
                    $join->on('users.id', '=', 'student_classes.student_id')
                        ->where('student_classes.class_id', $scheduleData->class_id);
                })
                ->leftJoin('schedule_detail', function ($join) use ($scheduleId) {
                    $join->on('users.id', '=', 'schedule_detail.student_id')
                        ->where('schedule_detail.schedule_id', $scheduleId);
                })
                ->whereIn('users.id', $studentUserIds)
                ->where('users.utype', 'STUDENT')
                ->orderBy('users.full_name')
                ->get();
        }

        // dd($studentsWithDetails);/

        return view('schedules.students', compact('scheduleData', 'students'));
    }
}
