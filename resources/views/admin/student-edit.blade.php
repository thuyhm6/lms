@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Sửa thông tin học sinh</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{ route('admin.students') }}">
                        <div class="text-tiny">Students</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Sửa học sinh</div>
                </li>
            </ul>
        </div>

        <!-- form-edit-student -->
        <form class="tf-section-2 form-add-student" method="POST" enctype="multipart/form-data"
            action="{{ route('admin.student.update', $student->user_id) }}">
            @csrf
            @method('PUT')
            <div class="wg-box">
                @if (session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <fieldset class="name">
                    <div class="body-title mb-10">Họ và tên <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter full student name" name="full_name"
                        value="{{ old('full_name', $student->full_name ?? '') }}" aria-required="true" required>
                    @error('full_name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <fieldset class="name">
                    <div class="body-title mb-10">Phụ huynh <span class="tf-color-1">*</span></div>
                    <select name="parent_id" required>
                        <option value="" disabled {{ old('parent_id', $student->parent_id) ? '' : 'selected' }}>Chọn phụ huynh</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->user_id }}"
                                {{ old('parent_id', $student->parent_id) == $parent->user_id ? 'selected' : '' }}>
                                {{ $parent->full_name }} - {{ $parent->mobile }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="email" placeholder="Enter student email" name="email"
                            value="{{ old('email', $student->email ?? '') }}" aria-required="true" required>
                        @error('email')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter student phone number" name="mobile"
                            value="{{ old('mobile', $student->mobile ?? '') }}" aria-required="true" required>
                        @error('mobile')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Ngày tháng năm sinh <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="birthday"
                            value="{{ old('birthday', $student->birthday ? \Carbon\Carbon::parse($student->birthday)->format('Y-m-d') : '') }}"
                            aria-required="true" required>
                        @error('birthday')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giới tính <span class="tf-color-1">*</span></div>
                        <select name="gender" required>
                            <option value="" disabled {{ old('gender', $student->gender) ? '' : 'selected' }}>Chọn giới tính</option>
                            <option value="nam" {{ old('gender', $student->gender) == 'nam' ? 'selected' : '' }}>Nam</option>
                            <option value="nữ" {{ old('gender', $student->gender) == 'nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                        @error('gender')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Trường <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter student school" name="school"
                            value="{{ old('school', $student->school ?? '') }}" aria-required="true" required>
                        @error('school')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Lớp <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter student class" name="grade"
                            value="{{ old('grade', $student->grade ?? '') }}" aria-required="true" required>
                        @error('grade')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>

                <fieldset class="name">
                    <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter student address" name="address"
                        value="{{ old('address', $student->address ?? '') }}" aria-required="true" required>
                    @error('address')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title mb-10">Upload Avatar student</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="{{ $student->image && $student->image != 'default.png' ? '' : 'display: none;' }}">
                                <img src="{{ $student->image && $student->image != 'default.png' ? asset('uploads/avatars/' . $student->image) : '' }}" class="effect8" alt="parent avatar">
                            </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên đăng nhập <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter username" name="username"
                            value="{{ old('username', $student->username ?? '') }}" aria-required="true" required>
                        @error('username')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Mật khẩu mới</div>
                        <input class="mb-10" type="password" placeholder="Enter new password (leave blank to keep current)" name="password" value="{{ old('password', $student->password ?? '') }}"> 
                        @error('password')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>

                <fieldset class="description">
                    <div class="body-title mb-10">Ghi chú</div>
                    <textarea class="mb-10" name="notes" placeholder="Ghi chú">{{ old('notes', $student->notes ?? '') }}</textarea>
                    @error('notes')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap10">
                    <a href="{{ route('admin.students') }}" class="tf-button w-full" style="background-color: #6c757d;">Quay lại</a>
                    <button class="tf-button w-full" type="submit" style="background-color: #28a745;">Cập nhật</button>
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

            $("#gFile").on("change", function(e) {
                const photoInp = $("#gFile");
                const gphotos = this.files;
                $.each(gphotos, function(key, value) {
                    $("#galUpload").prepend(
                        `<div class="item gitems"><img src="${URL.createObjectURL(value)}" alt=""</div>`
                    )
                })
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