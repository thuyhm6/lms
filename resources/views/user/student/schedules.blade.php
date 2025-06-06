@extends('layouts.app')
@section('content')
    <style>
        .table-responsive table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-responsive th,
        .table-responsive td {
            padding: 5px 10px;
            border: 1px solid #ddd;
            font-size: 14px
        }

        .table-responsive th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .table-responsive tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-responsive tr:hover {
            background-color: #e9ecef;
        }

        .table-responsive .badge:nth-child(1) {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 14px;
            background-color: rgb(0, 179, 0);
            color: #ffffff;
        }

        .table-responsive .badge:nth-child(2) {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 14px;
            color: #ffffff;
            background-color: rgb(255, 0, 0);
        }

        .table-responsive .btn {
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 4px;
            background-color: #007bff;
            color: #ffffff;
        }



        /* modal */
        .custom-modal {
            display: none;
            /* Ẩn mặc định */
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .custom-modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 6px;
            width: 90%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10000;
        }

        .custom-modal-close {
            position: absolute;
            right: 12px;
            top: 8px;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
        }

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


        /* Phân trang */
        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            gap: 5px;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination li a,
        .pagination li span {
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .pagination li a:hover {
            background-color: #007bff;
            color: #ffffff;
        }

        .pagination li.active span {
            background-color: #007bff !important;
            color: #ffffff;
            border-color: #007bff !important;
        }

        /* Dropdown số dòng hiển thị */
        #limit2 {
            padding: 6px 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #ffffff;
            color: #333;
            transition: border-color 0.3s ease;
        }

        #limit2:focus {
            border-color: #007bff;
            outline: none;
        }

        label[for="limit2"] {
            font-size: 14px;
            font-weight: 500;
            margin-right: 8px;
            color: #333;
        }



        /* Bộ lọc */
        .filter-wrapper {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 20px;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .filter-form {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }

        .filter-group input[type="date"] {
            padding: 6px 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #ffffff;
            color: #333;
            transition: border-color 0.3s ease;
        }

        .filter-group input[type="date"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .btn-primary {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: 4px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .content-lession {
            border-radius: 5px;
            padding: 5px 10px;
            background-color: #fff7e3;
            color: #df8c4d;
            width: fit-content;
        }



        /* Action Popup */
        .action-dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .action-popup {
            display: none;
            position: absolute;
            right: 75px;
            top: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            /* Tăng z-index lên cao hơn */
            min-width: 150px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Thêm style mới cho container chứa bảng */
        .table-responsive {
            position: relative;
            /* Thêm position relative cho container */
            z-index: 1;
            /* z-index thấp hơn popup */
        }

        /* Đảm bảo popup luôn hiển thị trên cùng khi active */
        .action-popup.show {
            display: block;
            z-index: 9999;
        }

        /* Điều chỉnh vị trí popup cho những hàng cuối */
        tr:last-child .action-popup,
        tr:nth-last-child(2) .action-popup,
        tr:nth-last-child(3) .action-popup {
            bottom: 10%;
            top: auto;
        }

        .action-popup.show {
            display: block;
            /* z-index: 1001; */
        }

        .action-item {
            display: block;
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .action-item:hover {
            background-color: #f5f5f5;
            text-decoration: none;
        }

        .action-btn {
            padding: 6px 12px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-btn:hover {
            background-color: #f5f5f5;
        }

        /* Add this to your existing styles */
        .homework-details {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .homework-file {
            margin-top: 20px;
            width: 300px;
            text-align: center;
            margin: 0 auto;
        }

        .homework-file img {
            max-width: 100%;
            height: auto;
        }

        .homework-file .file-download {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
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
                        <h2>Lịch học của <span class="text-primary"> {{ Auth::user()->full_name }}</span></h2>
                        <!-- filepath: c:\xampp\htdocs\He_thong_Lms\lms-done2Duc_Duong\lms-done\lms\resources\views\user\student\schedules.blade.php -->
                        <div class="filter-wrapper mb-4 d-flex flex-wrap align-items-center gap-3">
                            <form method="GET" action="{{ route('student.schedules.filter') }}" class="filter-form w-100"
                                id="searchForm">
                                <input type="hidden" name="limit" id="limit" value="10">
                                <div class="filter-group">
                                    <label for="from_date">Từ ngày:</label>
                                    <input type="date" id="from_date" name="from_date"
                                        value="{{ request('from_date') }}">
                                </div>
                                <div class="filter-group">
                                    <label for="to_date">Đến ngày:</label>
                                    <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <button type="reset" class="btn btn-primary">Xóa</button>
                            </form>
                            <div id="messageError" class="w-100">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="">
                                <thead class="">
                                    <tr>
                                        <th>#</th>
                                        <th>Ngày</th>
                                        <th>Giờ học</th>
                                        <th>Khóa học</th>
                                        <th>Môn học</th>
                                        <th>Bài giảng</th>
                                        <th>Bài tập</th>
                                        <th>Giáo viên</th>
                                    </tr>
                                </thead>
                                <tbody id="body-schedules">
                                    @forelse($schedules as $index => $schedule)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->ngay_hoc)->format('d/m/Y') }}</td>
                                            <td>{{ $schedule->tu_gio }} - {{ $schedule->den_gio }}</td>
                                            <td>{{ $schedule->khoa_hoc }}</td>
                                            <td>{{ $schedule->mon_hoc }}</td>
                                            <td>
                                                @php
                                                    $lines = explode("\n", $schedule->bai_giang);
                                                @endphp

                                                @foreach ($lines as $i => $line)
                                                    @php
                                                        [
                                                            $lessonId,
                                                            $lessonName,
                                                            $lessonContent,
                                                            $lessonFileLink,
                                                        ] = explode('::', $line);
                                                    @endphp

                                                    <button class="lesson-btn"
                                                        onclick="openModal('{{ 'modal-' . $index . '-' . $i }}')">
                                                        {{ $i + 1 }}. {{ Str::limit($lessonName, 30) }}
                                                    </button>

                                                    <!-- Modal -->
                                                    <div id="{{ 'modal-' . $index . '-' . $i }}" class="custom-modal">
                                                        <div class="custom-modal-content">
                                                            <span class="custom-modal-close"
                                                                onclick="closeModal('{{ 'modal-' . $index . '-' . $i }}')">&times;</span>
                                                            <h5>Bài {{ $i + 1 }}: {{ $lessonName }}</h5>
                                                            <p><span class="content-lession">Nội dung bài học:</span>
                                                                {{ $lessonContent }}</p>
                                                            <iframe width="100%" height="700px"
                                                                src="{{ asset($lessonFileLink . '/index.html') }}"
                                                                frameborder="0"></iframe>
                                                            <a href="{{ route('lessons.show', ['id' => $lessonId]) }}">
                                                                <button class="btn btn-primary">Xem chi tiết</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <div class="action-dropdown">
                                                    <button class="action-btn" data-schedule-id="{{ $schedule->stt }}">
                                                        <i class="icon-settings"></i> Actions
                                                    </button>
                                                    <div class="action-popup">
                                                        @if ($schedule->homework_id)
                                                            <a href="#" class="action-item view-homework"
                                                                data-schedule-id="{{ $schedule->homework_id }}">
                                                                <i class="icon-eye"></i> Xem bài tập
                                                            </a>
                                                            <a href="#" class="action-item submit-homework"
                                                                data-schedule-id="{{ $schedule->homework_id }}">
                                                                <i class="icon-upload"></i> Nộp bài tập
                                                            </a>
                                                        @else
                                                            <span class="action-item disabled">
                                                                <i class="icon-warning"></i> Không có bài tập
                                                            </span>
                                                        @endif
                                                        <a href="#" class="action-item view-materials">
                                                            <i class="icon-book"></i> Tài liệu học tập
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $schedule->giao_vien }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center alert-warning">Bạn chưa đăng ký khóa học
                                                nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="row align-items-center justify-content-between my-3">
                                <div class="col-auto" id="paginationWrapper">
                                    {{ $schedules->links('pagination::bootstrap-5') }}
                                </div>
                                <div class="col-auto">
                                    <label for="limit2" class=" mb-0">Số dòng hiển thị:</label>
                                    <select name="limit" id="limit2" class="">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Homework View Modal -->
                    <div id="homeworkViewModal" class="custom-modal">
                        <div class="custom-modal-content">
                            <span class="custom-modal-close" onclick="closeHomeworkModal()">&times;</span>
                            <h4>Bài tập được giao</h4>
                            <div id="homeworkContent">
                                <div class="homework-details">
                                    <p><strong>Mô tả:</strong> <span id="homework-description"></span></p>
                                    <p><strong>Hạn nộp:</strong> <span id="homework-deadline"></span></p>
                                </div>
                                <div class="homework-file" id="homework-file">
                                    <!-- File preview will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Homework Submit Modal -->
                    <div id="homeworkSubmitModal" class="custom-modal">
                        <div class="custom-modal-content">
                            <span class="custom-modal-close" onclick="closeSubmitModal()">&times;</span>
                            <h4>Nộp bài tập</h4>
                            <form id="homeworkSubmitForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="homework_id" id="homework_id">
                                <div class="form-group">
                                    <label>Ghi chú</label>
                                    <textarea name="submission_note" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>File đính kèm</label>
                                    <input type="file" name="file_path" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Nộp bài</button>
                            </form>
                        </div>
                    </div>

                    <!-- Materials Modal -->
                    <div id="materialsModal" class="custom-modal">
                        <div class="custom-modal-content">
                            <span class="custom-modal-close" onclick="closeMaterialsModal()">&times;</span>
                            <h4>Tài liệu học tập</h4>
                            <p>Chức năng đang được phát triển...</p>
                        </div>
                    </div>

                </div>
        </section>
    </main>
    <!-- Thêm trước thẻ </body> -->

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('DOMContentLoaded', function() {

            // Close all popups when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.action-dropdown')) {
                    document.querySelectorAll('.action-popup').forEach(popup => {
                        popup.classList.remove('show');
                    });
                }
            });

            // Handle action button clicks
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = this.closest('.action-dropdown');
                    const popup = dropdown.querySelector('.action-popup');

                    // Close all other popups
                    document.querySelectorAll('.action-popup').forEach(p => {
                        if (p !== popup) {
                            p.classList.remove('show');
                        }
                    });

                    // Toggle current popup
                    popup.classList.toggle('show');
                });
            });

            // Handle action item clicks
            document.querySelectorAll('.action-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const scheduleId = this.closest('.action-dropdown')
                        .querySelector('.action-btn')
                        .dataset.scheduleId;

                    if (this.classList.contains('view-homework')) {
                        viewHomework(scheduleId);
                    } else if (this.classList.contains('submit-homework')) {
                        showSubmitHomework(scheduleId);
                    } else if (this.classList.contains('view-materials')) {
                        showMaterials();
                    }
                });
            });
        });

        function viewHomework(scheduleId) {
            fetch(`/student/homework/${scheduleId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('homework-description').textContent = data.description;
                    document.getElementById('homework-deadline').textContent = data.deadline;

                    const fileDiv = document.getElementById('homework-file');
                    fileDiv.innerHTML = '';

                    if (data.attachment_path) {
                        const fileExt = data.attachment_path.split('.').pop().toLowerCase();
                        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                            fileDiv.innerHTML = `<img src="${data.attachment_path}" alt="Homework attachment">`;
                        } else {
                            fileDiv.innerHTML = `
                                    <a href="${data.attachment_path}" class="file-download" download>
                                        <i class="icon-download"></i> Tải file đính kèm
                                    </a>`;
                        }
                    }

                    document.getElementById('homeworkViewModal').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }

        function showSubmitHomework(scheduleId) {
            // Lấy homework_id từ API getHomework trước
            fetch(`/student/homework/${scheduleId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Set homework_id vào form
                        document.getElementById('homework_id').value = data.homework_id;
                        document.getElementById('homeworkSubmitModal').style.display = 'block';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi lấy thông tin bài tập');
                });
        }

        function showMaterials() {
            document.getElementById('materialsModal').style.display = 'block';
        }

        function closeHomeworkModal() {
            document.getElementById('homeworkViewModal').style.display = 'none';
        }

        function closeSubmitModal() {
            document.getElementById('homeworkSubmitModal').style.display = 'none';
        }

        function closeMaterialsModal() {
            document.getElementById('materialsModal').style.display = 'none';
        }

        // Handle homework submission
        document.getElementById('homeworkSubmitForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Đang xử lý...';

            const formData = new FormData(this);

            fetch('/student/homework/submit', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeSubmitModal();
                        window.location.reload();
                    } else {
                        if (data.errors) {
                            // Show validation errors
                            let errorMessage = 'Lỗi:\n';
                            Object.values(data.errors).forEach(error => {
                                errorMessage += `- ${error}\n`;
                            });
                            alert(errorMessage);
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi nộp bài');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi nộp bài');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Nộp bài';
                });
        });
    </script>
    <script>
        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Đóng modal khi click ngoài vùng nội dung
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.custom-modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {

            //Xử lý bộ Lọc -----------------------------------
            // Khi thay đổi limit ở formLimit
            $('#limit2').change(function() {
                const limitValue = $(this).val();
                $('#searchForm #limit').val(limitValue);
                $('#searchForm').submit();
            });

            //Hàm xử lý bộ lọc
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                const fromDate = $('#from_date').val();
                const toDate = $('#to_date').val();

                // Kiểm tra nếu toDate < fromDate thì báo lỗi và không gửi request
                if (fromDate && toDate && toDate < fromDate) {
                    $('#messageError').html(
                        '<div class="alert alert-danger">Ngày kết thúc không thể nhỏ hơn ngày bắt đầu.</div>'
                    );
                    return;
                }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        $('#body-schedules').html(renderSchedules(response.schedules.data));

                        $('#paginationWrapper').html(response
                            .pagination); // chèn HTML phân trang
                        $('#messageError').hide(); // ẩn thông báo lỗi nếu có


                    },
                    error: function(xhr) {
                        console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    }
                });
            });

            // Hàm tạo danh sách bài học
            function renderSchedules(data) {
                if (data.length === 0)
                    return '<tr><td colspan="11" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';

                let html = '';
                data.forEach((lesson, index) => {
                    html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${formatDateTime(lesson.ngay_hoc)}</td>
<td>${lesson.tu_gio} - ${lesson.den_gio}</td>
                                    <td>${lesson.khoa_hoc}</td>
                                    <td>${lesson.mon_hoc}</td>
                                    <td>`;
                    const lines = lesson.bai_giang.split('\n');
                    lines.forEach((line, i) => {
                        html += `<button class="lesson-btn" onclick="openModal('modal-${index}-${i}')">
                                                ${i + 1}. ${line.length > 30 ? line.substring(0, 30) + '...' : line}
                                            </button>
                                            <div id="modal-${index}-${i}" class="custom-modal">
                                                <div class="custom-modal-content">
                                                    <span class="custom-modal-close" onclick="closeModal('modal-${index}-${i}')">&times;</span>
                                                    <h5>Bài ${i + 1}</h5>
                                                    <p>${line}</p>
                                                </div>
                                            </div>`;
                    });
                    html += `</td>
                                    <td>
                                        <div class="action-dropdown">
                                            <button class="action-btn" data-schedule-id="{{ $schedule->stt }}">
                                                <i class="icon-settings"></i> Actions
                                            </button>
                                            <div class="action-popup">
                                                @if ($schedule->homework_id)
                                                    <a href="#" class="action-item view-homework"
                                                        data-schedule-id="{{ $schedule->homework_id }}">
                                                        <i class="icon-eye"></i> Xem bài tập
                                                    </a>
                                                    <a href="#" class="action-item submit-homework"
                                                        data-schedule-id="{{ $schedule->homework_id }}">
                                                        <i class="icon-upload"></i> Nộp bài tập
                                                    </a>
                                                @else
                                                    <span class="action-item disabled">
                                                        <i class="icon-warning"></i> Không có bài tập
                                                    </span>
                                                @endif
                                                <a href="#" class="action-item view-materials">
                                                    <i class="icon-book"></i> Tài liệu học tập
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${lesson.giao_vien}</td>
                                </tr>
                    `;
                });
                return html;
            }

            // Hàm định dạng thời gian
            function formatDateTime(dateTime) {
                const date = new Date(dateTime);

                const hours = date.getHours().toString().padStart(2, '0');
                const minutes = date.getMinutes().toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Tháng bắt đầu từ 0
                const year = date.getFullYear();

                return `${hours}:${minutes} ${day}/${month}/${year}`;
            }


            $(document).on('click', '#paginationWrapper a', function(e) {
                e.preventDefault();

                const url = $(this).attr('href'); // Lấy URL phân trang (bao gồm các tham số lọc)
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {

                        // Cập nhật kết quả bài học và phân trang
                        $('#body-schedules').html(renderSchedules(response.schedules.data));
                        $('#paginationWrapper').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.error('Lỗi phân trang:', xhr.responseText);
                    }
                });
            });

        })
    </script>

@endsection
