<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class subjectsController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->has('subject_name')) {
            $query->where('subject_name', 'like', '%' . $request->subject_name . '%');
        }

        $subjects = $query->with('creator','teachers')->orderByDesc('created_at')->paginate(10);
        $courses = Course::all();

        if ($request->ajax()) {
            return response()->json([
                'subjects' => $subjects,
                'pagination' => $subjects->links('pagination::bootstrap-5')->render()
            ]);
        }
        return view('admin.subjects.subjects-list', compact('subjects', 'courses'));
    }


    public function filter(Request $request)
    {
        $subjects = $this->getFilteredsubject($request);
        $subjects->appends($request->all());
        return response()->json([
            'subjects' => $subjects,
            'pagination' => $subjects->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredSubject(Request $request)
    {
        $query = Subject::query();

        // Lọc theo ID khóa học
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Lọc theo trạng thái xuất bản
        if ($request->filled('publish_status')) {
            $query->where('publish_status', $request->publish_status);
        }

        // Lọc theo từ khóa (tên môn học)
        if ($request->filled('keyword')) {
            $query->where('subject_name', 'like', '%' . $request->keyword . '%');
        }

        return $query->with('creator','teachers')->orderByDesc('created_at')->paginate($request->limit);
    }


    public function create()
    {
        $courses = Course::all();
        $teachers = DB::table('teachers')->join('users', 'teachers.user_id', '=', 'users.id')->get();
    
        // dd($teachers);
        return view('admin.subjects.subjects-add', compact('courses', 'teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_name' => 'required|string|max:255',
            'teacher_permission' => 'nullable|integer|exists:users,id',
            'publish_status' => 'nullable|boolean',
            'course_id' => 'required|integer|exists:courses,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'subject_name.required' => 'Vui lòng nhập tên môn học.',
            'subject_name.string' => 'Tên môn học phải là chuỗi.',
            'subject_name.max' => 'Tên môn học không được vượt quá 255 ký tự.',

            'teacher_permission.integer' => 'Giá trị phân quyền phải là số nguyên.',
            'teacher_permission.exists' => 'Giáo viên không tồn tại trong hệ thống.',

            'publish_status.boolean' => 'Trạng thái xuất bản chỉ được là 0 hoặc 1.',

            'course_id.required' => 'Vui lòng chọn khóa học.',
            'course_id.integer' => 'ID khóa học phải là số.',
            'course_id.exists' => 'Khóa học không tồn tại trong hệ thống.',

            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
        ]);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->subject_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/subjects/'), $filename);
            $data['image'] = 'images/subjects/' . $filename;
        }

        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        \App\Models\Subject::create($data);

        return redirect()->route('admin.subjects')->with('status', 'Môn học đã được thêm!');
    }


    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $courses = Course::all();
        $teachers = DB::table('teachers')->join('users', 'teachers.user_id', '=', 'users.id')->get();
        return view('admin.subjects.subjects-edit', compact('subject', 'teachers', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);

        $data = $request->validate([
            'subject_name' => 'required|string|max:255',
            'teacher_permission' => 'nullable|integer|exists:users,id',
            'publish_status' => 'nullable|boolean',
            'course_id' => 'required|integer|exists:courses,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'subject_name.required' => 'Vui lòng nhập tên môn học.',
            'subject_name.string' => 'Tên môn học phải là chuỗi.',
            'subject_name.max' => 'Tên môn học không được vượt quá 255 ký tự.',

            'teacher_permission.integer' => 'Giá trị phân quyền phải là số nguyên.',
            'teacher_permission.exists' => 'Giáo viên không tồn tại trong hệ thống.',

            'publish_status.boolean' => 'Trạng thái xuất bản chỉ được là 0 hoặc 1.',

            'course_id.required' => 'Vui lòng chọn khóa học.',
            'course_id.integer' => 'ID khóa học phải là số.',
            'course_id.exists' => 'Khóa học không tồn tại trong hệ thống.',

            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc webp.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->subject_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/subjects/'), $filename);
            $data['image'] = 'images/subjects/' . $filename;
            File::delete($subject->image);
        } else {
            $data['image'] = $subject->image;
        }

        $subject->update($data);

        return redirect()->route('admin.subjects')->with('status', 'Môn học đã được cập nhật!');
    }


    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        if ($subject->image && File::exists('storage/' . $subject->image)) {
            File::delete('storage/' . $subject->image);
        }

        $subject->delete();

        return redirect()->route('admin.subjects')->with('status', 'Môn học đã được xóa!');
    }
}
