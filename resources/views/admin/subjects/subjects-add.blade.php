@extends('layouts.admin')

@section('content')
    <div class="main-content-inner vh-100">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-5">

                <div class="d-flex items-center gap20">
                    <h3>Thêm môn học</h3>

                    <div class="mt-4">
                        <a class="tf-button style-1 w208" href="{{ route('admin.subjects') }}">
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
                        <div class="text-tiny">Thêm môn học</div>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.subjects.store') }}" enctype="multipart/form-data"
                class="d-flex justify-between">
                @csrf
                <div class="p-2 col-lg-8">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">

                        {{-- Tên môn học --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Tên môn học <span class="tf-color-1">*</span></label>
                            <input type="text" name="subject_name" value="{{ old('subject_name') }}">
                            @error('subject_name')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>


                        {{-- Phân quyền giáo viên --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Phân quyền giáo viên</label>
                            <select name="teacher_permission">
                                <option value="" disabled selected>-Chọn giáo viên-</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->user_id }}"
                                        {{ old('teacher_permission') == $teacher->user_id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }}</option>
                                @endforeach

                            </select>
                            @error('teacher_permission')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- Trạng thái xuất bản --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Xuất bản</label>
                            <select name="publish_status">
                                <option value="1" {{ old('publish_status') == '1' ? 'selected' : '' }}>Duyệt</option>
                                <option value="0" {{ old('publish_status') == '0' ? 'selected' : '' }}>Chưa đuyệt
                                </option>
                            </select>
                            @error('publish_status')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- ID khóa học (nếu có liên kết) --}}
                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">khóa học</label>
                            <select name="course_id">
                                <option value="" disabled selected>-Chọn Khóa học-</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach


                            </select>
                            @error('course_id')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>



                        <div class="mt-4 col-lg-12 col-md-12 p-3">
                            <button type="submit" class="tf-button w-full">Thêm môn học</button>
                        </div>
                    </div>
                </div>

                {{-- Hình ảnh --}}
                <div class="p-2 col-lg-4">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">
                        <fieldset class="my-3 col-lg-12 col-md-12 p-3">
                            <div class="body-title">Hình ảnh</div>
                            <div class="upload-image row">
                                <div id="upload-file" class="item up-load col-lg-12 col-md-12">
                                    <label class="uploadfile" for="myFile">
                                        <span class="icon">
                                            <i class="icon-upload-cloud"></i>
                                        </span>
                                        <span class="body-text">Thả hình ảnh ở đây hoặc <span class="tf-color">chọn
                                                tệp</span></span>
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
            $("#myFile").on("change", function() {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
        });
    </script>
@endpush
