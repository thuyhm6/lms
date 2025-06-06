<?php


namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\HomeworkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class HomeworkController extends Controller
{
    public function getHomework($scheduleId)
    {
        try {
            $homework = Homework::where('schedule_session_id', $scheduleId)->first();

            if (!$homework) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy bài tập cho buổi học này'
                ], 404);
            }

            // Thêm homework_id vào response để frontend có thể sử dụng
            return response()->json([
                'success' => true,
                'homework_id' => $homework->id, // Thêm dòng này
                'description' => $homework->description,
                'deadline' => $homework->deadline ? Carbon::parse($homework->deadline)->format('d/m/Y H:i') : null,
                'attachment_path' => $homework->attachment_path ? asset($homework->attachment_path) : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }


    
public function submitHomework(Request $request)
{
    try {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'homework_id' => 'required|exists:homeworks,id',
            'submission_note' => 'nullable|string|max:1000',
            'file_path' => 'required|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,txt,zip,rar,jpg,jpeg,png'
        ], [
            'homework_id.required' => 'ID bài tập không được trống',
            'homework_id.exists' => 'Bài tập không tồn tại',
            'file_path.required' => 'Vui lòng chọn file nộp bài',
            'file_path.file' => 'File không hợp lệ',
            'file_path.max' => 'File không được vượt quá 50MB',
            'file_path.mimes' => 'File phải có định dạng: pdf, doc, docx, ppt, pptx, txt, zip, rar, jpg, jpeg, png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if student has already submitted
        $existingSubmission = HomeworkSubmission::where('homework_id', $request->homework_id)
            ->where('student_id', Auth::id())
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã nộp bài tập này rồi'
            ], 400);
        }

        // Upload file
        $file = $request->file('file_path');
        $fileName = time() . '_' . Auth::id() . '_' . $file->getClientOriginalName();
        
        // Create directory if not exists
        $uploadPath = public_path('file/homework_submit');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $file->move($uploadPath, $fileName);

        // Create submission
        HomeworkSubmission::create([
            'homework_id' => $request->homework_id,
            'student_id' => Auth::id(),
            'submission_note' => $request->submission_note,
            'file_path' => 'file/homework_submit/' . $fileName,
            'submitted_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nộp bài tập thành công!'
        ]);

    } catch (\Exception $e) {
        \Log::error('Homework submission error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
        ], 500);
    }
}
}
