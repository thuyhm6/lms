<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class topicController extends Controller
{
    //
    public function index(Request $request)
    {

        $query = Topic::query();
        if ($request->has('keyword')) {
            $topics = $query->where('name', 'like', '%' . $request->keyword . '%')->orderByDesc('created_at')->paginate(10);
        }else{
            $topics = $query->orderByDesc('created_at')->paginate(10);

        }


        return view('admin.topics.topics-list', compact('topics'));
    }

    public function create()
    {
        $topics = Topic::all(); // để chọn chủ đề cha
        return view('admin.topics.topic-add', compact('topics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:topics,id',
        ], [
            'name.required' => 'Tên chủ đề không được để trống',
            'name.string' => 'Tên chủ đề phải là chuỗi',
            'name.max' => 'Tên chủ đề không vượt quá 255 ký tự',

            'parent_id.exists' => 'Chủ đề cha không tồn tại',
        ]);

        $data['parent_id'] = $data['parent_id'] ?? 0;
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        Topic::create($data);

        return redirect()->route('admin.topic')->with('status', 'Chủ đề đã được thêm!');
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $topics = Topic::where('id', '!=', $id)->get(); // tránh chọn chính nó làm cha
        return view('admin.topics.topic-edit', compact('topic', 'topics'));
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|regex:/^[a-z0-9-]+$/|max:255|unique:topics,slug,' . $id,
            'parent_id' => 'nullable|exists:topics,id|not_in:' . $id,
        ], [
            'name.required' => 'Tên chủ đề không được để trống',
            'name.string' => 'Tên chủ đề phải là chuỗi',
            'name.max' => 'Tên chủ đề không vượt quá 255 ký tự',

            'slug.string' => 'Slug phải là chuỗi',
            'slug.regex' => 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang (-)',
            'slug.max' => 'Slug không vượt quá 255 ký tự',
            'slug.unique' => 'Slug đã tồn tại',

            'parent_id.exists' => 'Chủ đề cha không tồn tại',
            'parent_id.not_in' => 'Chủ đề không thể là cha của chính nó',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['parent_id'] = $data['parent_id'] ?? 0;
        $data['updated_at'] = now();
        $topic->update($data);

        return redirect()->route('admin.topic')->with('status', 'Chủ đề đã được cập nhật!');
    }

    public function destroy($id)
    {
        Topic::findOrFail($id)->delete();
        return redirect()->route('admin.topic')->with('status', 'Chủ đề đã được xóa!');
    }
}
