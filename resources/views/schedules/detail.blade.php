@extends('layouts.admin')
@section('title', 'Danh sách lịch dạy của giáo viên')
@push('styles')
    <style>
        .form-label.required::after {
            content: " (*)";
            color: red;
        }

        .table-responsive {
            margin-bottom: 30px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 5px;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: black;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .dropdown-menu {
            min-width: 150px;
        }

        /* Định dạng bảng để minh họa */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        /* Định dạng cho nút công tắc */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 90px;
            height: 26px;
        }

        /* Ẩn checkbox mặc định */
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* Thiết kế nút trượt */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f44336;
            /* Màu đỏ cho Vắng mặt */
            transition: .4s;
            border-radius: 26px;
            text-align: right;
            padding: 0 8px;
            line-height: 26px;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #28a745;
            /* Màu xanh cho Có mặt */
            text-align: left;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #28a745;
        }

        input:checked+.slider:before {
            transform: translateX(64px);
        }

        /* Text cho trạng thái */
        .slider:after {
            content: "Vắng mặt";
        }

        input:checked+.slider:after {
            content: "Có mặt";
        }

        /*  */

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
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
            margin: 5% auto;
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
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
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
@section('title', 'Danh sách lịch dạy của giáo viên')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi tiết Lịch học</h3>
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
                        <div class="text-tiny">Class</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                {{-- <div class="d-flex justify-content-between mg-3">
                    <h3>Chi tiết Lịch học</h3>
                    <a href="{{ route('class.create') }}"><button class="btn btn-success btn-lg">+ Add</button></a>
                </div> --}}

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (isset($scheduleData) && $scheduleData)
                    <h4>Nội dung học</h4>
                    @if ($lessons->isNotEmpty())
                        <ul>
                            @foreach ($lessons as $lesson)
                                <li>{{ $lesson->lesson_name }}</li>
                                {{-- Hiển thị nội dung chi tiết hơn của bài học nếu cần --}}
                            @endforeach
                        </ul>
                    @else
                        <p>Chưa có nội dung học cho buổi này.</p>
                    @endif

                    <hr>

                    <h4 class="mb-4 text-primary d-flex justify-content-between align-items-center">
                        Danh sách học sinh
                        <button type="button" class="btn btn-success" data-id="{{ $scheduleData->id }}"
                            onclick="openModal(this)">
                            <i class="bi bi-plus-circle me-2"></i>Thêm học sinh
                        </button>
                    </h4>

                    @if (isset($students) && is_a($students, \Illuminate\Support\Collection::class) && $students->isNotEmpty())
                        <form id="attendanceForm" action="{{ route('attendance.save', $scheduleData->id) }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col" class="text-center">Ảnh</th>
                                            <th scope="col" class="text-center">Tên học sinh</th>
                                            <th scope="col" class="text-center">Số điện thoại</th>
                                            <th scope="col" class="text-center">Trạng thái điểm danh</th>
                                            <th scope="col" class="text-center">Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>
                                                    <img src="{{ $student->image && $student->image != 'default.png' ? asset('uploads/avatars/' . $student->image) : '' }}"
                                                        class="" alt="parent avatar" width="100px">
                                                </td>
                                                <td>{{ $student->full_name }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td>
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" class="attendance-toggle"
                                                            data-student-id="{{ $student->id }}"
                                                            onchange="updateAttendance(this)"
                                                            {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] == 1 ? 'checked' : '' }}>
                                                        <span class="slider"></span>
                                                    </label>
                                                    <input type="hidden" name="attendance[{{ $student->id }}][status]"
                                                        id="attendance-value-{{ $student->id }}"
                                                        value="{{ isset($attendance[$student->id]) ? $attendance[$student->id]['status'] : 0 }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control"
                                                        name="note[{{ $student->id }}]"
                                                        value="{{ isset($attendance[$student->id]) ? $attendance[$student->id]['note'] : '' }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-primary btn-lg" onclick="submitAttendance()">
                                    <i class="bi bi-save me-2"></i>Lưu điểm danh
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning text-center" role="alert">
                            Không có học sinh trong lớp này.
                        </div>
                    @endif
                @else
                    <p>Không tìm thấy lịch học.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="modal" onclick="closeModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>Thêm học sinh</div>
                <span onclick="closeModal(event)">×</span>
            </div>

            <div class="form-note">
                Lịch học ngày: {{ \Carbon\Carbon::parse($scheduleData->start_date)->format('d/m/Y') }}
                ({{ ucfirst(\Carbon\Carbon::parse($scheduleData->start_date)->locale('vi')->isoFormat('dddd')) }})
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
@endsection
@push('scripts')
    <script>
        function updateAttendance(checkbox) {
            const studentId = checkbox.getAttribute('data-student-id');
            const hiddenInput = document.getElementById(`attendance-value-${studentId}`);

            if (checkbox.checked) {
                hiddenInput.value = "1";
            } else {
                hiddenInput.value = "0";
            }
        }

        function submitAttendance() {
            if (!confirm('Bạn có chắc chắn muốn lưu điểm danh?')) {
                return;
            }
            const form = document.getElementById('attendanceForm');
            const url = form.getAttribute('action');
            const formData = new FormData(form);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);

                        // Cập nhật UI dựa trên dữ liệu từ form
                        document.querySelectorAll('.attendance-toggle').forEach(checkbox => {
                            const studentId = checkbox.getAttribute('data-student-id');
                            const hiddenInput = document.getElementById(`attendance-value-${studentId}`);
                            const noteInput = checkbox.closest('tr').querySelector('.note-input');

                            if (hiddenInput) {
                                // Cập nhật trạng thái checkbox
                                checkbox.checked = hiddenInput.value === '1';
                                // Cập nhật hiển thị trạng thái (nếu có)
                                const statusSpan = checkbox.closest('td').querySelector('.status-text');
                                if (statusSpan) {
                                    statusSpan.textContent = hiddenInput.value === '1' ? 'Có mặt' : 'Vắng';
                                }
                            }
                            if (noteInput) {
                                // Cập nhật ghi chú
                                noteInput.value = noteInput.value || ''; // Giữ giá trị hiện tại
                            }
                        });
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể lưu điểm danh.'));
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Đã xảy ra lỗi khi gửi yêu cầu. Vui lòng thử lại sau.');
                    window.location.reload(); // Tải lại trang
                });
        }

        let scheduleId; // Biến toàn cục để lưu classId
        function openModal(button) {
            // classId = button.getAttribute('data-id');
            scheduleId = button.getAttribute('data-id');
            document.getElementById('modal').style.display = 'block';

            // Xóa các lựa chọn cũ
            const select = document.getElementById('studentSelect');
            select.innerHTML = '<option value="">Chọn giá trị</option>';

            //Gửi request để lấy danh sách giáo viên
            fetch(`/schedule/students/add`, {
                    method: 'POST', // hoặc GET tùy thuộc vào yêu cầu
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // CSRF token
                    },
                    body: JSON.stringify({
                        scheduleId: scheduleId
                    }) // Gửi classId dưới dạng JSON
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ server:', data);

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
                    alert('Không thể tải danh sách giáo viên');
                });
        }

        function closeModal(event) {
            document.getElementById("modal").style.display = "none";
            selectedLessons = [];
            scheduleId = null; // Reset classId để tránh sử dụng sai
        }

        function saveForm(event) {
            event.preventDefault(); // chặn form tự submit (GET)
            var studentId = document.getElementById('studentSelect').value;
            var note = document.getElementById('note').value;

            // Kiểm tra dữ liệu
            if (!scheduleId) {
                alert('Không xác định được lịch học. Vui lòng mở lại modal.');
                return;
            }

            if (!studentId) {
                alert('Vui lòng chọn học sinh.');
                return;
            }

            // Thu thập dữ liệu form
            var formData = {
                scheduleId: scheduleId,
                student_id: studentId,
                note: note || null, // Gửi note, mặc định null nếu rỗng
            };

            // alert(JSON.stringify(formData, null, 2))

            // Gửi dữ liệu qua fetch
            fetch('/schedule/students/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Phản hồi từ /schedule/students/save:', data);
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
    </script>
@endpush
