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

        .dropdown-toggle::after {
            margin-left: 0.4rem;
        }

        .dropdown-menu {
            z-index: 9999;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            /* Nền mờ đen */
        }

        .modal-content {
            background-color: #fff;
            margin: 3% auto;
            padding: 20px;
            border-radius: 5px;
            width: 600px;
            max-width: 800px;
            animation: slideDown 0.3s ease-out;
        }

        .modal-header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 6px;
            box-sizing: border-box;
        }

        .modal-footer {
            text-align: right;
            margin-top: 15px;
        }

        .btn {
            padding: 8px 16px;
            margin-left: 5px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-secondary {
            background-color: #ccc;
            color: black;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }

        .modal-header span {
            cursor: pointer;
            font-weight: normal;
        }

        .label-row {
            display: flex;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-note {
            background: #00b7f1;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #eee;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
        }

        .required {
            color: red;
        }
    </style>
@endpush

@section('title', 'Danh sách học sinh - ' . $class->ten_lop)

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách học sinh: {{ $class->ten_lop }} ({{ $class->ma_lop }})</h3>
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
                        <a href="{{ route('schedules.index') }}">
                            <div class="text-tiny">Schedules</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">{{ $class->ten_lop }}</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="d-flex justify-content-between mg-3">
                    <h3>dsds</h3>
                    <!-- Nút mở modal -->
                    <button class="btn btn-success btn-lg" data-id="{{ $class->id }}" onclick="openModal(this)">Thêm
                        học sinh</button>
                    <!-- Modal -->
                    <div class="modal" id="modal" onclick="closeModal(event)">
                        <div class="modal-content" onclick="event.stopPropagation()">
                            <div class="modal-header">
                                <div>Thêm học sinh</div>
                                <span onclick="closeModal(event)">×</span>
                            </div>

                            <div class="form-note">
                                Thêm lịch học cho lớp: <strong><span id="className"></span> (<span
                                        id="classCode"></span>)</strong>
                            </div>

                            <div class="form-group">
                                <label>Học sinh <span class="required">*</span></label>
                                <select id="studentSelect" class="form-control">
                                    <option value="">Chọn giá trị</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Ghi chú</label>
                                <input id="note" placeholder="Nhập ghi chú">
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-cancel" onclick="closeModal(event)">Đóng</button>
                                <button class="btn btn-submit" id="submitButton" onclick="saveForm(event)">Thêm học sinh</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">STT</th>
                                <th style="width: 20%;">Họ và tên</th>
                                <th>Điện thoại</th>
                                <th>Trường học</th>
                                <th>Tên phụ huynh</th>
                                <th>Ghi chú</th>
                                <th style="width: 7%;">Trạng thái</th>
                                <th>Ngày tham gia</th>
                                <th>Ngày cập nhật</th>
                                <th style="width: 12%;">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-start">{{ $student->ho_ten ?? 'Chưa có' }}</td>
                                    <td>{{ $student->dien_thoai ?? 'Chưa có' }}</td>
                                    <td>{{ $student->truong_hoc ?? 'Chưa có' }}</td>
                                    <td>{{ $student->ten_phu_huynh }}</td>
                                    <td>{{ $student->ghi_chu ?? 'Chưa có' }}</td>
                                    <td>
                                        @if ($student->trang_thai === 'active')
                                            <span class="btn btn-sm btn-success">Hoạt động</span>
                                        @else
                                            <span class="btn btn-sm btn-danger">Ngừng hoạt động</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($student->ngay_tham_gia)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $student->ngay_cap_nhat ? \Carbon\Carbon::parse($student->ngay_cap_nhat)->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                                    </td>
                                    <td class="position-relative">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Chức năng
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Xem chi tiết</a></li>
                                                <li><a class="dropdown-item" href="#">Sửa</a></li>
                                                <li><a class="dropdown-item text-danger" href="#">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có học sinh nào trong lớp này</td>
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
        let classId; // Biến toàn cục để lưu classId

        function openModal(button) {
            classId = button.getAttribute('data-id');
            document.getElementById('modal').style.display = 'block';

            // Xóa các lựa chọn cũ
            const select = document.getElementById('studentSelect');
            select.innerHTML = '<option value="">Chọn giá trị</option>';

            // Gửi request để lấy danh sách giáo viên
            fetch(`/students/add`, {
                    method: 'POST', // hoặc GET tùy thuộc vào yêu cầu
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // CSRF token
                    },
                    body: JSON.stringify({
                        classId: classId
                    }) // Gửi classId dưới dạng JSON
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ server:', data);

                    document.getElementById('className').textContent = data.class.class_name;
                    document.getElementById('classCode').textContent = data.class.class_code;

                    // Cập nhật danh sách giáo viên trong <select>
                    var studentSelect = document.getElementById('studentSelect');
                    studentSelect.innerHTML = '<option value="" readonly>Chọn giá trị</option>'; // Reset danh sách
                    data.students.forEach(student => {
                        var option = document.createElement('option');
                        option.value = student.user_id;
                        option.textContent = `${student.full_name} `;
                        // Chọn giáo viên nếu khớp với selectedTeacher
                        if (data.studentSelect && student.user_id === data.studentSelect.user_id) {
                            option.selected = true;
                        }
                        studentSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Lỗi khi tải giáo viên:', error);
                    // alert('Không thể tải danh sách giáo viên');
                });
        }

        function saveForm(event) {
            event.preventDefault(); // chặn form tự submit (GET)
            var studentId = document.getElementById('studentSelect').value;
            var note = document.getElementById('note').value;

            // Kiểm tra dữ liệu
            if (!classId) {
                alert('Không xác định được lớp học. Vui lòng mở lại modal.');
                return;
            }
            
            if (!studentId) {
                alert('Vui lòng chọn học sinh.');
                return;
            }

            // Thu thập dữ liệu form
            var formData = {
                classId: classId,
                student_id: studentId,
                note: note || null, // Gửi note, mặc định null nếu rỗng
            };

            // alert(JSON.stringify(formData, null, 2))

            // Gửi dữ liệu qua fetch
            fetch('/save-student-selection', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ /save-class-selection:', data);
                    if (data.success) {
                        alert('Dữ liệu đã được lưu thành công!');
                        closeModal(); // Đóng modal sau khi lưu
                        window.location.reload(); // Tải lại trang
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể lưu dữ liệu.'));
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Lỗi server. Vui lòng thử lại sau.');
                });
        }

        function closeModal(event) {
            document.getElementById("modal").style.display = "none";
            selectedLessons = [];
            classId = null; // Reset classId để tránh sử dụng sai
        }
    </script>
@endpush
