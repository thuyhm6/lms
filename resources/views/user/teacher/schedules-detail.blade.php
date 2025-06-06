@extends('layouts.app')
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
        #assignmentModal {
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


        /*  */
        #lessonModal {
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
            font-family: Arial, sans-serif;
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
            font-family: Arial, sans-serif;
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

        /*  */
        .lesson-btn {
            display: inline-block;
            margin: 4px 4px 0 0;
            font-size: 14px;
            background-color: #17a2b8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .lesson-btn:hover {
            background-color: #138496;
        }

        /* lesson */
        /* Modal overlay */
        .modalLesson {
            display: none; /* Ẩn mặc định */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Lớp phủ tối */
            z-index: 1000; /* Đảm bảo modal nằm trên cùng */
            display: flex; /* Sử dụng flex để căn giữa */
            justify-content: center; /* Căn giữa chiều ngang */
            align-items: center; /* Căn giữa chiều dọc */
        }

        /* Modal content */
        .modalLesson-content {
            background-color: #fff;
            border-radius: 8px;
            max-width: 90%;
            max-height: 80vh; /* Giới hạn chiều cao tối đa */
            width: 1300px; /* Kích thước cố định */
            height: 1000px; /* Tự động điều chỉnh chiều cao */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden; /* Ngăn tràn nội dung */
            position: relative; /* Đảm bảo nội dung con được căn trong */
            display: flex; /* Sử dụng flex cho nội dung bên trong */
            flex-direction: column; /* Sắp xếp theo cột */
            transform: translate(-50%, -50%); /* Căn giữa chính xác hơn */
            top: 50%; /* Di chuyển xuống 50% chiều cao màn hình */
            left: 50%; /* Di chuyển sang 50% chiều rộng màn hình */
        }

        /* Modal header */
        .modalLesson-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
        }

        /* Lesson name and data */
        #lessonName {
            font-weight: bold;
            margin-right: 10px;
        }

        #lessonData {
            flex-grow: 1;
            overflow: auto;
            /* Allow scrolling if slide content overflows */
            /* max-height: 60vh; */
            /* Limit height for slide content */
            padding: 20px;
        }

        /* Close button */
        .modalLesson-header span {
            cursor: pointer;
            font-size: 24px;
            color: #333;
            transition: color 0.2s;
        }

        .modalLesson-header span:hover {
            color: #ff0000;
            /* Red on hover for close button */
        }

        /* Styling for slide content in lessonData */
        #lessonData img,
        #lessonData video,
        #lessonData iframe {
            max-width: 100%;
            height: 100%;
            display: block;
            margin: 0 auto;
            /* Center media */
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .modalLesson-content {
                width: 95%;
                max-height: 90%;
            }

            .modalLesson-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            #lessonName,
            #lessonData {
                margin-bottom: 10px;
            }

            #lessonData {
                max-height: 50vh;
                /* Adjust for smaller screens */
            }
        }
    </style>
    @section('content')
        <main class="pt-90">
            <div class="mb-4 pb-4"></div>
            <section class="my-account container">
                <h2 class="page-title">My Account</h2>
                <div class="row">
                    <div class="col-lg-3">
                        @include('user.account-nav')
                    </div>
                    <div class="col-lg-9">
                        <div class="page-content my-account__dashboard">
                            {{-- <h2>LỚP HỌC QUẢN LÝ CỦA GIÁO VIÊN <span class="text-primary"> {{ Auth::user()->full_name }}</span></h2> --}}
                            <div class="wg-box">

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (isset($scheduleData) && $scheduleData)
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <h4>Nội dung học</h4>
                                        <button type="button" data-id="{{ $scheduleData->id }}"
                                            onclick="openAssignmentModal(this)">Giao bài tập</button>

                                        <!-- Modal Giao Bài Tập -->
                                        <div class="modal" id="assignmentModal" onclick="closeAssignmentModal(event)">
                                            <div class="modal-content" onclick="event.stopPropagation()">
                                                <div class="modal-header">
                                                    <strong >Giao bài tập</strong>
                                                    <span onclick="closeAssignmentModal(event)" style="cursor:pointer;">×</span>
                                                </div>
                                                <form id="assignmentForm" enctype="multipart/form-data">
                                                    <!-- Hoặc nếu bạn đã biết schedule_id từ context -->
                                                    <input type="hidden" name="schedule_id" id="scheduleIdInput"
                                                        value="{{ $scheduleData->id }}">

                                                    <!-- Nhóm file + deadline cùng 1 hàng -->
                                                    <div class="form-row"
                                                        style="display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap;">
                                                        <!-- File -->
                                                        <div class="form-group" style="flex: 1;">
                                                            <label for="assignmentFile">Chọn file bài tập <span
                                                                    class="required">*</span></label>
                                                            <input type="file" id="assignmentFile" name="assignment_file"
                                                                class="form-control" required>
                                                        </div>
                                                        <!-- Deadline -->
                                                        <div class="form-group" style="flex: 1;">
                                                            <label for="assignmentDeadline">Hạn nộp bài <span
                                                                    class="required">*</span></label>
                                                            <input type="datetime-local" id="assignmentDeadline" name="deadline"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="assignmentDesc">Mô tả</label>
                                                        <textarea id="assignmentDesc" name="description" class="form-control" rows="3" placeholder="Nhập mô tả bài tập"></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-cancel"
                                                            onclick="closeAssignmentModal(event)">Đóng</button>
                                                        <button type="submit" class="btn btn-submit" id="submitHomework"
                                                            onclick="saveFormHomework(event)">Giao bài tập</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($lessons->isNotEmpty())
                                        <ul>
                                            @foreach ($lessons as $lesson)
                                                {{-- <li>{{ $lesson->lesson_name }}</li> --}}
                                                <li>
                                                    <button type="button" class="lesson-btn" data-id="{{ $lesson->id }}"
                                                        onclick="openModalLesson(this)">
                                                        {{ $lesson->lesson_name }}
                                                    </button>
                                                </li>
                                                {{-- Hiển thị nội dung chi tiết hơn của bài học nếu cần --}}
                                            @endforeach
                                        </ul>
                                        <!-- Modal Lesson -->
                                        <div class="modalLesson" id="lessonModal" onclick="closeLessonModal(event)">
                                            <div class="modalLesson-content" onclick="event.stopPropagation()">
                                                <div class="modalLesson-header">
                                                    <strong>Bài học: </strong>
                                                    <div id="lessonName"></div>
                                                    <span onclick="closeLessonModal(event)">×</span>
                                                </div>
                                                <div id="lessonData"></div>
                                            </div>
                                        </div>
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
                                        <form id="attendanceForm"
                                            action="{{ route('teacher.attendance.save', $scheduleData->id) }}" method="POST">
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
                                                                    <input type="hidden"
                                                                        name="attendance[{{ $student->id }}][status]"
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
                                                <button type="button" class="btn btn-primary btn-lg"
                                                    onclick="submitAttendance()">
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
            </section>
        </main>

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
                fetch(`/teacher/schedule/students/add`, {
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
                // selectedLessons = [];
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
                fetch('/teacher/schedule/students/save', {
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


            // Modal Lesson

            let lessonId; // Biến toàn cục để lưu lessonId
            function closeLessonModal(event) {
                document.getElementById("lessonModal").style.display = "none";
                lessonId = null; // Reset lessonId để tránh sử dụng sai
            }
            // Mở modal cho bài học
            function openModalLesson(button) {
                const lessonId = button.getAttribute('data-id');
                const modal = document.getElementById('lessonModal');

                // Gửi yêu cầu để lấy nội dung bài học
                fetch(`/teacher/lesson/${lessonId}/detail`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hiển thị nội dung bài học trong modal
                            document.getElementById('lessonName').innerText = data.lesson.lesson_name;
                            document.getElementById('lessonModal').style.display = 'block';

                            // Tạo đường dẫn đến file
                            var lessonData = document.getElementById('lessonData');
                            lessonData.innerHTML = `<iframe width="100%" height="800px" 
                                           src="/${data.lesson.file_link}/index.html" 
                                           frameborder="0"></iframe>`;
                        } else {
                            alert('Không thể tải nội dung bài học.');
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        alert('Đã xảy ra lỗi khi tải nội dung bài học.');
                    });
            }

            // Modal bài tập

            let assignmentId; // Biến toàn cục để lưu assignmentId
            function closeAssignmentModal(event) {
                document.getElementById("assignmentModal").style.display = "none";
                assignmentId = null; // Reset lessonId để tránh sử dụng sai
            }
            // Mở modal cho bài tập
            function openAssignmentModal(button) {
                // const lessonId = button.getAttribute('data-id');
                const modal = document.getElementById('assignmentModal');

                modal.style.display = 'block';

                // Gửi yêu cầu để lấy nội dung bài học
                // fetch(`/teacher/lesson/${lessonId}/detail`)
                //     .then(response => response.json())
                //     .then(data => {
                //         if (data.success) {
                //             // Hiển thị nội dung bài học trong modal
                //             // document.getElementById('lessonName').innerText = data.lesson.lesson_name;
                //             document.getElementById('assignmentModal').style.display = 'block';
                //         } else {
                //             alert('Không thể tải nội dung bài học.');
                //         }
                //     })
                //     .catch(error => {
                //         console.error('Lỗi:', error);
                //         alert('Đã xảy ra lỗi khi tải nội dung bài học.');
                //     });
            }

            // id="submitHomework" onclick="saveFormHomework(event)"
            function saveFormHomework(event) {
                event.preventDefault(); // chặn form tự submit (GET)

                const form = document.getElementById('assignmentForm');
                const submitButton = document.getElementById('submitHomework');

                // Validate form trước khi gửi
                if (!validateAssignmentForm()) {
                    return;
                }

                // Disable button để tránh double click
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';

                // Tạo FormData object để gửi file và data
                const formData = new FormData(form);

                // Gửi request
                fetch('/teacher/homeworks', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                '',
                            // Không set Content-Type vì FormData tự động set multipart/form-data
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Thành công
                            showSuccessMessage(data.message || 'Giao bài tập thành công!');

                            // Reset form
                            form.reset();

                            // Đóng modal
                            closeAssignmentModal();

                            // Reload danh sách bài tập hoặc cập nhật UI
                            if (typeof refreshAssignmentList === 'function') {
                                refreshAssignmentList();
                            } else {
                                // Hoặc reload trang
                                window.location.reload();
                            }
                        } else {
                            throw new Error(data.message || 'Có lỗi xảy ra khi giao bài tập');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorMessage(error.message || 'Có lỗi xảy ra khi giao bài tập');
                    })
                    .finally(() => {
                        // Enable lại button
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Giao bài tập';
                    });
            }

            // Hàm validate form
            function validateAssignmentForm() {
                const form = document.getElementById('assignmentForm');
                const fileInput = document.getElementById('assignmentFile');
                const deadlineInput = document.getElementById('assignmentDeadline');

                // Check file được chọn
                if (!fileInput.files || fileInput.files.length === 0) {
                    showErrorMessage('Vui lòng chọn file bài tập');
                    fileInput.focus();
                    return false;
                }

                // Check kích thước file (ví dụ: max 10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB
                if (fileInput.files[0].size > maxSize) {
                    showErrorMessage('Kích thước file không được vượt quá 10MB');
                    return false;
                }

                // Check định dạng file (tuỳ chọn)
                const allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg',
                    'image/png',
                    'image/gif'
                ];

                if (!allowedTypes.includes(fileInput.files[0].type)) {
                    showErrorMessage('Định dạng file không được hỗ trợ. Chỉ chấp nhận PDF, Word, hình ảnh');
                    return false;
                }

                // Check deadline
                if (!deadlineInput.value) {
                    showErrorMessage('Vui lòng chọn hạn nộp bài');
                    deadlineInput.focus();
                    return false;
                }

                // Check deadline phải sau thời gian hiện tại
                const deadline = new Date(deadlineInput.value);
                const now = new Date();

                if (deadline <= now) {
                    showErrorMessage('Hạn nộp bài phải sau thời gian hiện tại');
                    deadlineInput.focus();
                    return false;
                }

                return true;
            }

            // Hàm hiển thị thông báo thành công
            function showSuccessMessage(message) {
                // Sử dụng thư viện notification có sẵn hoặc tự tạo
                if (typeof toastr !== 'undefined') {
                    toastr.success(message);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: message,
                        timer: 3000
                    });
                } else {
                    alert(message);
                }
            }

            // Hàm hiển thị thông báo lỗi
            function showErrorMessage(message) {
                // Sử dụng thư viện notification có sẵn hoặc tự tạo
                if (typeof toastr !== 'undefined') {
                    toastr.error(message);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: message
                    });
                } else {
                    alert(message);
                }
            }
        </script>
    @endsection
