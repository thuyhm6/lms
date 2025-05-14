@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Sửa thông tin giáo viên</h3>
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
                    <a href="{{ route('admin.teachers') }}">
                        <div class="text-tiny">teachers</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Sửa giáo viên</div>
                </li>
            </ul>
        </div>

        <!-- form-edit-teacher -->
        <form class="tf-section-2 form-add-teacher" method="POST" enctype="multipart/form-data"
            action="{{ route('admin.teacher.update', $teacher->user_id) }}">
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
                    <input class="mb-10" type="text" placeholder="Enter full teacher name" name="full_name"
                        value="{{ old('full_name', $teacher->full_name ?? '') }}" aria-required="true" required>
                    @error('full_name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="email" placeholder="Enter teacher email" name="email"
                            value="{{ old('email', $teacher->email ?? '') }}" aria-required="true" required>
                        @error('email')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter teacher phone number" name="mobile"
                            value="{{ old('mobile', $teacher->mobile ?? '') }}" aria-required="true" required>
                        @error('mobile')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Ngày tháng năm sinh <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="birthday"
                            value="{{ old('birthday', $teacher->birthday ? \Carbon\Carbon::parse($teacher->birthday)->format('Y-m-d') : '') }}"
                            aria-required="true" required>
                        @error('birthday')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giới tính <span class="tf-color-1">*</span></div>
                        <select name="gender" required>
                            <option value="" disabled {{ old('gender', $teacher->gender) ? '' : 'selected' }}>Chọn giới tính</option>
                            <option value="nam" {{ old('gender', $teacher->gender) == 'nam' ? 'selected' : '' }}>Nam</option>
                            <option value="nữ" {{ old('gender', $teacher->gender) == 'nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                        @error('gender')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
                <fieldset class="name">
                    <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter teacher address" name="address"
                        value="{{ old('address', $teacher->address ?? '') }}" aria-required="true" required>
                    @error('address')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Học vị</div>
                        <input class="mb-10" type="text" name="academic_degree"
                            value="{{ old('academic_degree', $teacher->academic_degree ?? '') }}">
                        @error('academic_degree')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Danh xưng</div>
                        <input class="mb-10" type="text" name="title"
                            value="{{ old('title', $teacher->title ?? '') }}">
                        @error('title')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
                <fieldset class="name">
                    <div class="body-title mb-10">Facebook</div>
                    <input class="mb-10" type="text" placeholder="Enter link facebook name" name="facebook"
                        value="{{ old('facebook', $teacher->facebook ?? '') }}">
                    @error('facebook')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên đăng nhập <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter username" name="username"
                            value="{{ old('username', $teacher->username ?? '') }}" aria-required="true" required>
                        @error('username')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Mật khẩu</div>
                        <input class="mb-10" type="password" placeholder="Enter new password (leave blank to keep current)" name="password" value="{{ old('password', $teacher->password ?? '') }}">
                        @error('password')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title mb-10">Upload Avatar teacher</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="{{ $teacher->image ? '' : 'display: none;' }}">
                                <img src="{{ $teacher->image && $teacher->image != 'default.png' ? asset('uploads/avatars/' . $teacher->image) : '' }}" class="effect8" alt="teacher avatar">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="avatar" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('avatar')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <fieldset class="description">
                    <div class="body-title mb-10">Thông tin giới thiệu</div>
                    <textarea class="mb-10" name="introduction" placeholder="Ghi chú">{{ old('introduction', $teacher->introduction ?? '') }}</textarea>
                    @error('introduction')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>
                <fieldset class="description">
                    <div class="body-title mb-10">Thành tích</div>
                    <textarea class="mb-10" name="achievements" placeholder="Ghi chú">{{ old('achievements', $teacher->achievements ?? '') }}</textarea>
                    @error('achievements')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>
                <fieldset class="description">
                    <div class="body-title mb-10">Ghi chú</div>
                    <textarea class="mb-10" name="notes" placeholder="Ghi chú">{{ old('notes', $teacher->notes ?? '') }}</textarea>
                    @error('notes')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap10">
                    <a href="{{ route('admin.teachers') }}" class="tf-button w-full" style="background-color: #6c757d;">Quay lại</a>
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
        });
    </script>
@endpush