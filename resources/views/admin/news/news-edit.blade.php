@extends('layouts.admin')
@section('content')
    <style>
        .ck-editor__editable {
            min-height: 400px;
        }

        input[type="checkbox"] {
            appearance: checkbox !important; /* Đảm bảo không bị custom ẩn */
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <div class="d-flex items-center gap20">
                     <h3>Sửa tin tức</h3>

                    <div class="mt-4">
                        <a class="tf-button style-1 w208" href="{{ route('admin.news') }}">
                            <i class="icon-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Thêm tin tức</div>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.news.update',$news->id) }}" enctype="multipart/form-data"
                class="d-flex justify-between flex-wrap">
                @csrf
                @method('PUT')
                <div class="p-2 col-lg-9 col-md-12">
                    <div class="bg-white rounded p-3">
                        <fieldset class="my-3 title">
                            <label class="body-title mb-4">Tiêu đề <span class="tf-color-1">*</span></label>
                            <input type="text" name="title" value="{{ $news->title }}">
                            @error('title')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 topic_id">
                            <label class="body-title mb-4">Chủ đề <span class="tf-color-1">*</span></label>
                            <select name="topic_id">
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}" {{ $news->topic_id == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                                @endforeach
                            </select>
                            @error('topic_id')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="my-3 news_events">
                            <label class="body-title mb-4">Loại tin <span class="tf-color-1">*</span></label>
                            <select name="news_events">
                                <option value="news" {{ $news->news_events == 'news' ? 'selected' : '' }}>Tin tức</option>
                                <option value="event" {{ $news->news_events == 'event' ? 'selected' : '' }}>Sự kiện
                                </option>
                            </select>
                            @error('news_events')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 short_intro">
                            <label class="body-title mb-4">Tóm tắt ngắn <span class="tf-color-1">*</span></label>
                            <textarea name="short_intro" rows="3">{{ $news->short_intro }}</textarea>
                            @error('short_intro')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>
                        <fieldset class="my-3 full_content">
                            <label class="body-title mb-4">Nội dung đầy đủ <span class="tf-color-1">*</span></label>
                            <textarea id=""  class="ckeditor" name="full_content" style="height:400px;">{{ $news->full_content }}</textarea>
                            @error('full_content')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3">
                            <div class="form-check mb-2 d-flex items-center">
                                <input class="form-check-input" type="checkbox" value="1" name="is_visible" id="is_visible" {{ $news->is_visible ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="is_visible">
                                    Hiển thị
                                </label>
                            </div>

                        </fieldset>

                        <fieldset class="my-3">
                            <div class="form-check mb-2 d-flex items-center">
                                <input class="form-check-input" type="checkbox" value="1" name="show_on_homepage" id="show_on_homepage" {{  $news->show_on_homepage ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="is_show_homepage">
                                    Hiện trang chủ
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="my-3">
                            <div class="form-check mb-2 d-flex items-center">
                                <input class="form-check-input" type="checkbox" value="1" name="is_featured" id="is_featured" {{  $news->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="is_featured">
                                    Nổi bật
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="my-3">
                            <div class="form-check d-flex items-center">
                                <input class="form-check-input" type="checkbox" value="1" name="is_latest" id="is_latest" {{  $news->is_latest ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="is_latest">
                                    Mới nhất
                                </label>
                            </div>
                        </fieldset>


                    </div>
                </div>
                <div class="p-2 col-lg-3 col-md-12">
                    <div class="bg-white rounded p-3">
                        <fieldset class="my-3 col-12">
                            <div class="body-title">
                                Ảnh đại diện <span class="tf-color-1">*</span>
                            </div>

                            <div class="upload-image row col-12">
                                <div id="upload-file" class="item up-load" style="width:100%;">
                                    <label class="uploadfile" for="myFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Thả hình ảnh của bạn ở đây hoặc <span class="tf-color">nhấp để duyệt</span></span>
                                        <input type="file" id="myFile" name="image" accept="image/*">
                                    </label>
                                </div>

                                <div class="item p-2 col-12" id="imgpreview"  style="width:100%;">
                                    <img src="{{ asset($news->image) }}"
                                         class="img-fluid w-100 effect8 rounded"
                                         alt="{{ $news->image_caption }}">
                                </div>

                                @error('image')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </fieldset>


                        <fieldset class="image_caption my-3">
                            <label class="body-title mb-4">Chú thích ảnh <span class="tf-color-1">*</span></label>
                            <input type="text" name="image_caption" value="{{ $news->image_caption }}">
                        </fieldset>
                        @error('image_caption')
                            <p class="text-danger"> {{ $message }}</p>
                        @enderror

                    </div>
                    <div class="bg-white rounded p-3 my-3">
                        <h6 class="p-1">Tối ưu hóa công cụ tìm kiếm:</h6>
                        <fieldset class="my-3 slug">
                            <label class="body-title mb-4">Slug <span class="tf-color-1">*</span></label>
                            <input type="text" name="slug" value="{{ $news->slug }}">
                            @error('slug')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="my-3 seo_title">
                            <label class="body-title mb-4">Title (SEO) – Tối đa 160 ký tự <span
                                    class="tf-color-1">*</span></label>
                            <input type="text" name="seo_title" value="{{ $news->seo_title }}">
                            @error('seo_title')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="my-3 seo_description">
                            <label class="body-title mb-4">Description (SEO) – Tối đa 255 ký tự <span
                                    class="tf-color-1">*</span></label>
                            <textarea name="seo_description" rows="2">{{ $news->seo_description }}</textarea>
                            @error('seo_description')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="my-3 seo_keywords">
                            <label class="body-title mb-4">Keywords (SEO) – Tối đa 255 ký tự <span
                                    class="tf-color-1">*</span></label>
                            <input type="text" name="seo_keywords" value="{{ $news->seo_keywords }}">
                            @error('seo_keywords')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        <div class="mt-4">
                            <button type="submit" class="tf-button w-full">Lưu bài viết</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#myFile").on("change", function(e) {
                const photoInp = $("#myFile");
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            $("input[name='name']").on("change", function(e) {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        function StringToSlug(param) {
            return param.toLowerCase().replace(/[^\w ]+/g, "").replace(/ +/g, "-");
        }

        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
