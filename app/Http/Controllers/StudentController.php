<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
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
}
