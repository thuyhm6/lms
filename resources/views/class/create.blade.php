@extends('layouts.admin')

@push('styles')
    <style>
        /* Set base font size for the page */
        body {
            font-size: 15px; /* Base font size for scalability */
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        /* Custom form styling */
        .form-label {
            font-weight: bold; /* Hoặc dùng số: 600, 700, 800,... */
        }
        .form-label.required::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
        }

        /* Standardize form controls */
        .form-control, .form-select, .custom-dropdown {
            height: 40px; /* Fixed height for all controls */
            border: 1px solid #ced4da !important; /* Identical border for all */
            border-radius: 5px !important; /* Consistent border radius */
            padding: 10px 12px;
            font-size: 1.3rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-sizing: border-box;
            width: 100%;
            display: flex;
            align-items: center;
            background-color: #fff; /* Ensure consistent background */
        }

        .form-control:focus, .form-select:focus, .custom-dropdown:focus {
            border: 1px solid #0d6efd; /* Same border on focus */
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
            outline: none;
        }

        .form-control:hover, .form-select:hover, .custom-dropdown:hover {
            border: 1px solid #0d6efd; /* Same border on hover */
        }

        /* Ensure select elements align properly */
        .form-select {
            padding-right: 30px; /* Space for dropdown arrow */
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236c757d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }

        textarea.form-control {
            height: 100px; /* Fixed height for textarea */
            resize: vertical;
            border: 1px solid #ced4da; /* Match other controls */
        }

        textarea.form-control:focus {
            border: 1px solid #0d6efd; /* Match focus border */
        }

        textarea.form-control:hover {
            border: 1px solid #0d6efd; /* Match hover border */
        }

        /* Custom dropdown styling */
        .custom-dropdown {
            cursor: pointer;
            position: relative;
            /* font-size: 1.3rem; */
        }

        .custom-dropdown::after {
            content: '▼';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 0.8rem;
        }

        .dropdown-options {
            display: none;
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            background: #fff;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dropdown-options div {
            padding: 10px 12px;
            cursor: pointer;
            font-size: 1.3rem;
            transition: background-color 0.2s ease;
        }

        .dropdown-options div:hover {
            background-color: #f1f3f5;
        }

        .selected-items {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            min-height: 24px;
            font-size: 1.3rem;
        }

        .selected-item {
            background-color: #0d6efd;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 1.3rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .selected-item span {
            cursor: pointer;
            font-weight: 600;
            line-height: 1;
        }

        /* Button styling */
        .btn {
            padding: 5px 40px;
            font-size: 1.5rem;
            border-radius: 6px;
            height: 40px; /* Match form control height */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-danger {
            padding: 5px 30px !important;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Card styling */
        .wg-box {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Breadcrumb styling */
        .breadcrumbs {
            font-size: 0.9rem;
        }

        .breadcrumbs i {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <h3 class="fw-bold text-dark">Thêm lớp học</h3>
                <ul class="breadcrumbs d-flex align-items-center gap-2">
                    <li>
                        <a href="{{ route('admin.index') }}" class="text-muted text-decoration-none">Dashboard</a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><span class="text-muted">Class</span></li>
                </ul>
            </div>

            <div class="wg-box">
                <form action="{{ route('class.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="tenLopHoc" class="form-label required">Tên lớp</label>
                            <input type="text" class="form-control" id="tenLopHoc" name="class_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="giaoVien" class="form-label">Giáo viên</label>
                            <select class="form-select" id="giaoVien" name="teacher_id">
                                <option value="" hidden>Chọn giáo viên</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->user_id }}">{{ $teacher->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="maLopHoc" class="form-label required">Mã lớp</label>
                            <input type="text" class="form-control" id="maLopHoc" name="class_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nhinThuc" class="form-label required">Hình thức</label>
                            <select class="form-select" id="nhinThuc" name="learning_format" required>
                                <option value="" hidden>Chọn giá trị</option>
                                @foreach ($learningFormats as $format)
                                    <option value="{{ $format['id'] }}">{{ $format['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="khoaHoc" class="form-label required">Khóa học</label>
                            <select class="form-select" id="khoaHoc" name="course_id" required>
                                <option value="" hidden>Chọn khóa học</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trangThai" class="form-label required">Trạng thái</label>
                            <select class="form-select" id="trangThai" name="status" required>
                                <option value="" hidden>Chọn giá trị</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="soNgay" class="form-label required">Số ngày</label>
                            <input type="number" class="form-control" id="soNgay" name="active_days" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="gia" class="form-label required">Giá</label>
                            <input type="number" class="form-control" id="gia" name="price" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="giaKhuyenMai" class="form-label">Giá KM</label>
                            <input type="number" class="form-control" id="giaKhuyenMai" name="discount_price">
                        </div>

                        <div class="col-12 mb-3">
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

                        <div class="col-12 mb-3">
                            <label for="moTa" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="moTa" name="description" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-4">
                        <button type="submit" class="btn btn-success">Thêm lớp</button>
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
            // Form submission handling
            $('form').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serializeArray();
                let schedule = selected;

                console.log('Schedule before sending:', schedule);

                if (Array.isArray(schedule)) {
                    schedule.forEach(function(day) {
                        formData.push({
                            name: 'schedule[]',
                            value: day
                        });
                    });
                } else {
                    console.error('Schedule is not an array:', schedule);
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert('Thêm lớp học thành công!');
                            window.location.href = '{{ route('class.index') }}';
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

            // Custom dropdown handling
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