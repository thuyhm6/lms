<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class newsController extends Controller
{
    //

    public function index(Request $request)
    {
        $news = News::latest()->with('creator')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'news' => $news,
                'pagination' => $news->links('pagination::bootstrap-5')->render()
            ]);
        }

        return view('admin.news.news-list', compact('news'));
    }

    public function filter(Request $request)
    {
        $news = $this->getFilteredsubject($request);
        $news->appends($request->all());
        return response()->json([
            'news' => $news,
            'pagination' => $news->links('pagination::bootstrap-5')->render()
        ]);
    }

    private function getFilteredSubject(Request $request)
    {
        $query = News::query();


        if ($request->filled('is_visible')) {
            $query->where('is_visible', $request->is_visible);
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        if ($request->filled('is_latest')) {
            $query->where('is_latest', $request->is_latest);
        }

        if ($request->filled('show_on_homepage')) {
            $query->where('show_on_homepage', $request->show_on_homepage);
        }


        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        return $query->with('creator')->orderByDesc('created_at')->paginate($request->limit);
    }



    public function create()
    {
        $topics = Topic::all();
        return view('admin.news.news-add', compact('topics'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|regex:/^[a-z0-9-]+$/|max:255',
            'topic_id' => 'required|exists:topics,id',
            'news_events' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_caption' => 'nullable|string|max:255',
            'short_intro' => 'required|string|max:500',
            'full_content' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255'

        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là một chuỗi ký tự',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',

            'slug.string' => 'Slug phải là một chuỗi ký tự',
            'slug.regex' => 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang (-)',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',

            'topic_id.required' => 'Chủ đề không được để trống',
            'topic_id.exists' => 'Chủ đề không tồn tại trong hệ thống',

            'news_events.required' => 'Vui lòng chọn loại tin (Tin tức/Sự kiện)',


            'image.image' => 'Tệp tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận các định dạng: jpeg, png, jpg, gif, webp',
            'image.max' => 'Hình ảnh không được vượt quá 2MB',


            'image_caption.string' => 'Chú thích ảnh phải là chuỗi',
            'image_caption.max' => 'Chú thích ảnh không được vượt quá 255 ký tự',

            'short_intro.required' => 'Giới thiệu ngắn không được để trống',
            'short_intro.string' => 'Giới thiệu ngắn phải là chuỗi',
            'short_intro.max' => 'Giới thiệu ngắn không được vượt quá 500 ký tự',

            'full_content.required' => 'Nội dung đầy đủ không được để trống',
            'full_content.string' => 'Nội dung đầy đủ phải là chuỗi',

            'seo_title.string' => 'Tiêu đề SEO phải là chuỗi',
            'seo_title.max' => 'Tiêu đề SEO không được vượt quá 255 ký tự',

            'seo_description.string' => 'Mô tả SEO phải là chuỗi',
            'seo_description.max' => 'Mô tả SEO không được vượt quá 500 ký tự',
        ]);


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news/'), $filename);
            $data['image'] = 'images/news/' . $filename;
        } else {
            $data['image'] = null;
        }

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        $data['is_visible'] = $request->boolean('is_visible') ? 1  : 0;
        $data['show_on_homepage'] = $request->boolean('show_on_homepage') ? 1  : 0;
        $data['is_featured'] = $request->boolean('is_featured') ? 1  : 0;
        $data['is_latest'] = $request->boolean('is_latest') ? 1  : 0;

        // dd($data);
        News::create($data);

        return redirect()->route('admin.news')->with('status', 'Tin tức đã được thêm!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        $topics = Topic::all();
        return view('admin.news.news-edit', compact('news', 'topics'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|regex:/^[a-z0-9-]+$/|max:255',
            'topic_id' => 'required|exists:topics,id',
            'news_events' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_caption' => 'nullable|string|max:255',
            'short_intro' => 'required|string|max:500',
            'full_content' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
        ], [
            'title.required' => 'Tiêu đề không được để trống',
            'title.string' => 'Tiêu đề phải là một chuỗi ký tự',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',

            'slug.string' => 'Slug phải là một chuỗi ký tự',
            'slug.regex' => 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang (-)',
            'slug.max' => 'Slug không được vượt quá 255 ký tự',

            'topic_id.required' => 'Chủ đề không được để trống',
            'topic_id.exists' => 'Chủ đề không tồn tại trong hệ thống',

            'news_events.required' => 'Vui lòng chọn loại tin (Tin tức/Sự kiện)',

            'image.image' => 'Tệp tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh chỉ chấp nhận các định dạng: jpeg, png, jpg, gif, webp',
            'image.max' => 'Hình ảnh không được vượt quá 2MB',

            'image_caption.string' => 'Chú thích ảnh phải là chuỗi',
            'image_caption.max' => 'Chú thích ảnh không được vượt quá 255 ký tự',

            'short_intro.required' => 'Giới thiệu ngắn không được để trống',
            'short_intro.string' => 'Giới thiệu ngắn phải là chuỗi',
            'short_intro.max' => 'Giới thiệu ngắn không được vượt quá 500 ký tự',

            'full_content.required' => 'Nội dung đầy đủ không được để trống',
            'full_content.string' => 'Nội dung đầy đủ phải là chuỗi',

            'seo_title.string' => 'Tiêu đề SEO phải là chuỗi',
            'seo_title.max' => 'Tiêu đề SEO không được vượt quá 255 ký tự',

            'seo_description.string' => 'Mô tả SEO phải là chuỗi',
            'seo_description.max' => 'Mô tả SEO không được vượt quá 500 ký tự',

            'seo_keywords.string' => 'Từ khóa SEO phải là chuỗi',
            'seo_keywords.max' => 'Từ khóa SEO không được vượt quá 255 ký tự',
        ]);



        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/news/'), $filename);
            $data['image'] = 'images/news/' . $filename;
            File::delete($news->image);
        } else {
            $data['image'] = $news->image;
        }

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['updated_by'] = Auth::user()->id;
        $data['updated_at'] = now();

        $data['is_visible'] = $request->boolean('is_visible') ? 1  : 0;
        $data['show_on_homepage'] = $request->boolean('show_on_homepage') ? 1  : 0;
        $data['is_featured'] = $request->boolean('is_featured') ? 1  : 0;
        $data['is_latest'] = $request->boolean('is_latest') ? 1  : 0;

        $news->update($data);
        return redirect()->route('admin.news')->with('status', 'Tin tức đã được cập nhật!');
    }


    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return redirect()->route('admin.news')->with('status', 'Tin tức đã được xóa!');
    }

    // public function show($id)
    // {
    //     $news = News::findOrFail($id);
    //     return view('admin.news.news-show', compact('news'));
    // }


}
