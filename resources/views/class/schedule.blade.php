    @extends('layouts.admin')

    @push('styles')
        <style>
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .table th,
            .table td {
                vertical-align: middle;
                text-align: center;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .text-start td {
                text-align: left !important;
            }

            /*  */
            .schedule-content {
                padding: 10px;
            }

            .content-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .content-list li {
                margin-bottom: 8px;
                padding: 8px 12px;
                border-radius: 4px;
                font-size: 0.85rem;
                /* Chữ nhỏ hơn, khoảng 13-14px tùy phông chữ */
            }

            .course-name {
                background-color: #e3f2fd;
                /* Xanh nhạt */
                font-weight: bold;
                color: #1a5276;
                /* Màu xanh đậm hơn cho độ tương phản */
            }

            .subject-name {
                background-color: #e8f5e9;
                /* Xanh lá nhạt */
                color: #1e8449;
                /* Xanh lá đậm hơn */
            }

            .lesson-name {
                background-color: #fff3e0;
                /* Cam nhạt */
                color: #784212;
                /* Cam đậm hơn */
            }

            .no-content {
                color: #95a5a6;
                font-style: italic;
                font-size: 0.85rem;
                /* Chữ nhỏ hơn cho phần "Không có nội dung" */
            }

            /* Responsive design */
            @media (max-width: 576px) {
                .content-list li {
                    font-size: 0.75rem;
                    /* Chữ nhỏ hơn nữa trên mobile (~12px) */
                }

                .no-content {
                    font-size: 0.75rem;
                }
            }

            /*  */
            .schedule-row {
                vertical-align: middle;
            }

            .teacher-cell {
                position: relative;
            }

            .edit-teacher-btn {
                border: none;
                background: transparent;
                padding: 0;
                margin-left: 5px;
                color: #4a6cf7;
                opacity: 0.7;
            }

            .edit-teacher-btn:hover {
                opacity: 1;
            }

            .teacher-name {
                display: inline-block;
            }

            .select2-container {
                width: 100% !important;
            }

            .inline-edit {
                display: none;
                margin-top: 10px;
            }

            .edit-controls {
                margin-top: 5px;
            }

            .dropdown-menu {
                padding: 15px;
                min-width: 280px;
            }

            /* Mới thêm */
            .teacher-cell {
                position: relative;
                min-width: 150px;
            }

            .edit-teacher-btn {
                background: transparent;
                border: none;
                padding: 0;
                margin-left: 5px;
                color: #fff;
                opacity: 0.7;
                font-size: 12px;
                cursor: pointer;
            }

            .edit-teacher-btn:hover {
                opacity: 1;
            }

            .teacher-display {
                display: flex;
                align-items: center;
            }

            .teacher-edit {
                margin-top: 8px;
            }

            .select2-container {
                width: 100% !important;
            }

            .edit-actions {
                display: flex;
                gap: 5px;
            }
        </style>
    @endpush

    @section('title', 'Lịch học - ' . $class->ten_lop)

    @section('content')
        <div class="main-content-inner">
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Lịch học: {{ $class->ten_lop }} ({{ $class->ma_lop }})</h3>
                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <div class="text-tiny">Dashboard</div>
                            </a>
                        </li>
                        <li><i class="icon-chevron-right"></i></li>
                        <li>
                            <a href="{{ route('schedules.index') }}">
                                <div class="text-tiny">Schedules</div>
                            </a>
                        </li>
                        <li><i class="icon-chevron-right"></i></li>
                        <li>
                            <div class="text-tiny">{{ $class->ten_lop }}</div>
                        </li>
                    </ul>
                </div>

                <!-- Danh sách lịch học -->
                <div class="wg-box mb-27">
                    <h4>Danh sách lịch học</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Giáo viên</th>
                                    <th>Thời gian</th>
                                    <th>Nội dung</th>
                                    <th>Ghi chú</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedules as $index => $schedule)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        {{-- Mới thêm --}}
                                        <td class="teacher-cell">
                                            <div class="teacher-display">
                                                <span class="btn btn-info btn-sm text-white">
                                                    {{ $schedule->giao_vien ?? ($class->giao_vien_phu_trach_chinh ?? 'Chưa có') }}
                                                </span>
                                                <button type="button" class="edit-teacher-btn"
                                                    onclick="toggleEditTeacher(this)" title="Sửa giáo viên">
                                                    <i class="icon-edit"></i>
                                                </button>
                                            </div>
                                            <div class="teacher-edit" style="display: none;">
                                                <form action="{{ route('schedules.update-teacher', $schedule->schedule_id) }}"
                                                    method="POST" class="inline-edit-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="giao_vien_id"
                                                        class="form-control form-control-sm select2-teacher">
                                                        @foreach ($teachers as $teacher)
                                                            <option value="{{ $teacher->user_id }}"
                                                                {{ $schedule->giao_vien_id == $teacher->user_id ? 'selected' : '' }}>
                                                                {{ $teacher->ten_giao_vien }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="edit-actions mt-2">
                                                        <button type="submit" class="btn btn-primary btn-sm">Lưu</button>
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            onclick="cancelEditTeacher(this)">Hủy</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($schedule->start_date)->format('d/m/Y') }}
                                            ({{ ucfirst(\Carbon\Carbon::parse($schedule->start_date)->locale('vi')->isoFormat('dddd')) }})
                                            <br>
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </td>
                                        <td class="text-start schedule-content">
                                            @if ($schedule->ten_khoa_hoc || $schedule->ten_mon_hoc || $schedule->ten_bai_hoc)
                                                <ul class="content-list">
                                                    @if ($schedule->ten_khoa_hoc)
                                                        <li class="course-name">{{ $schedule->ten_khoa_hoc }}</li>
                                                    @endif
                                                    @if ($schedule->ten_mon_hoc)
                                                        <li class="subject-name">{{ $schedule->ten_mon_hoc }}</li>
                                                    @endif
                                                    @if ($schedule->ten_bai_hoc)
                                                        <li class="lesson-name">{{ $schedule->ten_bai_hoc }}</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <span class="no-content">Không có nội dung</span>
                                            @endif
                                        </td>
                                        <td>{{ $schedule->ghi_chu ?? 'Không có' }}</td>
                                        <td>
                                            <span class="btn btn-success btn-sm">Đã duyệt</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Chưa có lịch học</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Khởi tạo Select2
                $('.select2-teacher').select2({
                    width: '100%',
                    placeholder: 'Chọn giáo viên',
                    allowClear: true,
                    language: {
                        noResults: function() {
                            return "Không tìm thấy giáo viên";
                        },
                        searching: function() {
                            return "Đang tìm kiếm...";
                        }
                    }
                });

                // Xử lý submit form trực tiếp bằng AJAX
                $('.inline-edit-form').on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const url = form.attr('action');
                    const cell = form.closest('.teacher-cell');
                    const displayDiv = cell.find('.teacher-display');
                    const editDiv = cell.find('.teacher-edit');
                    const teacherSpan = displayDiv.find('.btn');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: form.serialize(),
                        beforeSend: function() {
                            // Hiển thị loading nếu cần
                            teacherSpan.html(
                                '<i class="icon-spinner icon-spin"></i> Đang cập nhật...');
                        },
                        success: function(response) {
                            // Cập nhật tên giáo viên hiển thị
                            const selectedText = form.find('select option:selected').text();
                            teacherSpan.text(selectedText);

                            // Ẩn form chỉnh sửa
                            editDiv.hide();
                            displayDiv.show();

                            // Hiển thị thông báo thành công (nếu có)
                            if (response.message) {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            // Xử lý lỗi
                            alert('Có lỗi xảy ra khi cập nhật giáo viên');
                            console.error(xhr);
                        }
                    });
                });
            });

            // Hàm chuyển đổi giữa hiển thị và chỉnh sửa
            function toggleEditTeacher(button) {
                const cell = button.closest('.teacher-cell');
                const displayDiv = cell.querySelector('.teacher-display');
                const editDiv = cell.querySelector('.teacher-edit');

                displayDiv.style.display = 'none';
                editDiv.style.display = 'block';

                // Khởi tạo lại Select2 nếu cần
                $(editDiv).find('.select2-teacher').select2('open');
            }

            // Hàm hủy chỉnh sửa
            function cancelEditTeacher(button) {
                const cell = button.closest('.teacher-cell');
                const displayDiv = cell.querySelector('.teacher-display');
                const editDiv = cell.querySelector('.teacher-edit');

                editDiv.style.display = 'none';
                displayDiv.style.display = 'flex';
            }
        </script>
    @endpush
