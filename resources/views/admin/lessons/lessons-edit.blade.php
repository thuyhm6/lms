@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">


                <div class="d-flex items-center gap20">

                    <h3>Chỉnh sửa bài giảng "<span class="text-danger fw-bold">{{ $lesson->lesson_name }}</span>"</h3>
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
                    <li><a href="{{ route('admin.subjects') }}">
                            <div class="text-tiny">Môn học</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.lessons', ['id' => $lesson->subject_id]) }}">
                            <div class="text-tiny">Bài giảng</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa bài giảng</div>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.lessons.update', $lesson->id) }}" enctype="multipart/form-data"
                class="d-flex justify-between">
                @csrf
                @method('PUT')
                <div class="p-2 col-lg-12">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">
                        <input type="hidden" name="subject_id" value="{{ $lesson->subject_id }}">

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Chủ đề</label>
                            <input type="text" name="topic" value="{{ old('topic', $lesson->topic) }}">
                            @error('topic')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Tên bài giảng <span class="tf-color-1">*</span></label>
                            <input type="text" name="lesson_name" value="{{ old('lesson_name', $lesson->lesson_name) }}">
                            @error('lesson_name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Mô tả nội dung <span class="tf-color-1">*</span></label>
                            <input type="text" name="content" value="{{ old('content') }}">
                            @error('content')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Loại nội dung</label>
                            <select name="file_type" id="file_type">
                                <option value="iSpring" {{ $lesson->file_type == 'iSpring' ? 'selected' : '' }}>iSpring
                                </option>
                                <option value="Video" {{ $lesson->file_type == 'Video' ? 'selected' : '' }}>Video</option>
                            </select>
                            @error('file_type')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror

                        </fieldset>

                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Giáo viên</label>
                            <select name="teacher_id" disabled>
                                <option value="{{ $lesson->teacher_id }}">
                                    {{ $lesson->teachers->full_name ?? 'Giáo viên không xác định' }}</option>
                            </select>
                            <input type="hidden" name="teacher_id" value="{{ $lesson->teacher_id }}">
                            @error('teacher_id')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Loại bài giảng</label>
                            <select name="type">
                                @foreach (['Bài giảng', 'Khóa học', 'Workshop', 'Webinar', 'Hội thảo'] as $type)
                                    <option value="{{ $type }}" {{ $lesson->type == $type ? 'selected' : '' }}>
                                        {{ $type }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Hình thức</label>
                            <select name="fee_type">
                                <option value="Offline" {{ $lesson->fee_type == 'Offline' ? 'selected' : '' }}>Offline
                                </option>
                                <option value="Online" {{ $lesson->fee_type == 'Online' ? 'selected' : '' }}>Online
                                </option>
                            </select>
                            @error('fee_type')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <fieldset class="my-3 col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Thời lượng</label>
                            <input type="time" name="duration"
                                value="{{ old('duration', $lesson->duration ?? '01:30:00') }}">
                            @error('duration')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </fieldset>


                        <div class="mt-4 col-lg-12 col-md-12 p-3">
                            <button type="submit" class="tf-button w-full">Cập nhật bài giảng</button>
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
