@extends('layouts.app')
@push('styles')
    <style>
    
        .dropdown-menu {
            min-width: 150px;

            z-index: 1050 !important;
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
            background-color: #1486ff;
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .table-responsive2 table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #f9f9f9;
            border-radius: 8px;
            /* overflow: hidden; */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-responsive2 th,
        .table-responsive2 td {
            padding: 5px 10px;
            border: 1px solid #ddd;
            font-size: 14px
        }

        .table-responsive2 th {
            background-color: #007bff;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .table-responsive2 tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-responsive2 tr:hover {
            background-color: #e9ecef;
        }

        .table-responsive2 .badge:nth-child(1) {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 14px;
            background-color: rgb(0, 179, 0);
            color: #ffffff;
        }

        .table-responsive2 .badge:nth-child(2) {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 14px;
            color: #ffffff;
            background-color: rgb(255, 0, 0);
        }

        
    </style>
    @section('content')
        <section class="my-account container">
            <h2 class="page-title">My Account</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <h2>LỚP HỌC QUẢN LÝ CỦA GIÁO VIÊN <span class="text-primary"> {{ Auth::user()->full_name }}</span>
                        </h2>
                        <div class="filter-wrapper mb-4 d-flex flex-wrap align-items-center gap-3">
                            {{-- <form method="GET" action="{{ route('teacher.schedules.filter') }}" class="filter-form w-100"
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
                            </form> --}}
                            <div id="messageError" class="w-100">
                            </div>
                        </div>
                        <div class="table-responsive2">
                            <table class="">
                                <thead class="">
                                    <tr>
                                        {{-- <th scope="col" style="width: 5%;"><input type="checkbox" class="form-check-input"></th> --}}
                                        <th scope="col" style="width: 5%;">STT</th>
                                        <th scope="col" style="width: 5%;">Mã lớp</th>
                                        <th scope="col" style="width: 10%;">Tên lớp</th>
                                        <th scope="col" style="width: 10%;">Thuộc trung tâm</th>
                                        <th scope="col">Lịch học</th>
                                        <th scope="col" style="width: 7%;">Mô tả</th>
                                        <th scope="col" style="width: 10%;">Số học sinh</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col" style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="body-schedules">
                                    @foreach ($classes as $index => $class)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $class->ma_lop }}</td>
                                            <td><span class="btn btn-sm btn-danger text-white">{{ $class->ten_lop }}</span></td>
                                            <td><strong>TIPY STEM - Thanh Hóa</strong></td>
                                            <td>
                                                <span class="btn btn-sm btn-info text-white">{{ $class->lich_hoc }}</span>
                                            </td>

                                            <td>{{ $class->mo_ta }}</td>
                                            <td class="text-center">{{ $class->so_hoc_sinh ?? 'Chưa có' }}</td>

                                            <td class="text-center">
                                                @if ($class->trang_thai_lop_hoc === 0)
                                                    <span class="btn btn-sm btn-warning text-white">Kết thúc</span>
                                                @elseif ($class->trang_thai_lop_hoc === 1)
                                                    <span class="btn btn-sm btn-success">Đang học</span>
                                                @else
                                                    <span class="btn btn-sm btn-secondary">Không rõ</span>
                                                @endif
                                                {{-- <br>
                                                <span class="btn btn-sm btn-info mt-1">{{ $class->active_days }} ngày
                                                    active</span> --}}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        ⚙️
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('class.students', ['id' => $class->sap_xep]) }}">📋
                                                                Danh sách học sinh</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('class.edit', ['id' => $class->sap_xep]) }}">✏️
                                                                Sửa</a></li>
                                                        <li><a class="dropdown-item" href="#">📊 Xem báo cáo kết quả
                                                                học
                                                                tập</a></li>
                                                        <li>
                                                            <a href="#" class="dropdown-item text-danger"
                                                                onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa lớp này?')) document.getElementById('delete-form-{{ $class->sap_xep }}').submit();">
                                                                🗑️ Xóa
                                                            </a>
                                                            <form id="delete-form-{{ $class->sap_xep }}"
                                                                action="{{ route('class.destroy', ['id' => $class->sap_xep]) }}"
                                                                method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </section>

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

                // Hàm tạo danh sách bài học
                function renderSchedules(data) {
                    if (data.length === 0)
                        return '<tr><td colspan="11" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';

                    let html = '';
                    data.forEach((classItem, index) => {
                        // Format trạng thái theo Blade template
                        let statusHtml = '';
                        if (classItem.trang_thai_lop_hoc === 0) {
                            statusHtml = '<span class="btn btn-sm btn-warning text-white">Kết thúc</span>';
                        } else if (classItem.trang_thai_lop_hoc === 1) {
                            statusHtml = '<span class="btn btn-sm btn-success">Đang học</span>';
                        } else {
                            statusHtml = '<span class="btn btn-sm btn-secondary">Không rõ</span>';
                        }

                        // Format số học sinh
                        let studentCount = classItem.so_hoc_sinh ?? 'Chưa có';

                        // Format lịch học với button style
                        let scheduleHtml =
                            `<span class="btn btn-sm btn-info text-white">${classItem.lich_hoc}</span>`;

                        html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${classItem.ma_lop}</td>
                            <td>${classItem.ten_lop}</td>
                            <td><strong>TIPY STEM - Thanh Hóa</strong></td>
                            <td>${scheduleHtml}</td>
                            <td>${classItem.mo_ta || ''}</td>
                            <td class="text-center">${studentCount}</td>
                            <td class="text-center">${statusHtml}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                        id="dropdownMenuButton${classItem.sap_xep}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        ⚙️
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton${classItem.sap_xep}">
                                        <li><a class="dropdown-item" href="/class/${classItem.sap_xep}/students">📋 Danh sách học sinh</a></li>
                                        <li><a class="dropdown-item" href="/class/${classItem.sap_xep}/edit">✏️ Sửa</a></li>
                                        <li><a class="dropdown-item" href="#">📊 Xem báo cáo kết quả học tập</a></li>
                                        <li>
                                            <a href="#" class="dropdown-item text-danger"
                                                onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa lớp này?')) deleteClass(${classItem.sap_xep});">
                                                🗑️ Xóa
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                    });
                    return html;
                }

                // // Hàm format trạng thái
                // function formatStatus(status) {
                //     const statusMap = {
                //         'active': 'Đang hoạt động',
                //         'inactive': 'Không hoạt động',
                //         'completed': 'Đã hoàn thành',
                //         'pending': 'Chờ xử lý'
                //     };
                //     return statusMap[status] || status;
                // }

                // // Hàm format hình thức học
                // function formatLearningFormat(format) {
                //     const formatMap = {
                //         'online': 'Trực tuyến',
                //         'offline': 'Tại lớp',
                //         'hybrid': 'Kết hợp'
                //     };
                //     return formatMap[format] || format;
                // }

                // // Hàm lấy class CSS cho badge
                // function getStatusBadgeClass(status) {
                //     const classMap = {
                //         'active': 'bg-success',
                //         'inactive': 'bg-secondary',
                //         'completed': 'bg-primary',
                //         'pending': 'bg-warning',
                //         'online': 'bg-info',
                //         'offline': 'bg-dark',
                //         'hybrid': 'bg-primary'
                //     };
                //     return classMap[status] || 'bg-secondary';
                // }

                // // Hàm format ngày giờ
                // function formatDateTime(dateString) {
                //     if (!dateString) return 'Chưa có';

                //     const date = new Date(dateString);
                //     const day = String(date.getDate()).padStart(2, '0');
                //     const month = String(date.getMonth() + 1).padStart(2, '0');
                //     const year = date.getFullYear();
                //     const hours = String(date.getHours()).padStart(2, '0');
                //     const minutes = String(date.getMinutes()).padStart(2, '0');

                //     return `${day}/${month}/${year} ${hours}:${minutes}`;
                // }


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
