@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">

            <div class="flex items-center flex-wrap justify-between gap20 mb-27">


                <div class="d-flex items-center gap20">

                    <h3>Thêm bài giảng của môn học "<span
                            class="text-danger fw-bold">{{ $subjectsDetail->subject_name }}</span>"
                    </h3>
                    <div class="mt-4">
                        <a class="tf-button style-1 w208" href="{{ route('admin.courses') }}">
                            <i class="icon-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.subjects') }}">
                            <div class="text-tiny">Môn học</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.lessons', ['id' => $subjectsDetail->id]) }}">
                            <div class="text-tiny">Bài giảng</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>

                        <div class="text-tiny">Thêm bài giảng</div>

                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data"
                class="d-flex justify-between">
                @csrf
                <div class="p-2 col-lg-12">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">
                        <input type="hidden" name="subject_id" value="{{ $subjectsDetail->id }}">

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Chủ đề</label>
                            <input type="text" name="topic" value="{{ old('topic') }}">
                        </fieldset>

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Tên bài giảng <span class="tf-color-1">*</span></label>
                            <input type="text" name="lesson_name" value="{{ old('lesson_name') }}">
                        </fieldset>

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Mô tả nội dung <span class="tf-color-1">*</span></label>
                            <input type="text" name="content" value="{{ old('content') }}">
                        </fieldset>

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Loại nội dung </label>
                            <select name="file_type" id="file_type" class="">
                                <option value="iSpring">iSpring</option>
                                <option value="Video">Video</option>
                            </select>
                        </fieldset>

                        {{-- <div class="my-3 col-lg-6 col-md-12 p-3" id="content-wrapper">
                            <label class="body-title mb-4">Nội dung</label>
                            <input type="text" name="content" value="{{ old('content') }}">
                        </div> --}}

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Giáo viên</label>
                            <select name="teacher_id" id="" disabled>
                                <option value="{{ $subjectsDetail->teacher_permission }}">
                                    {{ $subjectsDetail->teachers->full_name }}
                                </option>
                            </select>
                            <!-- Hidden input to actually send value -->
                            <input type="hidden" name="teacher_id" value="{{ $subjectsDetail->teacher_permission }}">
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Loại bài giảng</label>
                            <select name="type">
                                <option value="Bài giảng">Bài giảng</option>
                                <option value="Khóa học">Khóa học</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Webinar">Webinar</option>
                                <option value="Hội thảo">Hội thảo</option>
                            </select>
                        </fieldset>

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Hình thức</label>
                            <select name="fee_type">
                                <option value="Offline">Offline</option>
                                <option value="Online">Online</option>
                            </select>
                        </fieldset>

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Thời lượng</label>
                            <input type="time" name="duration" value="{{ old('duration', '01:30:00') }}">
                        </fieldset>

                        <div class="mt-4 col-lg-12 col-md-12 p-3">
                            <button type="submit" class="tf-button w-full">Thêm bài giảng</button>
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
            $(".delete").on('click', function(e) {
                e.preventDefault();
                var selectedForm = $(this).closest('form');
                swal({
                    title: "Bạn có chắc không?",
                    text: "Bạn muốn xóa bản ghi này?",
                    type: "warning",
                    buttons: ["Không", "Có"],
                    confirmButonColor: "#dc3545"
                }).then(function(result) {
                    if (result) {
                        selectedForm.submit();
                    }
                });
            });
        });
    </script>
@endpush
