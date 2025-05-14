@extends('layouts.admin')

@push('styles')
    <style>
       
        /* Giao diện dropdown tùy chỉnh */
        .custom-dropdown {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 12px;
            background-color: #fff;
            cursor: pointer;
            position: relative;
            font-size: 14px;
            min-height: 38px;
        }

        .custom-dropdown::after {
            content: '▼';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 12px;
            pointer-events: none;
        }

        /* Danh sách lựa chọn */
        .dropdown-options {
            display: none;
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background: #fff;
            z-index: 1050;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .dropdown-options div {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
        }

        .dropdown-options div:hover {
            background-color: #f8f9fa;
        }

        /* Hiển thị các item đã chọn */
        .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .selected-item {
            background-color: #438eff;
            color: white;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
        }

        .selected-item span {
            margin-left: 6px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Add Parent</h3>
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
                        <a href="{{ route('admin.parents') }}">
                            <div class="text-tiny">Parents</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Add parent</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-parent -->
            <form class="tf-section-2 form-add-parent" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.parent.store') }}">
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Họ và tên <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter full parent name" name="full_name"
                            tabindex="0" value="{{ old('full_name') }}" aria-required="true" required="">
                        <div class="text-tiny">Do not exceed 100 characters when entering the parent fullname.</div>
                    </fieldset>
                    @error('full_name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter parent email" name="email"
                                tabindex="0" value="{{ old('email') }}" aria-required="true" required="">
                        </fieldset>
                        @error('email')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter parent phone number" name="mobile"
                                tabindex="0" value="{{ old('mobile') }}" aria-required="true" required="">
                        </fieldset>
                        @error('mobile')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <fieldset class="name">
                        <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter parent address" name="address"
                            tabindex="0" value="{{ old('address') }}" aria-required="true" required="">
                    </fieldset>
                    @error('address')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Trường học của con <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter school name" name="school"
                                tabindex="0" value="{{ old('school') }}" aria-required="true" required="">
                        </fieldset>
                        @error('school')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Lớp của con <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter child class" name="grade"
                                tabindex="0" value="{{ old('grade') }}" aria-required="true" required="">
                        </fieldset>
                        @error('grade')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Trạng thái tìm hiểu<span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="status">
                                    <option disabled selected>Chọn trạng thái</option>
                                    <option value="pending">Chờ đợi</option>
                                    <option value="interested">Quan tâm</option>
                                    <option value="exploring">Tìm hiểu</option>
                                    <option value="doubtful">Nghi ngờ</option>
                                    <option value="rejected">Từ chối</option>
                                    <option value="completed">Hoàn thành</option>
                                    <option value="reserved">Bảo lưu</option>
                                    <option value="inactive">Ngừng khai thác</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('category_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="brand">
                            <div class="body-title mb-10">Hình thức học <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="learning_format">
                                    <option disabled selected>Chọn hình thức học</option>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('brand_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>


                    <a class="tf-button style-1 w208" href="{{ route('admin.parents') }}">
                        <i class="icon-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload Image Parent<span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8"
                                    alt="">
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
                    </fieldset>
                    @error('avatar')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror


                    <fieldset class="name">
                        <div class="body-title mb-10">Chọn môn học <span class="tf-color-1">*</span></div>
                        <div class="custom-dropdown" id="customDropdown">
                            <div class="selected-items" id="selectedItems">Chưa chọn</div>
                            <div class="dropdown-options" id="dropdownOptions">
                                @foreach ($subjects as $subject)
                                    <div data-value="{{ $subject->id }}">{{ $subject->subject_name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </fieldset>
                    @error('subjects')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    @error('subjects')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <div class="cols gap22">
                        
                        <fieldset class="name">
                            <div class="body-title mb-10">Nguồn maketing <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select class="" name="marketing_source">
                                    <option disabled selected>Chọn nguồn</option>
                                        <option value="none">Chưa chọn</option>
                                        <option value="ads_content">Ads & Content</option>
                                        <option value="consultant">Tham khảo</option>
                                        <option value="class_management">CSKH - Quản lý lớp học</option>
                                        <option value="workshop">Wordshop</option>
                                        <option value="sales_marketing">Sale & Marketing</option>
                                        <option value="teacher">Giáo viên</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('sale_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <fieldset class="description">
                        <div class="body-title mb-10">Ghi chú <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="notes" placeholder="Ghi chú" tabindex="0" aria-required="true" required="">{{ old('notes') }}</textarea>
                    </fieldset>
                    @error('notes')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Add Parent</button>
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

@push('scripts')
    <script>
        
            $(document).ready(function() {
    const dropdown = document.getElementById('customDropdown');
    const dropdownOptions = document.getElementById('dropdownOptions');
    const selectedItems = document.getElementById('selectedItems');
    let selected = [];

    dropdown.addEventListener('click', function(e) {
        if (e.target.tagName.toLowerCase() === 'span') return;
        dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' : 'block';
    });

    dropdownOptions.addEventListener('click', function(e) {
        const value = e.target.getAttribute('data-value');
        const label = e.target.textContent;
        if (!selected.includes(value)) {
            selected.push(value);
            renderSelected();
            e.target.style.display = 'none';
        }
    });

    function renderSelected() {
        selectedItems.innerHTML = '';
        document.querySelectorAll('input[name="subjects[]"]').forEach(input => input.remove());

        if (selected.length === 0) {
            selectedItems.textContent = 'Chưa chọn';
            return;
        }
        selected.forEach(value => {
            const label = dropdownOptions.querySelector(`[data-value="${value}"]`).textContent;
            const item = document.createElement('div');
            item.className = 'selected-item';
            item.innerHTML = `${label} <span data-value="${value}">×</span>`;
            selectedItems.appendChild(item);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'subjects[]';
            input.value = value;
            dropdown.appendChild(input);
        });
    }

    selectedItems.addEventListener('click', function(e) {
        if (e.target.tagName.toLowerCase() === 'span') {
            const value = e.target.getAttribute('data-value');
            selected = selected.filter(v => v !== value);
            dropdownOptions.querySelector(`[data-value="${value}"]`).style.display = 'block';
            renderSelected();
        }
    });

    document.addEventListener('click', function(e) {
        if (!dropdown.contains(e.target)) {
            dropdownOptions.style.display = 'none';
        }
    });
});
        
    </script>
@endpush
