<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Lesson;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{

    // Hiển thị trang khóa học đăng kí
    public function registeredCourses()
    {
        // dd(1);
        // Lấy danh sách khóa học đã đăng ký của học sinh
        $id = Auth::user()->id;
        //Kiêm tra xem nếu là học sinh thì lấy danh sách khóa học đã đăng ký của học sinh đó
        //Còn nếu là phụ huyen thì lấy danh sách khóa học đã đăng ký của học sinh con của mình
        if (Auth::user()->utype === 'STUDENT') {
            $registeredCourses = DB::table('student_classes')
                ->join('classes', 'student_classes.class_id', '=', 'classes.id')
                ->join('courses', 'classes.course_id', '=', 'courses.id')
                ->where('student_classes.student_id', $id)
                ->select('classes.id as class_id', 'classes.class_name', 'courses.id as course_id', 'courses.course_code', 'courses.course_name', 'student_classes.status', 'student_classes.notes')
                ->get();
        } else if (Auth::user()->utype === 'PARENT') {

            $registeredCourses = DB::table('student_classes')
                ->join('classes', 'student_classes.class_id', '=', 'classes.id')
                ->join('courses', 'classes.course_id', '=', 'courses.id')
                ->join('students', 'student.user_id', '=', 'student_classes.student_id')
                ->where('students.parent_id', $id)
                ->select('classes.id as class_id', 'classes.class_name', 'courses.id as course_id', 'courses.course_code', 'courses.course_name', 'student_classes.status', 'student_classes.notes')
                ->get();
        }
        return view('user.student.registered-courses', compact('registeredCourses'));
    }



    // Hiển thị trang lớp học đăng kí
    public function registeredClass()
    {
        // Lấy danh sách khóa học đã đăng ký của học sinh
        $id = Auth::user()->id;
        $registeredClass = DB::table('student_classes')
            ->join('classes', 'student_classes.class_id', '=', 'classes.id')
            ->where('student_classes.student_id', $id)
            ->select('classes.id as class_id', 'classes.class_name', 'student_classes.status', 'student_classes.notes')
            ->get();

        // dd($registeredClass->all());
        // Truyền dữ liệu đến view
        return view('user.student.registered-class', compact('registeredClass'));
    }


    public function schedule(Request $request)
    {
        $idStudent = Auth::user()->id;
        $schedules = DB::table('schedules as s')
    ->select(
        's.id as stt',
        's.start_date as ngay_hoc',
        's.start_time as tu_gio',
        's.end_time as den_gio',
        'cr.course_name as khoa_hoc',
        'sb.subject_name as mon_hoc',
        'hw.id as homework_id', // Có thể là null
        DB::raw("GROUP_CONCAT(CONCAT(l.id, '::', l.lesson_name, '::', l.content, '::', l.file_link) ORDER BY l.id SEPARATOR '\n') as bai_giang"),
        'u.full_name as giao_vien'
    )
    ->join('schedule_lessons as sl', 'sl.schedule_id', '=', 's.id')
    ->join('lessons as l', 'l.id', '=', 'sl.lesson_id')
    ->join('subjects as sb', 'l.subject_id', '=', 'sb.id')
    ->join('courses as cr', 'sb.course_id', '=', 'cr.id')
    ->join('classes as c', 'c.id', '=', 's.class_id')
    ->join('student_classes as sc', 'sc.class_id', '=', 'c.id')
    ->join('students as st', 'st.user_id', '=', 'sc.student_id')
    ->join('teachers as t', 't.user_id', '=', 's.teacher_id')
    ->join('users as u', 'u.id', '=', 't.user_id')
    ->leftJoin('homeworks as hw', 'hw.schedule_session_id', '=', 's.id') // Sử dụng LEFT JOIN
    ->where('st.user_id', $idStudent)
    ->groupBy(
        's.id',
        's.start_date',
        's.start_time',
        's.end_time',
        'u.full_name',
        'cr.course_name',
        'sb.subject_name',
        'hw.id' // Thêm hw.id vào GROUP BY
    )
    ->orderBy('s.start_date', 'desc')
    ->paginate(10);
                // dd($schedules);
        
        if ($request->ajax()) {
            return response()->json([
                'schedules' => $schedules,
                'pagination' => $schedules->links('pagination::bootstrap-5')->render()
            ]);
        }
        return view('user.student.schedules', compact('schedules'));
    }

    //Bộ Lọc

    public function filter(Request $request)
    {
        $idStudent = Auth::user()->id;
        $schedules = $this->getFilteredsSchedules($request, $idStudent);
        $schedules->appends($request->all());
        return response()->json([
            'schedules' => $schedules,
            'pagination' => $schedules->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredsSchedules(Request $request, $id)
    {
        $query = DB::table('schedules as s')->select(
            's.id as stt',
            's.start_date as ngay_hoc',
            's.start_time as tu_gio',
            's.end_time as den_gio',
            'cr.course_name as khoa_hoc',
            'sb.subject_name as mon_hoc',
            DB::raw('GROUP_CONCAT(l.lesson_name ORDER BY l.id SEPARATOR "\n") as bai_giang'),
            'u.full_name as giao_vien'
        )
            ->join('schedule_lessons as sl', 'sl.schedule_id', '=', 's.id')
            ->join('lessons as l', 'l.id', '=', 'sl.lesson_id')
            ->join('subjects as sb', 'l.subject_id', '=', 'sb.id')
            ->join('courses as cr', 'sb.course_id', '=', 'cr.id')
            ->join('classes as c', 'c.id', '=', 's.class_id')
            ->join('student_classes as sc', 'sc.class_id', '=', 'c.id')
            ->join('students as st', 'st.user_id', '=', 'sc.student_id')
            ->join('teachers as t', 't.user_id', '=', 's.teacher_id')
            ->join('users as u', 'u.id', '=', 't.user_id')
            ->where('st.user_id', $id)  // ← truyền biến học sinh
            ->groupBy(
                's.id',
                's.start_date',
                's.start_time',
                's.end_time',
                'u.full_name',
                'cr.course_name',
                'sb.subject_name'
            )
            ->orderBy('s.start_date', 'desc');

        if ($request->filled('from_date')) {
            $query->where('s.start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('s.start_date', '<=', $request->to_date);
        }

        return $query->paginate($request->limit);
    }








    public function store(Request $request)
    {
        $classId = $request->input('classId');
        // Lấy thông tin lớp học hiện tại
        $class = Classes::findOrFail($classId);

        // Lấy danh sách giáo viên
        $students = DB::table('students')
            ->select('students.user_id', 'users.full_name')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('users.utype', 'STUDENT')
            ->get();

        return response()->json([
            'class' => $class,
            'students' => $students, // Danh sách tất cả giáo viên

            'message' => 'Đã nhận classId thành công'
        ]);
    }

    public function studentStore(Request $request)
    {
        try {
            Log::info('Request input:', $request->all());
            $data = $request->validate([
                'classId' => 'required|integer|exists:classes,id',
                'student_id' => 'required|integer|exists:students,user_id'
                // 'note' => 'nullable|string|max:255'
            ]);

            // Kiểm tra xem student_id và class_id đã tồn tại trong student_classes chưa
            $exists = DB::table('student_classes')
                ->where('student_id', $data['student_id'])
                ->where('class_id', $data['classId'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Học sinh này đã được đăng ký vào lớp học này.'
                ], 422);
            }

            DB::table('student_classes')->insert([
                'student_id' => $data['student_id'],
                'class_id' => $data['classId'],
                'status' => 'active',
                'notes' => $data['note'] ?? null,
                'enrollment_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
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




    public function showLession($id){
        $lesson = Lesson::where('id', $id)->get();
        // dd($lesson);
        return view('user.student.lessons', compact('lesson'));
    }





    // public function showHomework(){
    //     $homework = Homework::all();
    //     return view('user.student.homework', compact('homework'));
    // }
}
