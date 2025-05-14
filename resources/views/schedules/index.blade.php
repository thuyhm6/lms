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

        /*  */
        #filterForm {
            margin-bottom: 20px;
        }

        #filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        #filter-container .filter-item {
            display: flex;
            flex-direction: column;
            min-width: 160px;
        }

        #filter-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        #btn-search,
        #btn-reset {
            height: 38px;
            padding: 0 15px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }

        #btn-search {
            background-color: #3490dc;
            color: white;
        }

        #btn-reset {
            background-color: #e3342f;
            color: white;
        }

        #filterForm select,
        #filterForm input[type="text"] {
            height: 38px;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
@endpush
@section('title', 'Lịch học')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Lịch học</h3>
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
                        <div class="text-tiny">Schedules</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box mb-10">
                <div id="schedule-filter" class="flex-grow">
                    <form id="filterForm" method="GET" action="{{ route('schedules.index') }}">
                        <div id="filter-container">
                            <div class="filter-item">
                                <label for="keyword">Tìm kiếm</label>
                                <input type="text" id="keyword" placeholder="Mã lớp, tên lớp..." name="keyword"
                                    value="{{ request('keyword') }}">
                            </div>
                            <div class="filter-item">
                                <label for="truong_tim_kiem">Trường tìm kiếm</label>
                                <select id="truong_tim_kiem" name="truong_tim_kiem">
                                    <option value="">Chọn trường</option>
                                    @foreach ($searchFields as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request('truong_tim_kiem') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="giao_vien">Giáo viên</label>
                                <select id="giao_vien" name="giao_vien">
                                    <option value="">Tất cả</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->user_id }}"
                                            {{ request('giao_vien') == $teacher->user_id ? 'selected' : '' }}>
                                            {{ $teacher->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai">
                                    <option value="" disabled selected>Tất cả</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang học
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Kết thúc
                                    </option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="hinh_thuc">Hình thức học</label>
                                <select id="hinh_thuc" name="hinh_thuc">
                                    <option value="">Tất cả</option>
                                    @foreach ($learningFormats as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request('hinh_thuc') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="per_page">Số dòng</label>
                                <select id="per_page" name="per_page">
                                    @foreach ([4, 10, 25, 50] as $option)
                                        <option value="{{ $option }}"
                                            {{ request('per_page', 4) == $option ? 'selected' : '' }}>
                                            {{ $option }} dòng
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="filter-buttons">
                                <button type="submit" id="btn-search" title="Tìm kiếm">
                                    <i class="icon-search"></i>
                                </button>
                                <a href="{{ route('schedules.index') }}" id="btn-reset" title="Xóa bộ lọc">
                                    Xóa lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="wg-box">
                {{-- <div class="d-flex justify-content-end mg-3">
                        <a href="{{ route('class.create') }}"><button class="btn btn-success btn-lg">+ Add</button></a>
                    </div> --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">STT</th>
                                <th style="width: 25%;">Lớp học</th>
                                <th>Giáo viên chính</th>
                                <th style="width: 10%;">Hình thức học</th>
                                <th>Học sinh</th>
                                {{-- <th>Số buổi học (có điểm danh)</th> --}}
                                <th style="width: 10%;">Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="classList">
                            @foreach ($classes as $index => $class)
                                {{-- Copy toàn bộ phần <tr> trong bảng bạn đã gửi vào đây --}}
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-start">
                                        <strong>{{ $class->ma_lop }}</strong><br>
                                        {{ $class->ten_lop }}
                                    </td>
                                    <td>
                                        <span class="btn btn-info btn-sm text-white">
                                            {{ $class->giao_vien_phu_trach_chinh }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="btn btn-danger btn-sm">
                                            {{ $class->hinh_thuc == 'online' ? 'Online' : 'Offline' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('class.students', $class->id) }}">Xem danh
                                            sách({{ $class->so_hoc_sinh }})</a>
                                    </td>
                                    <td class="text-center">
                                        @if ($class->trang_thai_lop_hoc === 0)
                                            <span class="btn btn-sm btn-warning text-white">Kết thúc</span>
                                        @elseif ($class->trang_thai_lop_hoc === 1)
                                            <span class="btn btn-sm btn-success">Đang học</span>
                                        @else
                                            <span class="btn btn-sm btn-secondary">Không rõ</span>
                                        @endif
                                    </td>
                                    <td class="position-relative">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Chức năng
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('class.schedule', $class->id) }}">
                                                        Danh sách lịch học
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" data-id="{{ $class->id }}"
                                                        onclick="openModal(this)">Thêm
                                                        lịch học</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Modal -->
                            <div class="modal" id="modal" onclick="closeModal(event)">
                                <div class="modal-content" onclick="event.stopPropagation()">
                                    <div class="modal-header">
                                        <div>Thêm lịch học</div>
                                        <span onclick="closeModal(event)">×</span>
                                    </div>

                                    <div class="form-note">
                                        Thêm lịch học cho lớp: <strong><span id="className"></span> (<span
                                                id="classCode"></span>)</strong>
                                    </div>

                                    <div class="label-row">
                                        <div class="form-group">
                                            <label>Ngày <span class="required">*</span></label>
                                            <input type="date" id="scheduleDate">
                                        </div>
                                        <div class="form-group">
                                            <label>Bắt đầu <span class="required">*</span></label>
                                            <input type="time" id="startTime">
                                        </div>
                                        <div class="form-group">
                                            <label>Kết thúc <span class="required">*</span></label>
                                            <input type="time" id="endTime">
                                        </div>
                                    </div>

                                    <div class="label-row">
                                        <div class="form-group">
                                            <label>Giáo viên <span class="required">*</span></label>
                                            <select id="teacherSelect" class="form-control">
                                                <option value="">Chọn giá trị</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Giáo viên trợ giảng</label>
                                            <select id="assistantTeacherSelect">
                                                <option value="" selected>Chọn giá trị</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Khóa học <span class="required">*</span></label>
                                        <input id="courseSelect" type="text" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="subjectSelect">Chủ đề: </label>
                                        <select id="subjectSelect">
                                            <option value="">Chọn chủ đề</option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="lessonContainer" style="display: none;">
                                        <label for="lessonSelect">Bài học: </label>
                                        <div class="custom-dropdown" id="customLessonDropdown">
                                            <div class="selected-items" id="selectedLessonItems">Chưa chọn</div>
                                            <div class="dropdown-options" id="lessonOptions"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Ghi chú</label>
                                        <input id="note" placeholder="Nhập ghi chú">
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-cancel" onclick="closeModal(event)">Đóng</button>
                                        <button class="btn btn-submit" id="submitButton" onclick="saveForm(event)">Thêm
                                            lịch
                                            học</button>
                                    </div>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>

                {{-- <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $coupons->links('pagination::bootstrap-5') }}
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let classId; // Biến toàn cục để lưu classId
        let selectedLessons = []; // Biến toàn cục để lưu danh sách lesson được chọn

        function openModal(button) {
            classId = button.getAttribute('data-id');
            // alert(classId);
            // console.log(classId);

            fetch('/classes/info', {
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
                    var teacherSelect = document.getElementById('teacherSelect');
                    teacherSelect.innerHTML = '<option value="" readonly>Chọn giá trị</option>'; // Reset danh sách
                    data.teachers.forEach(teacher => {
                        var option = document.createElement('option');
                        option.value = teacher.user_id;
                        option.textContent = `${teacher.full_name} (${teacher.teacher_code})`;
                        // Chọn giáo viên nếu khớp với selectedTeacher
                        if (data.selectedTeacher && teacher.user_id === data.selectedTeacher.user_id) {
                            option.selected = true;
                        }
                        teacherSelect.appendChild(option);
                    });

                    // Cập nhật danh sách khóa học dạng input
                    var courseSelect = document.getElementById('courseSelect');
                    if (data.selectedCourse) {
                        // Nếu có khóa học được chọn, hiển thị tên và mã khóa học
                        courseSelect.value = `${data.selectedCourse.course_name} (${data.selectedCourse.course_code})`;
                        // Lưu course_id vào một thuộc tính data để sử dụng sau
                        courseSelect.dataset.courseId = data.selectedCourse.id;
                        updateSubjectList(data.subjects);
                    } else {
                        // Nếu không có khóa học được chọn, hiển thị placeholder
                        courseSelect.value = 'Chưa có khóa học';
                        courseSelect.dataset.courseId = null;
                    }

                    // Cập nhật danh sách khóa học dạng list
                    // var courseSelect = document.getElementById('courseSelect');
                    // courseSelect.innerHTML = '<option value="">Chọn giá trị</option>';
                    // data.courses.forEach(course => {
                    //     console.log('Course ID:', course.id, 'Selected Course ID:', data.selectedCourse ? data
                    //         .selectedCourse.id : null);
                    //     var option = document.createElement('option');
                    //     option.value = course.id;
                    //     option.textContent = `${course.course_name} (${course.course_code})`;
                    //     if (data.selectedCourse && String(course.id) === String(data.selectedCourse.id)) {
                    //         option.selected = true;
                    //     }
                    //     courseSelect.appendChild(option);
                    // });

                    document.getElementById('modal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
        }

        // Cập nhật danh sách chủ đề
        function updateSubjectList(subjects) {
            var subjectSelect = document.getElementById('subjectSelect');
            var lessonContainer = document.getElementById('lessonContainer');
            subjectSelect.innerHTML = '<option value="">Chọn chủ đề</option>';
            lessonContainer.style.display = 'none'; // Ẩn danh sách bài học

            if (subjects && subjects.length > 0) {
                subjects.forEach(subject => {
                    var option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.subject_name;
                    subjectSelect.appendChild(option);
                });
            }

            // Xử lý sự kiện khi chọn chủ đề
            subjectSelect.onchange = function() {
                var selectedSubjectId = subjectSelect.value;
                updateLessonList(subjects, selectedSubjectId);
            };
        }

        // Cập nhật danh sách bài học
        function updateLessonList(subjects, subjectId) {
            var lessonContainer = document.getElementById('lessonContainer');
            var selectedLessonItems = document.getElementById('selectedLessonItems');
            var lessonOptions = document.getElementById('lessonOptions');
            selectedLessonItems.textContent = 'Chưa chọn';
            lessonOptions.innerHTML = '';
            selectedLessons = [];

            if (!subjectId) {
                lessonContainer.style.display = 'none'; // Ẩn nếu không có chủ đề được chọn
                return;
            }

            // Tìm chủ đề theo subjectId
            var subject = subjects.find(s => String(s.id) === String(subjectId));
            if (subject && subject.lessons && subject.lessons.length > 0) {
                subject.lessons.forEach(lesson => {
                    var div = document.createElement('div');
                    div.setAttribute('data-value', lesson.id);
                    div.textContent = lesson.lesson_name;
                    lessonOptions.appendChild(div);
                });
                lessonContainer.style.display = 'block'; // Hiển thị dropdown bài học
            } else {
                lessonContainer.style.display = 'none'; // Ẩn nếu không có bài học
            }

            // Thiết lập dropdown tùy chỉnh
            setupCustomLessonDropdown();
        }

        // Thiết lập sự kiện cho dropdown tùy chỉnh
        function setupCustomLessonDropdown() {
            var dropdown = document.getElementById('customLessonDropdown');
            var dropdownOptions = document.getElementById('lessonOptions');
            var selectedItems = document.getElementById('selectedLessonItems');
            // var selected = [];

            // Mở/đóng dropdown khi click vào selected-items
            dropdown.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'span') return;
                dropdownOptions.style.display = dropdownOptions.style.display === 'block' ? 'none' : 'block';
            });

            // Xử lý khi chọn một tùy chọn
            dropdownOptions.addEventListener('click', function(e) {
                var value = e.target.getAttribute('data-value');
                var label = e.target.textContent;

                if (!selectedLessons.includes(value)) {
                    selectedLessons.push(value);
                    renderSelected();
                    e.target.style.display = 'none';
                }
            });

            // Hiển thị các bài học được chọn
            function renderSelected() {
                selectedItems.innerHTML = '';
                if (selectedLessons.length === 0) {
                    selectedItems.textContent = 'Chưa chọn';
                    return;
                }
                selectedLessons.forEach(value => {
                    var label = dropdownOptions.querySelector(`[data-value="${value}"]`).textContent;
                    var item = document.createElement('div');
                    item.className = 'selected-item';
                    item.innerHTML = `${label} <span data-value="${value}">×</span>`;
                    selectedItems.appendChild(item);
                });
            }

            // Xử lý xóa bài học được chọn
            selectedItems.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() === 'span') {
                    var value = e.target.getAttribute('data-value');
                    selectedLessons = selectedLessons.filter(v => v !== value);
                    dropdownOptions.querySelector(`[data-value="${value}"]`).style.display = 'block';
                    renderSelected();
                }
            });

            // Đóng dropdown khi click bên ngoài
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    dropdownOptions.style.display = 'none';
                }
            });
        }

        // Xử lý gửi form
        function saveForm(event) {
            event.preventDefault(); // chặn form tự submit (GET)
            var scheduleDate = document.getElementById('scheduleDate').value;
            var startTime = document.getElementById('startTime').value;
            var endTime = document.getElementById('endTime').value;
            var teacherId = document.getElementById('teacherSelect').value;
            var assistantTeacherId = document.getElementById('assistantTeacherSelect').value;
            var subjectId = document.getElementById('subjectSelect').value;
            var note = document.getElementById('note').value;
            var courseSelect = document.getElementById('courseSelect');
            var courseId = courseSelect.dataset.courseId ? parseInt(courseSelect.dataset.courseId) : null;

            // Kiểm tra dữ liệu
            if (!classId) {
                alert('Không xác định được lớp học. Vui lòng mở lại modal.');
                return;
            }
            if (!scheduleDate) {
                alert('Vui lòng chọn ngày.');
                return;
            }
            if (!startTime) {
                alert('Vui lòng chọn thời gian bắt đầu.');
                return;
            }
            if (!endTime) {
                alert('Vui lòng chọn thời gian kết thúc.');
                return;
            }
            if (startTime >= endTime) {
                alert('Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc.');
                return;
            }
            if (!teacherId) {
                alert('Vui lòng chọn giáo viên.');
                return;
            }
            if (!subjectId) {
                alert('Vui lòng chọn chủ đề.');
                return;
            }
            if (selectedLessons.length === 0) {
                alert('Vui lòng chọn ít nhất một bài học.');
                return;
            }
            if (!courseId) {
                alert('Không xác định được khóa học.');
                return;
            }

            // Thu thập dữ liệu form
            var formData = {
                classId: classId,
                scheduleDate: scheduleDate,
                startTime: startTime,
                endTime: endTime,
                teacher_id: teacherId,
                assistant_teacher_id: assistantTeacherId ? parseInt(assistantTeacherId) :
                null, // Mặc định là null nếu không chọn
                subject_id: subjectId,
                lesson_ids: selectedLessons,
                note: note || null, // Gửi note, mặc định null nếu rỗng
                course_id: courseId // Thêm course_id vào formData
            };

            alert(JSON.stringify(formData, null, 2))

            // Gửi dữ liệu qua fetch
            fetch('/save-class-selection', {
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



        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            const data = $(this).serialize();
            $.ajax({
                url: "{{ route('schedules.index') }}",
                type: 'GET',
                data: data,
                success: function(response) {
                    $('#classList').html(response);
                },
                error: function() {
                    alert("Có lỗi xảy ra khi lọc dữ liệu.");
                }
            });
        });

        // Filter scripts
        $(document).ready(function() {
            // Submit form qua AJAX
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                performAjaxSearch();
            });

            // Auto-submit khi thay đổi select
            $('#truong_tim_kiem, #giao_vien, #trang_thai, #hinh_thuc, #per_page').on('change', function() {
                performAjaxSearch();
            });

            // Xử lý nút reset bộ lọc
            $('.btn-reset').on('click', function(e) {
                e.preventDefault();
                // Reset tất cả các trường trong form
                $('#keyword').val('');
                $('#truong_tim_kiem').val('');
                $('#giao_vien').val('');
                $('#trang_thai').val('');
                $('#hinh_thuc').val('');
                $('#per_page').val('4');

                // Thực hiện tìm kiếm với dữ liệu đã reset
                performAjaxSearch();
            });

            // Function tìm kiếm AJAX
            function performAjaxSearch() {
                const data = $('#filterForm').serialize();
                console.log('Filter Params:', data);

                $.ajax({
                    url: $('#filterForm').attr('action'),
                    type: 'GET',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        // Thêm loading indicator nếu cần
                        $('#classList').html(
                            '<tr><td colspan="6" class="text-center">Đang tải...</td></tr>');
                    },
                    success: function(response) {
                        console.log('AJAX Response:', response);

                        // Cập nhật bảng
                        if (response.table) {
                            $('#classList').html(response.table);
                        } else {
                            $('#classList').html(
                                '<tr><td colspan="6">Lỗi: Không nhận được dữ liệu bảng.</td></tr>');
                        }

                        // Cập nhật phân trang
                        if (response.pagination) {
                            $('.pagination-container').html(response.pagination);
                        } else {
                            $('.pagination-container').html(
                                '<p class="text-center text-muted">Không có phân trang.</p>');
                        }

                        // Cập nhật URL trình duyệt nhưng không reload trang
                        const url = new URL(window.location);
                        const params = new URLSearchParams(data);

                        // Xóa các tham số truy vấn cũ
                        [...url.searchParams.keys()].forEach(key => {
                            url.searchParams.delete(key);
                        });

                        // Thêm tham số truy vấn mới
                        params.forEach((value, key) => {
                            if (value) { // Chỉ thêm các tham số có giá trị
                                url.searchParams.append(key, value);
                            }
                        });

                        window.history.pushState({}, '', url);
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi AJAX:', {
                            status,
                            error,
                            response: xhr.responseText
                        });
                        $('#classList').html('<tr><td colspan="6">Lỗi khi tải dữ liệu.</td></tr>');
                    }
                });
            }

            // Xử lý phân trang AJAX
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const page = $(this).attr('href').split('page=')[1];
                $('#filterForm').append('<input type="hidden" name="page" value="' + page + '">');
                performAjaxSearch();
                $('#filterForm').find('input[name="page"]').remove();
            });
        });
    </script>
@endpush
