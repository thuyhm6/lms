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
            background-color: #218cff;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
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
            color: #ffffff;
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
        .bg-green{
            background-color: rgb(0, 177, 0);
            border-radius: 4px;
            padding: 3px 5px;
        }

        .content-lession {
            border-radius: 7px;
            padding: 5px 10px;
            background-color: #ffe7ac;
            color: #e7731a;
            width: fit-content;
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
                        <h2>LỊCH DẠY CỦA GIÁO VIÊN <span class="text-primary"> {{ Auth::user()->full_name }}</span></h2>
                        <div class="filter-wrapper mb-4 d-flex flex-wrap align-items-center gap-3">
                            <form method="GET" action="{{ route('teacher.schedules.filter') }}" class="filter-form w-100"
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
                                        <th>STT</th>
                                        <th>Ngày dạy</th>
                                        <th>Giờ dạy</th>
                                        <th>Nội dung</th>
                                        <th>Địa điểm (lớp học)</th>
                                        <th>Số học sinh</th>
                                        <th>Ghi chú</th>
                                        <th>Hình thức học</th>
                                        {{-- <th>Điểm danh</th> --}}
                                        {{-- <th>Trạng thái</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="body-schedules">
                                    @forelse ($teacherSchedules as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="">
                                                <p class="bg-green text-white w-100">{{ \Carbon\Carbon::parse($class->start_date)->format('d/m/Y') }}</p>
                                            </td>
                                            <td class="">
                                                @if ($class->start_date && $class->start_time && $class->end_time)
                                                    <span
                                                        class="btn btn-danger text-white">{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}</span>
                                                    -
                                                    <span
                                                        class="btn btn-info text-white">{{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}</span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.schedule.detail', ['id' => $class->schedule_id]) }}">
                                                    <span
                                                        class="btn btn-danger text-white mb-1">{{ $class->courses ?? 'N/A' }}</span>
                                                    <br>
                                                    <span
                                                        class="btn btn-primary text-white">{{ $class->subjects ?? 'N/A' }}</span>
                                                    <br>
                                                    {{-- @if (count($class->ten_bai_hoc) > 0) --}}
                                                    <br>
                                                    <small class="btn btn-info text-white">
                                                        {{ $class->lessons ?? 'N/A' }}
                                                    </small>
                                                    {{-- @endif --}}
                                                </a>
                                            </td>
                                            <td>
                                                <span
                                                    class="btn btn-success text-white">{{ $class->class_name ?? 'Chưa có' }}</span><br>
                                                <span class="btn btn-warning text-white">
                                                    {{ $class->class_code ?? 'Chưa có' }}</span>
                                            </td>
                                            <td>
                                                <a style="color: #007bff" href="{{ route('schedules.students', $class->schedule_id) }}">Xem danh
                                                    sách({{ $class->student_count }})</a>
                                            </td>
                                            <td>{{ $class->schedule_notes ?? 'Không có' }}</td>
                                            <td><span class="content-lession">{{ $class->learning_format ?? 'Chưa có' }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Không có lịch học nào trong lớp này</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </section>
    </main>


    <script>
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
                            console.log(response.schedules.data);
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

                function formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    if (isNaN(d)) return dateStr;
                    return d.toLocaleDateString('vi-VN');
                }
                function formatTime(timeStr) {
                    if (!timeStr) return '';
                    return timeStr.substring(0, 5);
                }

                // Hàm tạo danh sách bài học
                function renderSchedules(data) {
                    if (data.length === 0)
                        return '<tr><td colspan="11" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';

                    let html = '';
                    data.forEach((classItem, index) => {

                        html += `
                        <tr>
                            <td>${classItem.stt}</td>
                            <td>${formatDate(classItem.start_date)}</td>
                           <td>
                            ${classItem.start_time ? `<span class="btn btn-danger text-white">${formatTime(classItem.start_time)}</span>` : 'N/A'}
                            -
                            ${classItem.end_time ? `<span class="btn btn-info text-white">${formatTime(classItem.end_time)}</span>` : ''}
                        </td>
                            <td>
                                <a href="/schedules/${classItem.schedule_id}/detail">
                                    <span class="btn btn-danger text-white">${classItem.courses || 'N/A'}</span><br>
                                    <span class="btn btn-primary text-white">${classItem.subjects || 'N/A'}</span><br>
                                    <small class="btn btn-info text-white">${classItem.lessons || 'N/A'}</small>
                                </a>
                            </td>
                            <td>
                                <span class="btn btn-success text-white">${classItem.class_name || 'Chưa có'}</span><br>
                                <span class="btn btn-warning text-white">${classItem.class_code || 'Chưa có'}</span>
                            </td>
                            <td>
                                <a href="/schedules/${classItem.schedule_id}/students">Xem danh sách(${classItem.student_count || 0})</a>
                            </td>
                            <td>${classItem.schedule_notes || 'Không có'}</td>
                            <td>${classItem.learning_format || 'Chưa có'}</td>
                        </tr>
                    `;
                    });
                    return html;
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
