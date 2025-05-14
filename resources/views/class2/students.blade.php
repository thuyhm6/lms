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
                <div class="d-flex justify-content-end mb-3">
                    <p><strong>Số học sinh:</strong> {{ $class->so_hoc_sinh }}</p>
                    <a href="{{ route('schedules.index') }}"><button class="btn btn-secondary btn-sm ms-2">Quay lại</button></a>
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
                                <th>Chức năng</th>
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
                                    <td>{{ $student->ngay_cap_nhat ? \Carbon\Carbon::parse($student->ngay_cap_nhat)->format('d/m/Y H:i') : 'Chưa cập nhật' }}</td>
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