<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class coursesController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->has('course_name')) {
            $query->where('course_name', 'like', '%' . $request->course_name . '%');
        }

        $courses = $query->orderByDesc('created_at')->paginate(10);
        if ($request->ajax()) {
            return response()->json([
                'courses' => $courses,
                'pagination' => $courses->links('pagination::bootstrap-5')->render()
            ]);
        }
        return view('admin.courses.courses-list', compact('courses'));
    }


    public function filter(Request $request)
    {
        $courses = $this->getFilteredCourses($request);
        $courses->appends($request->all());
        return response()->json([
            'courses' => $courses,
            'pagination' => $courses->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredCourses(Request $request)
    {
        $query = Course::query();

        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible);
        }

        if ($request->filled('display_on_homepage')) {
            $query->where('display_on_homepage', $request->display_on_homepage);
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('course_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('course_code', 'like', '%' . $request->keyword . '%');
            });
        }

        return $query->orderByDesc('created_at')->paginate($request->limit);
    }




    public function create()
    {
        return view('admin.courses.courses-add');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_code' => 'required|string|max:50|unique:courses,course_code',
            'course_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_visible' => 'sometimes|boolean',
            'display_on_homepage' => 'sometimes|boolean',
        ], [
            'course_code.required' => 'Vui lòng nhập mã khóa học.',
            'course_code.string' => 'Mã khóa học phải là chuỗi.',
            'course_code.max' => 'Mã khóa học không được vượt quá 50 ký tự.',
            'course_code.unique' => 'Mã khóa học đã tồn tại.',

            'course_name.required' => 'Vui lòng nhập tên khóa học.',
            'course_name.string' => 'Tên khóa học phải là chuỗi.',
            'course_name.max' => 'Tên khóa học không được vượt quá 255 ký tự.',

            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg hoặc gif.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',

            'is_visible.boolean' => 'Giá trị hiển thị phải là đúng hoặc sai.',
            'display_on_homepage.boolean' => 'Giá trị hiển thị trên trang chủ phải là đúng hoặc sai.',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->course_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/courses/'), $filename);
            $data['image'] = 'images/courses/' . $filename;
        }

        Course::create($data);

        return redirect()->route('admin.courses')->with('status', 'Khóa học đã được thêm!');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.courses-edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $data = $request->validate([
            'course_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'course_code')->ignore($id),
            ],
            'course_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_visible' => 'sometimes|boolean',
            'display_on_homepage' => 'sometimes|boolean',
        ], [
            'course_code.required' => 'Vui lòng nhập mã khóa học.',
            'course_code.string' => 'Mã khóa học phải là chuỗi.',
            'course_code.max' => 'Mã khóa học không được vượt quá 50 ký tự.',
            'course_code.unique' => 'Mã khóa học đã tồn tại.',

            'course_name.required' => 'Vui lòng nhập tên khóa học.',
            'course_name.string' => 'Tên khóa học phải là chuỗi.',
            'course_name.max' => 'Tên khóa học không được vượt quá 255 ký tự.',

            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg hoặc gif.',
            'image.max' => 'Hình ảnh không được vượt quá 2MB.',

            'is_visible.boolean' => 'Giá trị hiển thị phải là đúng hoặc sai.',
            'display_on_homepage.boolean' => 'Giá trị hiển thị trên trang chủ phải là đúng hoặc sai.',
        ]);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->course_name) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/courses/'), $filename);
            $data['image'] = 'images/courses/' . $filename;
            File::delete($course->image);
        } else {
            $data['image'] = $course->image;
        }

        $course->update($data);

        return redirect()->route('admin.courses')->with('status', 'Khóa học đã được cập nhật!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image && File::exists('storage/' . $course->image)) {
            File::delete('storage/' . $course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses')->with('status', 'Khóa học đã được xóa!');
    }
}
