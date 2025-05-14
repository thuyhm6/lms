@extends('layouts.admin')

@push('styles')
    <style>
        /* Label có dấu * màu đỏ */
        .form-label.required::after {
            content: " (*)";
            color: red;
        }

        /* Đảm bảo các input, select, textarea có độ cao, padding thống nhất */
        input.form-control,
        select.form-control,
        textarea.form-control {
            height: 38px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }

        /* Textarea cao hơn một chút */
        textarea.form-control {
            height: auto;
        }

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
            background-color: #0d6efd;
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 13px;
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
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Sửa lớp học</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Class</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <form action="{{ route('class.update', $class->id) }}" method="POST" id="editClassForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="tenLopHoc" class="form-label required">Tên lớp</label>
                            <input type="text" class="form-control" id="tenLopHoc" name="class_name"
                                value="{{ old('class_name', $class->class_name) }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="giaoVien" class="form-label">Giáo viên</label>
                            <select class="form-select form-control" id="giaoVien" name="teacher_id">
                                <option value="" hidden>Chọn giáo viên</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->user_id }}"
                                        {{ old('teacher_id', $class->teacher_id) == $teacher->user_id ? 'selected' : '' }}>
                                        {{ $teacher->full_name }} ({{ $teacher->teacher_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="maLopHoc" class="form-label required">Mã lớp</label>
                            <input type="text" class="form-control" id="maLopHoc"
                                name="class_code"value="{{ old('class_code', $class->class_code) }}" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nhinThuc" class="form-label required">Hình thức</label>
                            <select class="form-select form-control" id="nhinThuc" name="learning_format" required>
                                {{-- <option value="" hidden>Chọn giá trị</option> --}}
                                @foreach ($learningFormats as $format)
                                    <option value="{{ $format['id'] }}"
                                        {{ old('learning_format', $class->learning_format) == $format['id'] ? 'selected' : '' }}>
                                        {{ $format['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="khoaHoc" class="form-label required">Khóa học</label>
                            <select class="form-select form-control" id="khoaHoc" name="course_id" required>
                                <option value="" hidden>Chọn khóa học</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id', $class->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="trangThai" class="form-label required">Trạng thái</label>
                            <select class="form-select form-control" id="trangThai" name="status" required>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status['id'] }}"
                                        {{ old('status', $class->status) == $status['id'] ? 'selected' : '' }}>
                                        {{ $status['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="soNgay" class="form-label required">Số ngày</label>
                            <input type="number" class="form-control" id="active_days" name="active_days"
                                value="{{ old('active_days', $class->active_days) }}" required min="1">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="gia" class="form-label required">Giá</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                value="{{ old('price', $class->price) }}" required min="0">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="giaKhuyenMai" class="form-label">Giá KM</label>
                            <input type="number" step="0.01" class="form-control" id="discount_price"
                                name="discount_price" value="{{ old('discount_price', $class->discount_price) }}"
                                min="0">
                        </div>
                        {{-- <div>Kiểm tra schedule: {{ json_encode($class->schedule) }}</div> --}}
                        {{-- {{ dd($class->schedule) }} --}}
                        <div class="mb-3 col-md-12">
                            <label for="customDropdown" class="form-label">Lịch học</label>
                            <div class="custom-dropdown" id="customDropdown">
                                <div class="selected-items" id="selectedItems">Chưa chọn</div>
                                <div class="dropdown-options" id="dropdownOptions">
                                    <div data-value="thu-hai">Thứ Hai</div>
                                    <div data-value="thu-ba">Thứ Ba</div>
                                    <div data-value="thu-tu">Thứ Tư</div>
                                    <div data-value="thu-nam">Thứ Năm</div>
                                    <div data-value="thu-sau">Thứ Sáu</div>
                                    <div data-value="thu-bay">Thứ Bảy</div>
                                    <div data-value="chu-nhat">Chủ Nhật</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="moTa" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $class->description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Sửa lớp</button>
                        <a href="{{ route('class.index') }}" class="btn btn-danger">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Xử lý sự kiện submit form
            $('form').on('submit', function(e) {
                e.preventDefault(); // Ngăn hành vi submit mặc định

                // Thu thập dữ liệu từ form
                let formData = $(this).serializeArray();

                let schedule = selected; // `selected` là mảng chứa các ngày đã chọn

                // Debug dữ liệu schedule
                // console.log('Schedule before sending:', schedule);

                // Đảm bảo schedule là mảng và gửi đúng định dạng
                if (Array.isArray(selected)) {
                    selected.forEach(function(day) {
                        formData.push({
                            name: 'schedule[]',
                            value: day
                        });
                    });
                } else {
                    console.error('Schedule is not an array:', selected);
                }

                // Gửi yêu cầu AJAX
                $.ajax({
                    url: $(this).attr('action'), // URL từ action của form
                    type: 'POST',
                    data: formData,
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                    // },
                    success: function(response) {
                        if (response.success) {
                            alert('Sửa lớp học thành công!');
                            window.location.href =
                                '{{ route('class.index') }}'; // Chuyển hướng về danh sách lớp học
                        } else {
                            alert('Có lỗi xảy ra: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = Object.values(errors).join('\n');
                            alert('Validation Error:\n' + errorMessage);
                        } else {
                            alert('Server Error: ' + xhr.responseJSON.message);
                        }
                    }
                });
            });

            // Xử lý dropdown tùy chỉnh (giữ nguyên code của bạn)
            const dropdown = document.getElementById('customDropdown');
            const dropdownOptions = document.getElementById('dropdownOptions');
            const selectedItems = document.getElementById('selectedItems');
            // Dữ liệu cũ (thay thế bằng dữ liệu thực tế từ server)
            // const oldData = ["thu-hai", "thu-sau"]; // Giả sử đây là dữ liệu cũ
            const oldData = {!! $class->schedule ?? '[]' !!}; // Giữ nguyên giá trị gốc
            console.log(typeof oldData); // Kết quả nên là mảng

            let selected = [...oldData]; // Khởi tạo với dữ liệu cũ

            // Tự động chọn ngày cũ
            oldData.forEach(value => {
                const option = dropdownOptions.querySelector(`[data-value="${value}"]`);
                if (option) {
                    option.style.display = 'none'; // Ẩn tùy chọn đã chọn
                }
            });

            // Hiển thị các mục đã chọn khi tải trang
            function renderSelected() {
                selectedItems.innerHTML = '';
                if (selected.length === 0) {
                    selectedItems.textContent = 'Chọn lịch học';
                    return;
                }
                selected.forEach(value => {
                    const label = dropdownOptions.querySelector(`[data-value="${value}"]`).textContent;
                    const item = document.createElement('div');
                    item.className = 'selected-item';
                    item.innerHTML = `${label} <span data-value="${value}">&times;</span>`;
                    selectedItems.appendChild(item);
                });
            }

            // Hiển thị các mục đã chọn khi tải trang
            renderSelected();

            dropdown.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'span') return;
                dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' :
                'block';
            });

            dropdownOptions.addEventListener('click', function(e) {
                const value = e.target.getAttribute('data-value');
                const label = e.target.textContent;

                if (!selected.includes(value)) {
                    selected.push(value);
                    renderSelected();
                    e.target.style.display = 'none'; // Ẩn tùy chọn đã chọn
                }
            });

            selectedItems.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'span') {
                    const value = e.target.getAttribute('data-value');
                    selected = selected.filter(v => v !== value);
                    dropdownOptions.querySelector(`[data-value="${value}"]`).style.display =
                    'block'; // Hiện lại tùy chọn
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
