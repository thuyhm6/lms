@extends('layouts.admin')

@section('content')
    <div class="main-content-inner vh-100">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-5">
                <div class="d-flex items-center gap20">
                    <h3>Thêm khóa học</h3>

                    <div class="mt-4">
                        <a class="tf-button style-1 w208" href="{{ route('admin.courses') }}">
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
                        <div class="text-tiny">Thêm khóa học</div>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data"
                class="d-flex justify-between">
                @csrf
                <div class="p-2 col-lg-8">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">

                        {{-- Mã khóa học --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Mã khóa học <span class="tf-color-1">*</span></label>
                            <input type="text" name="course_code" value="{{ old('course_code') }}">
                            @error('course_code')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- Tên khóa học --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Tên khóa học <span class="tf-color-1">*</span></label>
                            <input type="text" name="course_name" value="{{ old('course_name') }}">
                            @error('course_name')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>


                        {{-- Hiển thị --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Hiển thị</label>
                            <select name="is_visible">
                                <option value="1" {{ old('is_visible') == '1' ? 'selected' : '' }}>Hiển thị</option>
                                <option value="0" {{ old('is_visible') == '0' ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </fieldset>

                        {{-- Hiển thị trên trang chủ --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Trang chủ</label>
                            <select name="display_on_homepage">
                                <option value="1" {{ old('display_on_homepage') == '1' ? 'selected' : '' }}>Hiện
                                </option>
                                <option value="0" {{ old('display_on_homepage') == '0' ? 'selected' : '' }}>Không
                                </option>
                            </select>
                        </fieldset>

                        <div class="mt-4 col-lg-12 col-md-12 p-3">
                            <button type="submit" class="tf-button w-full">Thêm khóa học</button>
                        </div>
                    </div>
                </div>
                <div class="p-2 col-lg-4">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">
                        {{-- Hình ảnh --}}
                        <fieldset class="my-3 col-lg-12 col-md-12 p-3">
                            <div class="body-title">Hình ảnh <span class="tf-color-1">*</span>
                            </div>
                            <div class="upload-image row">

                                <div id="upload-file" class="item up-load col-lg-12 col-md-12">
                                    <label class="uploadfile" for="myFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Thả hình ảnh của bạn ở đây hoặc <span class="tf-color">chọn
                                                nhấp để duyệt</span></span>
                                        <input type="file" id="myFile" name="image" accept="image/*">
                                    </label>
                                </div>
                                <div class="item p-2 col-lg-12 col-md-12" id="imgpreview" style="display:none">
                                    <img src="" class="effect8 rounded" alt="">
                                </div>
                                @error('image')
                                    <p class="text-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                        </fieldset>
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
    </script>
@endpush
