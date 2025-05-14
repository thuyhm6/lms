@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Giáo viên</h3>
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
                    <div class="text-tiny">Add giáo viên</div>
                </li>
            </ul>
        </div>

        <!-- form-add-teacher -->
        <form class="tf-section-2 form-add-teacher" method="POST" enctype="multipart/form-data"
            action="{{ route('admin.teacher.store') }}">
            @csrf
            <div class="wg-box">
                @if (session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif

                <fieldset class="name">
                    <div class="body-title mb-10">Họ và tên <span class="tf-color-1">*</span></div>
                    <input class="mb-10" type="text" placeholder="Enter full teacher name" name="full_name"
                        value="{{ old('full_name') }}" aria-required="true" required>
                    @error('full_name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="email" placeholder="Enter teacher email" name="email"
                            value="{{ old('email') }}" aria-required="true" required>
                        @error('email')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter teacher phone number" name="mobile"
                            value="{{ old('mobile') }}" aria-required="true" required>
                        @error('mobile')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>

                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Ngày tháng năm sinh <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="date" name="birthday" value="{{ old('birthday') }}"
                            aria-required="true" required>
                        @error('birthday')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Giới tính <span class="tf-color-1">*</span></div>
                        <select name="gender" required>
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Chọn giới tính</option>
                            <option value="nam" {{ old('gender') == 'nam' ? 'selected' : '' }}>Nam</option>
                            <option value="nữ" {{ old('gender') == 'nữ' ? 'selected' : '' }}>Nữ</option>
                        </select>
                        @error('gender')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
                <fieldset class="name">
                        <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter teacher address" name="address"
                            value="{{ old('address') }}" aria-required="true" required>
                        @error('address')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                </fieldset>
                <div class="cols gap22">
                    <fieldset class="name">
                        <div class="body-title mb-10">Học vị </div>
                        <input class="mb-10" type="text" name="academic_degree" value="{{ old('academic_degree') }}"
                            aria-required="true" >
                        @error('academic_degree')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Danh xưng </div>
                        <input class="mb-10" type="text" name="title" value="{{ old('title') }}"
                            aria-required="true" >
                        @error('title')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>
                </div>
                <fieldset class="name">
                        <div class="body-title mb-10">Facebook </div>
                        <input class="mb-10" type="text" placeholder="Enter link facebook name" name="facebook"
                            value="{{ old('facebook') }}" aria-required="true" >
                        @error('facebook')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                </fieldset>
                
               
                <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Tên đăng nhập <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter username" name="username"
                                value="{{ old('username') }}" aria-required="true" required>
                            @error('username')
                                <span class="alert alert-danger text-center">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Mật khẩu <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="password" placeholder="Enter password" name="password"
                                aria-required="true" required>
                            @error('password')
                                <span class="alert alert-danger text-center">{{ $message }}</span>
                            @enderror
                        </fieldset>
                </div>
                
            </div>

            <div class="wg-box">
                <fieldset>
                    <div class="body-title mb-10">Upload Avatar Teacher</div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display: none;">
                            <img src="" class="effect8" alt="Student avatar">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span class="tf-color">click
                                        to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <fieldset class="description">
                        <div class="body-title mb-10">Thông tin giới thiệu </div>
                        <textarea class="mb-10" name="introduction" placeholder="Ghi chú" aria-required="true" >{{ old('introduction') }}</textarea>
                        @error('introduction')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                </fieldset>
                <fieldset class="description">
                        <div class="body-title mb-10">Thành tích </div>
                        <textarea class="mb-10" name="achievements" placeholder="Ghi chú" aria-required="true" >{{ old('achievements') }}</textarea>
                        @error('achievements')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                </fieldset>
                <fieldset class="description">
                    <div class="body-title mb-10">Ghi chú </div>
                    <textarea class="mb-10" name="notes" placeholder="Ghi chú" aria-required="true" >{{ old('notes') }}</textarea>
                    @error('notes')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </fieldset>

                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Add teacher</button>
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
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Chọn môn học",
                allowClear: true
            });
        });
    </script>
@endpush
