<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use ZipArchive;

class lessonsController extends Controller
{
    //

    public function index(Request $request, $id)
    {
        $subjectsDetail = Subject::find($id);
        $lessons = Lesson::where('subject_id', $id)->with('creator')->orderByDesc('created_at')->paginate(10);
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'lessons' => $lessons,
                'pagination' => $lessons->links('pagination::bootstrap-5')->render()
            ]);
        }
        return view('admin.lessons.lessons-list', compact('lessons', 'subjectsDetail'));
    }


    public function filter(Request $request, $id)
    {
        $lessons = $this->getFilteredLessons($request, $id);
        $lessons->appends($request->all());
        return response()->json([
            'lessons' => $lessons,
            'pagination' => $lessons->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredLessons(Request $request, $id)
    {
        $query = Lesson::query()->where('subject_id', $id);

        if ($request->filled('topic')) {
            $query->where('topic', $request->topic);
        }

        if ($request->filled('fee_type')) {
            $query->where('fee_type', $request->fee_type);
        }

        if ($request->filled('file_type')) {
            $query->where('file_type', $request->file_type);
        }

        if ($request->filled('keyword')) {
            $query->where('lesson_name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('limit')) {
            $query->where('lesson_name', 'like', '%' . $request->keyword . '%');
        }

        return $query->with('creator')->orderByDesc('created_at')->paginate($request->limit);
    }



    public function create($id)
    {
        $subjectsDetail = Subject::find($id);
        // dd($subjectsDetail);
        return view('admin.lessons.lessons-add', compact('subjectsDetail'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'topic' => 'required|string|max:255',
            'lesson_name' => 'required|string|max:255',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'nullable|integer|exists:teachers,user_id',
            'type' => 'required|in:Bài giảng,Khóa học,Workshop,Webinar,Hội thảo',
            'fee_type' => 'required|in:Online,Offline',
            'file_type' => 'required|in:iSpring,Video',
            'duration' => 'required',
            'content' => 'required|string|max:255',
            'file_link' => 'nullable|file|max:100000',
        ], [
            'topic.required' => 'Vui lòng nhập chủ đề.',
            'topic.string' => 'Chủ đề phải là chuỗi.',
            'topic.max' => 'Chủ đề không được vượt quá 255 ký tự.',

            'lesson_name.required' => 'Vui lòng nhập tên bài giảng.',
            'lesson_name.string' => 'Tên bài giảng phải là chuỗi.',
            'lesson_name.max' => 'Tên bài giảng không được vượt quá 255 ký tự.',

            'subject_id.required' => 'Vui lòng chọn môn học.',
            'subject_id.integer' => 'Mã môn học không hợp lệ.',
            'subject_id.exists' => 'Môn học không tồn tại.',

            'teacher_id.integer' => 'Mã giáo viên không hợp lệ.',
            'teacher_id.exists' => 'Giáo viên không tồn tại.',

            'type.required' => 'Vui lòng chọn loại bài giảng.',
            'type.in' => 'Loại bài giảng không hợp lệ.',

            'fee_type.required' => 'Vui lòng chọn hình thức học phí.',
            'fee_type.in' => 'Hình thức học phí không hợp lệ.',

            'file_type.required' => 'Vui lòng chọn dạng file.',
            'file_type.in' => 'Dạng file không hợp lệ.',

            'duration.required' => 'Vui lòng nhập thời lượng.',

            'content.required' => 'Vui lòng nhập nội dung.',
            'content.string' => 'Nội dung phải là chuỗi.',
            'content.max' => 'Nội dung không được vượt quá 255 ký tự.',

            'file_link.file' => 'Tập tin tải lên phải là một tệp.',
            'file_link.max' => 'Kích thước tập tin không được vượt quá 100MB.',
        ]);


        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('file_link')) {
            $file = $request->file('file_link');

            if ($file->isValid()) {
                $uploadDir = public_path('file/lessons/');
                $fileName = time() . "-" . $file->getClientOriginalName();
                $name = $file->getClientOriginalName();
                $zipPath = $uploadDir . $fileName;
                $extractPath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME);



                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $file->move($uploadDir, $fileName);

                $zip = new \ZipArchive;
                if ($zip->open($zipPath) === TRUE) {
                    $zip->extractTo($extractPath);
                    $zip->close();

                    // ✅ Xóa file zip sau khi giải nén thành công
                    if (file_exists($zipPath)) {
                        unlink($zipPath);
                    }

                    // Optional: lưu đường dẫn
                    $data['file_link'] = 'file/lessons/' . pathinfo($fileName, PATHINFO_FILENAME) . '/' . pathinfo($name, PATHINFO_FILENAME);
                } else {
                    return back()->withErrors(['file_link' => 'Không thể mở file ZIP.']);
                }
            } else {
                return back()->withErrors(['file_link' => 'File tải lên không hợp lệ.']);
            }
        }
        // dd($data);

        Lesson::create($data);

        return redirect()->route('admin.lessons', ['id' => $request->subject_id])
            ->with('status', 'Bài giảng đã được thêm!');
    }



    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lessons.lessons-edit', compact('lesson'));
    }





    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $data = $request->validate([
            'topic' => 'nullable|string|max:255',
            'lesson_name' => 'required|string|max:255',
            'subject_id' => 'required|integer|exists:subjects,id',
            'teacher_id' => 'nullable|integer|exists:teachers,user_id',
            'type' => 'required|in:Bài giảng,Khóa học,Workshop,Webinar,Hội thảo',
            'fee_type' => 'required|in:Online,Offline',
            'file_type' => 'required|in:iSpring,Video',
            'duration' => 'required',
            'content' => 'nullable|string|max:255',
            'file_link' => 'nullable|file|max:100000',
        ], [
            'lesson_name.required' => 'Vui lòng nhập tên bài giảng.',
            'lesson_name.max' => 'Tên bài giảng không được vượt quá 255 ký tự.',
            'subject_id.required' => 'Vui lòng chọn môn học.',
            'subject_id.exists' => 'Môn học không tồn tại.',
            'teacher_id.exists' => 'Giáo viên không tồn tại.',
            'type.required' => 'Vui lòng chọn loại bài giảng.',
            'type.in' => 'Loại bài giảng không hợp lệ.',
            'fee_type.required' => 'Vui lòng chọn hình thức học phí.',
            'fee_type.in' => 'Hình thức học phí không hợp lệ.',
            'file_type.required' => 'Vui lòng chọn dạng file.',
            'file_type.in' => 'Dạng file không hợp lệ.',
            'duration.required' => 'Vui lòng nhập thời lượng.',
            'content.max' => 'Nội dung không được vượt quá 255 ký tự.',
            'file_link.file' => 'Tập tin tải lên phải là một tệp.',
            'file_link.max' => 'Kích thước tập tin không được vượt quá 100MB.',
        ]);

        $data['updated_by'] = Auth::id();

        if ($request->hasFile('file_link')) {
            $file = $request->file('file_link');

            if ($file->isValid()) {
                $uploadDir = public_path('file/lessons/');
                $fileName = time() . "-" . $file->getClientOriginalName();
                $name = $file->getClientOriginalName();
                $zipPath = $uploadDir . $fileName;
                $extractPath = $uploadDir . pathinfo($fileName, PATHINFO_FILENAME);



                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $file->move($uploadDir, $fileName);

                $zip = new \ZipArchive;
                if ($zip->open($zipPath) === TRUE) {
                    $zip->extractTo($extractPath);
                    $zip->close();

                    // ✅ Xóa file zip sau khi giải nén thành công
                    if (file_exists($zipPath)) {
                        unlink($zipPath);
                    }

                    // Optional: lưu đường dẫn
                    $data['file_link'] = 'file/lessons/' . pathinfo($fileName, PATHINFO_FILENAME) . '/' . pathinfo($name, PATHINFO_FILENAME);
                } else {
                    return back()->withErrors(['file_link' => 'Không thể mở file ZIP.']);
                }
            } else {
                return back()->withErrors(['file_link' => 'File tải lên không hợp lệ.']);
            }
        }else{
            $data['file_link'] = $lesson->file_link;
        }


        // dd($data);
        $lesson->update($data);

        return redirect()->route('admin.lessons', ['id' => $lesson->subject_id])
            ->with('status', 'Bài giảng đã được cập nhật!');
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);

        $lesson->delete();

        return redirect()->back()->with('status', 'Bài giảng đã được xóa!');
    }
}
