<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\User; // Model User cho học sinh
use App\Models\Lesson; // Model Lesson cho bài học
use App\Models\ScheduleDetail;
use App\Models\Schedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class ScheduleDetailController extends Controller
{
    public function show($scheduleId)
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

        return view('schedules.detail', compact('scheduleData', 'lessons', 'students', 'attendance'));
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
}
