<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Homework;
use App\Models\ScheduleDetail;
use App\Models\Schedules;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{
    public function schedules()
    {
        // Lấy ID của user hiện tại đang đăng nhập
        $teacherId = Auth::user()->id;

        // Tăng giới hạn GROUP_CONCAT
        DB::statement('SET SESSION group_concat_max_len = 1000000');

        // Lấy lịch học và thông tin liên quan
        $query = DB::table('schedules')
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
            );

        // Lọc theo teacher_id của user hiện tại
        $query->where('schedules.teacher_id', $teacherId);

        // Thực hiện query
        $teacherSchedules = $query
            ->groupBy(
                'schedules.id',
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

        // Đếm học sinh riêng và thêm STT
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

        // Debug: Kiểm tra số lượng lịch học của giáo viên hiện tại
        $schedulesCount = DB::table('schedules')->where('teacher_id', $teacherId)->count();

        Log::info('TeacherSchedule: schedules count for current teacher', [
            'teacher_id' => $teacherId,
            'count' => $schedulesCount
        ]);
        // return view('user.student.registered-class', compact('registeredClass'));
        return view('user.teacher.schedules', compact('teacherSchedules', 'teacherId'));
    }

    // Sửa ở dưới ***
    //Bộ Lọc 

    public function filter(Request $request)
    {
        $idTeacher = Auth::user()->id;
        $schedules = $this->getFilteredsSchedules($request, $idTeacher);
        $schedules->appends($request->all());
        return response()->json([
            'schedules' => $schedules,
            'pagination' => $schedules->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredsSchedules(Request $request, $teacherId)
    {
        // Lấy lịch học và thông tin liên quan
        $query = DB::table('schedules')
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
            );

        // Lọc theo teacher_id của user hiện tại
        $query->where('schedules.teacher_id', $teacherId);

        // Filter theo ngày tạo lớp học
        if ($request->filled('from_date')) {
            $query->where('schedules.start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('schedules.start_date', '<=', $request->to_date);
        }

        // Thực hiện query với pagination
        $perPage = $request->get('limit', 15);
        $page = $request->get('page', 1);

        // Thực hiện query
        $teacherSchedules = $query
            ->groupBy(
                'schedules.id',
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
            ->paginate($perPage, ['*'], 'page', $page);

        // Xử lý từng item trong paginated result
        $teacherSchedules->getCollection()->transform(function ($schedule, $index) use ($teacherSchedules) {
            // Đếm học sinh từ cả student_classes và schedule_detail
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

            // Tính STT dựa trên vị trí thực tế trong pagination
            $currentPage = $teacherSchedules->currentPage();
            $perPage = $teacherSchedules->perPage();
            $schedule->stt = (($currentPage - 1) * $perPage) + $index + 1;

            return $schedule;
        });

        return $teacherSchedules;
    }

    public function classes(Request $request)
    {
        // Lấy ID của user hiện tại đang đăng nhập
        $teacherId = Auth::user()->id;

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
            ->leftJoin('students AS t2', 'schedules.assistant_teacher_id', '=', 't2.user_id')
            // Lọc theo teacher_id của user hiện tại
            ->where('classes.teacher_id', $teacherId);

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

        // Paginate the results
        $classes = $query->orderBy('classes.id')->paginate(15);

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

        // bạn có thể bỏ đi nếu bạn không cần giữ lại các tham số.
        $classes->appends($request->all());

        // Data cho view
        $statuses = ['active', 'inactive', 'completed'];
        $learningFormats = ['online', 'offline', 'hybrid'];

        return view('user.teacher.class', compact('classes', 'statuses', 'learningFormats'));
    }

    public function showSchedules($scheduleId)
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
                'teachers.mobile',
                // 'assistant_teachers.name as assistant_teacher_name',
                'classes.class_name'
            )
            ->first();

        if (!$scheduleData) {
            abort(404); // Hoặc xử lý trường hợp không tìm thấy lịch học
        }

        // Lấy danh sách bài học của lịch học
        $lessonIds = DB::table('schedule_lessons')
            ->where('schedule_id', $scheduleId)
            ->pluck('lesson_id')
            ->toArray();

        $lessons = DB::table('lessons')
            ->whereIn('id', $lessonIds)
            ->get();

        // Lấy thông tin lớp học (nếu chưa có trong $scheduleData)
        if (!isset($scheduleData->class_id)) {
            $class = null;
            $classStudents = collect();
            $studentUserIds = [];
        } else {
            $class = DB::table('classes')
                ->where('id', $scheduleData->class_id)
                ->first();

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

            // Lấy thông tin học sinh từ bảng users
            $students = DB::table('users')
                ->whereIn('id', $studentUserIds)
                ->get();
        }

        // Lấy trạng thái điểm danh của học sinh cho lịch học này
        $attendance = DB::table('schedule_detail')
            ->where('schedule_id', $scheduleId)
            ->get()
            ->keyBy('student_id')
            ->map(function ($item) {
                return [
                    'status' => $item->attendance_status,
                    'note' => $item->notes,
                ];
            });

        return view('user.teacher.schedules-detail', compact('scheduleData', 'lessons', 'students', 'attendance'));
    }

    public function save(Request $request, $scheduleId)
    {

        // dd();
        Log::info('Starting save method', ['schedule_id' => $scheduleId]);

        // Tìm schedule thủ công thay vì inject trực tiếp
        $schedule = Schedules::find($scheduleId);
        if (!$schedule) {
            Log::error('Schedule not found', ['schedule_id' => $scheduleId]);
            return response()->json([
                'success' => false,
                'message' => 'Lịch học không tồn tại.'
            ], 404);
        }
        Log::info('Schedule found', ['schedule' => $schedule->toArray()]);
        Log::info('Request data:', $request->all());
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:0,1',
            'note.*' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Kiểm tra student_id trước khi lưu
            $attendance = $request->input('attendance', []);
            $notes = $request->input('note', []);
            $studentIds = array_keys($attendance);
            $existingStudents = DB::table('users')->whereIn('id', $studentIds)->pluck('id')->toArray();
            $invalidStudents = array_diff($studentIds, $existingStudents);

            if (!empty($invalidStudents)) {
                Log::error('Invalid student IDs', ['invalid_ids' => $invalidStudents]);
                throw new \Exception('Một số học sinh không tồn tại: ' . implode(', ', $invalidStudents));
            }

            // Kiểm tra xem đây có phải lần điểm danh đầu tiên không
            $isFirstTime = !ScheduleDetail::where('schedule_id', $schedule->id)->exists();

            if ($isFirstTime) {
                // Lần đầu tiên điểm danh - chỉ cần tăng attended_sessions cho học sinh có mặt
                foreach ($attendance as $studentId => $data) {
                    ScheduleDetail::create([
                        'schedule_id' => $schedule->id,
                        'student_id' => $studentId,
                        'attendance_status' => $data['status'],
                        'attendance_date' => now(),
                        'notes' => $notes[$studentId] ?? null,
                    ]);

                    // Nếu học sinh có mặt, tăng attended_sessions
                    // remaining_sessions sẽ được tính: total_sessions - attended_sessions
                    if ($data['status'] == 1) {
                        DB::table('students')
                            ->where('user_id', $studentId)
                            ->increment('attended_sessions');

                        Log::info("Increased attended_sessions for student {$studentId}");
                    }
                }

                Log::info('First time attendance saved');
            } else {
                // Đã có điểm danh trước đó - cần so sánh và cập nhật
                $oldAttendance = ScheduleDetail::where('schedule_id', $schedule->id)
                    ->pluck('attendance_status', 'student_id')
                    ->toArray();

                // Xóa dữ liệu cũ
                ScheduleDetail::where('schedule_id', $schedule->id)->delete();

                // Tạo dữ liệu mới và cập nhật attended_sessions
                foreach ($attendance as $studentId => $data) {
                    ScheduleDetail::create([
                        'schedule_id' => $schedule->id,
                        'student_id' => $studentId,
                        'attendance_status' => $data['status'],
                        'attendance_date' => now(),
                        'notes' => $notes[$studentId] ?? null,
                    ]);

                    $oldStatus = $oldAttendance[$studentId] ?? 0;
                    $newStatus = $data['status'];

                    // Chỉ cập nhật khi có thay đổi
                    if ($oldStatus != $newStatus) {
                        if ($newStatus == 1 && $oldStatus == 0) {
                            // Từ vắng mặt → có mặt: tăng attended_sessions, giảm remaining_sessions
                            DB::table('students')
                                ->where('user_id', $studentId)
                                ->increment('attended_sessions');

                            DB::table('students')
                                ->where('user_id', $studentId)
                                ->where('remaining_sessions', '>', 0)
                                ->decrement('remaining_sessions');

                            Log::info("Increased attended_sessions and decreased remaining_sessions for student {$studentId}");
                        } elseif ($newStatus == 0 && $oldStatus == 1) {
                            // Từ có mặt → vắng mặt: giảm attended_sessions, tăng remaining_sessions
                            DB::table('students')
                                ->where('user_id', $studentId)
                                ->where('attended_sessions', '>', 0)
                                ->decrement('attended_sessions');

                            DB::table('students')
                                ->where('user_id', $studentId)
                                ->increment('remaining_sessions');

                            Log::info("Decreased attended_sessions and increased remaining_sessions for student {$studentId}");
                        }
                    }
                }

                Log::info('Updated attendance saved');
            }

            // Cập nhật remaining_sessions với error handling chi tiết
            if (!empty($studentIds)) {
                Log::info('Starting to update remaining_sessions', ['student_ids' => $studentIds]);

                foreach ($studentIds as $studentId) {
                    try {
                        Log::info("Processing student ID: {$studentId}");

                        // Kiểm tra student có tồn tại không
                        $student = DB::table('students')->where('user_id', $studentId)->first();

                        if (!$student) {
                            Log::warning("Student not found in students table", ['user_id' => $studentId]);
                            continue;
                        }

                        Log::info("Found student", [
                            'user_id' => $studentId,
                            'registered_sessions' => $student->registered_sessions ?? 'NULL',
                            'attended_sessions' => $student->attended_sessions ?? 'NULL'
                        ]);

                        // Tính toán remaining_sessions
                        $totalSessions = (int)($student->registered_sessions ?? 0);
                        $attendedSessions = (int)($student->attended_sessions ?? 0);
                        $remaining = max(0, $totalSessions - $attendedSessions);

                        // Cập nhật
                        $updated = DB::table('students')
                            ->where('user_id', $studentId)
                            ->update(['remaining_sessions' => $remaining]);

                        Log::info("Updated student {$studentId}", [
                            'remaining_sessions' => $remaining,
                            'rows_affected' => $updated
                        ]);
                    } catch (\Exception $e) {
                        Log::error("Error updating student {$studentId}", [
                            'error' => $e->getMessage(),
                            'line' => $e->getLine()
                        ]);
                    }
                }

                Log::info('Finished updating remaining_sessions');
            }

            DB::commit();

            // Trả về phản hồi JSON cho AJAX
            return response()->json([
                'success' => true,
                'message' => 'Điểm danh thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu điểm danh: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $scheduleId = $request->input('scheduleId');
        // Lấy thông tin lớp học hiện tại
        // $class = Classes::findOrFail($classId);

        // Lấy danh sách giáo viên
        $students = DB::table('students')
            ->select('students.user_id', 'users.full_name')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('users.utype', 'STUDENT')
            ->get();

        return response()->json([
            'scheduleId' => $scheduleId,
            'students' => $students, // Danh sách tất cả giáo viên
            'message' => 'Đã nhận scheduleId thành công'
        ]);
    }

    public function saveStudent(Request $request)
    {
        try {
            Log::info('Request input:', $request->all());
            $data = $request->validate([
                'scheduleId' => 'required|integer|exists:schedules,id',
                'student_id' => 'required|integer|exists:students,user_id'
                // 'note' => 'nullable|string|max:255'
            ]);

            // Kiểm tra xem student_id và class_id đã tồn tại trong student_classes chưa
            $exists = DB::table('schedule_detail')
                ->where('schedule_id', $data['scheduleId'])
                ->where('student_id', $data['student_id'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Học sinh này đã tồn tại trong lịch học này.'
                ], 422);
            }

            // Lấy attendance_date từ bảng schedules
            $schedule = DB::table('schedules')
                ->where('id', $data['scheduleId'])
                ->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch học.'
                ], 404);
            }

            // Giả sử cột lưu ngày trong bảng schedules là 'date'
            $attendanceDate = $schedule->start_date;

            DB::table('schedule_detail')->insert([
                'schedule_id' => $data['scheduleId'],
                'student_id' => $data['student_id'],
                'attendance_status' => 0,
                'notes' => $data['note'] ?? null,
                'attendance_date' => $attendanceDate,
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

    public function lessonDetail($lessonId)
    {
        $lesson = DB::table('lessons')->where('id', $lessonId)->first();
        // dd($lesson);
        if (!$lesson) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy bài học'], 404);
        }
        return response()->json(['success' => true, 'lesson' => $lesson]);
    }

    
public function homeworks(Request $request) 
{
    // Log thông tin request để debug
    Log::info('Homework creation request', [
        'user_id' => Auth::id(),
        'request_data' => $request->except(['assignment_file']),
        'file_info' => $request->hasFile('assignment_file') ? [
            'original_name' => $request->file('assignment_file')->getClientOriginalName(),
            'size' => $request->file('assignment_file')->getSize(),
            'mime_type' => $request->file('assignment_file')->getMimeType()
        ] : 'No file uploaded'
    ]);

    try {
        // 1. Check authentication
        if (!Auth::check()) {
            Log::error('Homework creation failed: User not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện chức năng này',
                'error_type' => 'authentication_error'
            ], 401);
        }

        // 2. Check if schedule_id exists
        if (!$request->has('schedule_id') || empty($request->schedule_id)) {
            Log::error('Homework creation failed: Missing schedule_id', [
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin buổi học (schedule_id)',
                'error_type' => 'missing_schedule_id'
            ], 400);
        }

        // 3. Validate schedule exists and belongs to teacher
        $scheduleExists = DB::table('schedules')
            ->where('id', $request->schedule_id)
            ->where('teacher_id', Auth::id()) // Assuming you have teacher_id in schedules table
            ->exists();

        if (!$scheduleExists) {
            Log::error('Homework creation failed: Invalid schedule_id', [
                'schedule_id' => $request->schedule_id,
                'teacher_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Buổi học không tồn tại hoặc bạn không có quyền giao bài tập cho buổi học này',
                'error_type' => 'invalid_schedule'
            ], 403);
        }

        // 4. Check if homework already exists for this schedule
        $existingHomework = Homework::where('schedule_session_id', $request->schedule_id)->first();
        if ($existingHomework) {
            Log::warning('Homework creation failed: Homework already exists', [
                'schedule_id' => $request->schedule_id,
                'existing_homework_id' => $existingHomework->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Buổi học này đã có bài tập được giao',
                'error_type' => 'homework_exists',
                'existing_homework' => [
                    'id' => $existingHomework->id,
                    'created_at' => $existingHomework->created_at->format('d/m/Y H:i')
                ]
            ], 409);
        }

        // 5. Validate dữ liệu với thông báo lỗi chi tiết
        $validator = Validator::make($request->all(), [
            'assignment_file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar,jpg,jpeg,png|max:51200',
            'deadline' => 'required|date|after:now',
            'description' => 'nullable|string|max:2000'
        ], [
            'assignment_file.required' => 'Vui lòng chọn file bài tập',
            'assignment_file.file' => 'File không hợp lệ',
            'assignment_file.mimes' => 'File phải có định dạng: pdf, doc, docx, ppt, pptx, txt, zip, rar, jpg, jpeg, png',
            'assignment_file.max' => 'File không được vượt quá 50MB',
            'deadline.required' => 'Vui lòng chọn hạn nộp bài',
            'deadline.date' => 'Hạn nộp bài không hợp lệ',
            'deadline.after' => 'Hạn nộp bài phải sau thời điểm hiện tại',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự'
        ]);

        if ($validator->fails()) {
            Log::error('Homework validation failed', [
                'errors' => $validator->errors()->toArray(),
                'request_data' => $request->except(['assignment_file'])
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'error_type' => 'validation_error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 6. Check file upload errors
        $file = $request->file('assignment_file');
        if (!$file) {
            Log::error('Homework creation failed: No file received');
            return response()->json([
                'success' => false,
                'message' => 'Không nhận được file upload',
                'error_type' => 'file_upload_error'
            ], 400);
        }

        if (!$file->isValid()) {
            Log::error('Homework creation failed: Invalid file upload', [
                'file_error' => $file->getError(),
                'file_error_message' => $file->getErrorMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'File upload không hợp lệ: ' . $file->getErrorMessage(),
                'error_type' => 'file_invalid',
                'php_error_code' => $file->getError()
            ], 400);
        }

        // 7. Check file size manually (additional check)
        $maxFileSize = 52428800; // 50MB in bytes
        if ($file->getSize() > $maxFileSize) {
            Log::error('Homework creation failed: File too large', [
                'file_size' => $file->getSize(),
                'max_size' => $maxFileSize,
                'file_name' => $file->getClientOriginalName()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'File quá lớn. Kích thước hiện tại: ' . round($file->getSize()/1024/1024, 2) . 'MB. Tối đa cho phép: 50MB',
                'error_type' => 'file_too_large',
                'current_size_mb' => round($file->getSize()/1024/1024, 2),
                'max_size_mb' => 50
            ], 400);
        }

        // 8. Create upload directory with proper error handling
        $uploadPath = public_path('file/homeworks');
        try {
            if (!file_exists($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    throw new \Exception('Cannot create upload directory');
                }
                Log::info('Created upload directory', ['path' => $uploadPath]);
            }

            // Check directory permissions
            if (!is_writable($uploadPath)) {
                throw new \Exception('Upload directory is not writable');
            }
        } catch (\Exception $e) {
            Log::error('Homework creation failed: Directory creation error', [
                'path' => $uploadPath,
                'error' => $e->getMessage(),
                'permissions' => substr(sprintf('%o', fileperms(dirname($uploadPath))), -4)
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo thư mục upload: ' . $e->getMessage(),
                'error_type' => 'directory_error',
                'upload_path' => $uploadPath
            ], 500);
        }

        // 9. Generate unique filename with collision check
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        
        $counter = 0;
        do {
            $fileName = time() . '_' . uniqid() . ($counter > 0 ? '_' . $counter : '') . '_' . $baseName . '.' . $extension;
            $fullPath = $uploadPath . '/' . $fileName;
            $counter++;
        } while (file_exists($fullPath) && $counter < 100);

        if (file_exists($fullPath)) {
            Log::error('Homework creation failed: Cannot generate unique filename', [
                'attempted_filename' => $fileName,
                'counter' => $counter
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo tên file duy nhất',
                'error_type' => 'filename_generation_error'
            ], 500);
        }

        // 10. Move file with error handling
        try {
            if (!$file->move($uploadPath, $fileName)) {
                throw new \Exception('Failed to move uploaded file');
            }
            
            // Verify file was actually moved
            if (!file_exists($fullPath)) {
                throw new \Exception('File was not found after move operation');
            }
            
            Log::info('File uploaded successfully', [
                'original_name' => $originalName,
                'saved_name' => $fileName,
                'file_size' => filesize($fullPath),
                'path' => $fullPath
            ]);
            
        } catch (\Exception $e) {
            Log::error('Homework creation failed: File move error', [
                'source' => $file->getPathname(),
                'destination' => $fullPath,
                'error' => $e->getMessage(),
                'disk_free_space' => disk_free_space($uploadPath)
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lưu file: ' . $e->getMessage(),
                'error_type' => 'file_move_error',
                'disk_space_available' => round(disk_free_space($uploadPath)/1024/1024, 2) . 'MB'
            ], 500);
        }

        $filePath = 'file/homeworks/' . $fileName;
        $teacherId = Auth::id();

        // 11. Database transaction for data integrity
        DB::beginTransaction();
        try {
            $homework = Homework::create([
                'description' => $request->description,
                'attachment_path' => $filePath,
                'deadline' => Carbon::parse($request->deadline),
                'schedule_session_id' => $request->schedule_id,
                'created_by' => $teacherId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (!$homework) {
                throw new \Exception('Failed to create homework record');
            }

            DB::commit();
            
            Log::info('Homework created successfully', [
                'homework_id' => $homework->id,
                'schedule_id' => $request->schedule_id,
                'teacher_id' => $teacherId,
                'file_path' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Giao bài tập thành công!',
                'data' => [
                    'id' => $homework->id,
                    'description' => $homework->description,
                    'deadline' => $homework->deadline->format('d/m/Y H:i'),
                    'file_url' => asset($homework->attachment_path),
                    'file_name' => $originalName,
                    'file_size' => round(filesize($fullPath)/1024/1024, 2) . 'MB',
                    'created_at' => $homework->created_at->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if database operation failed
            if (file_exists($fullPath)) {
                unlink($fullPath);
                Log::info('Cleaned up uploaded file due to database error', ['file' => $fullPath]);
            }
            
            Log::error('Homework creation failed: Database error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi database: ' . $e->getMessage(),
                'error_type' => 'database_error'
            ], 500);
        }

    } catch (\Exception $e) {
        Log::error('Homework creation failed: Unexpected error', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi không mong muốn xảy ra: ' . $e->getMessage(),
            'error_type' => 'unexpected_error',
            'debug_info' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ], 500);
    }
}

    // Bổ sung sau
    /**
     * Lấy danh sách bài tập
     */
    public function index()
    {
        try {
            $homeworks = Homework::orderBy('created_at', 'desc')
                ->with(['user', 'schedule']) // Load relationship với schedule
                ->get()
                ->map(function ($homework) {
                    return [
                        'id' => $homework->id,
                        'title' => $homework->title,
                        'description' => $homework->description,
                        'file_name' => $homework->file_name,
                        'file_url' => asset($homework->file_path),
                        'deadline' => $homework->deadline->format('d/m/Y H:i'),
                        'status' => $homework->status,
                        'is_expired' => $homework->deadline->isPast(),
                        'created_at' => $homework->created_at->format('d/m/Y H:i'),
                        'user_name' => $homework->user->name ?? 'N/A',
                        'schedule_info' => [
                            'id' => $homework->schedule->id ?? null,
                            'subject' => $homework->schedule->subject ?? 'N/A',
                            'class_name' => $homework->schedule->class_name ?? 'N/A',
                            'teacher' => $homework->schedule->teacher ?? 'N/A'
                        ]
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $homeworks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách bài tập theo schedule_id
     */
    public function getBySchedule($scheduleId)
    {
        try {
            $homeworks = Homework::where('schedule_id', $scheduleId)
                ->orderBy('created_at', 'desc')
                ->with(['user', 'schedule'])
                ->get()
                ->map(function ($homework) {
                    return [
                        'id' => $homework->id,
                        'title' => $homework->title,
                        'description' => $homework->description,
                        'file_name' => $homework->file_name,
                        'file_url' => asset($homework->file_path),
                        'deadline' => $homework->deadline->format('d/m/Y H:i'),
                        'status' => $homework->status,
                        'is_expired' => $homework->deadline->isPast(),
                        'created_at' => $homework->created_at->format('d/m/Y H:i'),
                        'user_name' => $homework->user->name ?? 'N/A'
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $homeworks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết bài tập
     */
    public function show($id)
    {
        try {
            $homework = Homework::with(['user', 'schedule'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $homework->id,
                    'title' => $homework->title,
                    'description' => $homework->description,
                    'file_name' => $homework->file_name,
                    'file_url' => asset($homework->file_path),
                    'deadline' => $homework->deadline->format('d/m/Y H:i'),
                    'status' => $homework->status,
                    'is_expired' => $homework->deadline->isPast(),
                    'created_at' => $homework->created_at->format('d/m/Y H:i'),
                    'user_name' => $homework->user->name ?? 'N/A',
                    'schedule_info' => [
                        'id' => $homework->schedule->id ?? null,
                        'subject' => $homework->schedule->subject ?? 'N/A',
                        'class_name' => $homework->schedule->class_name ?? 'N/A',
                        'teacher' => $homework->schedule->teacher ?? 'N/A'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy bài tập'
            ], 404);
        }
    }

    /**
     * Xóa bài tập
     */
    public function destroy($id)
    {
        try {
            $homework = Homework::findOrFail($id);
            
            // Xóa file trong thư mục public
            $filePath = public_path($homework->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Xóa record
            $homework->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa bài tập thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái bài tập (hết hạn)
     */
    public function updateExpiredHomeworks()
    {
        try {
            $expiredCount = Homework::where('deadline', '<', now())
                ->where('status', '!=', 'expired')
                ->update(['status' => 'expired']);
            
            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật {$expiredCount} bài tập hết hạn"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

}
