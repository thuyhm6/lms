@extends('layouts.admin')
@push('styles')
    <style>
        .form-label.required::after {
            content: " (*)";
            color: red;
        }
    </style>
@endpush
@section('title', 'Lớp học')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Lớp học</h3>
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
                <div class="d-flex justify-content-between mg-3">
                    <h3>Class List</h3>
                    <a href="{{ route('class.create') }}"><button class="btn btn-success btn-lg">+ Add</button></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 5%;"><input type="checkbox" class="form-check-input"></th>
                                <th scope="col" style="width: 5%;"></th>
                                <th scope="col" style="width: 5%;">Sắp xếp</th>
                                <th scope="col" style="width: 5%;">Mã lớp</th>
                                <th scope="col" style="width: 10%;">Tên lớp</th>
                                <th scope="col">Lịch học</th>
                                <th scope="col" style="width: 10%;">Giáo viên</th>
                                <th scope="col" style="width: 7%;">Nhân viên</th>
                                <th scope="col" style="width: 5%;">Hình thức học</th>
                                <th scope="col" style="width: 7%;">Mô tả</th>
                                <th scope="col" style="width: 10%;">Số học sinh</th>
                                {{-- <th scope="col">Số buổi giảng viên đã điểm danh</th> --}}
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo lớp học</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classes as $class)
                                <tr>
                                    {{-- {{ dd($class) }} --}}
                                    <td>{{ $class->sap_xep }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                ⚙️
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('class.students', ['id' => $class->sap_xep]) }}">📋
                                                        Danh sách học sinh</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('class.edit', ['id' => $class->sap_xep]) }}">✏️
                                                        Sửa</a></li>
                                                <li><a class="dropdown-item" href="#">📊 Xem báo cáo kết quả học
                                                        tập</a></li>
                                                <li>
                                                    <a href="#" class="dropdown-item text-danger"
                                                        onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa lớp này?')) document.getElementById('delete-form-{{ $class->id }}').submit();">
                                                        🗑️ Xóa
                                                    </a>
                                                    <form id="delete-form-{{ $class->id }}"
                                                        action="{{ route('class.destroy', ['id' => $class->sap_xep]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>

                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $class->sap_xep }}</td>
                                    <td>{{ $class->ma_lop }}</td>
                                    <td>{{ $class->ten_lop }}</td>
                                    <td>
                                        <span class="btn btn-sm btn-info text-white">{{ $class->lich_hoc }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="btn btn-sm btn-info text-white">{{ $class->giao_vien_phu_trach_chinh }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="btn btn-sm btn-primary text-white">{{ $class->nhan_vien }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($class->hinh_thuc === 'offline')
                                            <span class="btn btn-sm btn-danger">Offline</span>
                                        @elseif ($class->hinh_thuc === 'online')
                                            <span class="btn btn-sm btn-success">Online</span>
                                        @elseif ($class->hinh_thuc === 'Hybrid')
                                            <span class="btn btn-sm btn-warning text-white">Hybrid</span>
                                        @else
                                            <span
                                                class="btn btn-sm btn-secondary">{{ $class->hinh_thuc ?? 'Không rõ' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $class->mo_ta }}</td>
                                    {{-- <td>{{ $class->so_buoi_hoc ?? 0 }}</td> --}}
                                    <td class="text-center">{{ $class->so_hoc_sinh ?? 'Chưa có' }}</td>
                                    {{-- <td>
                                        <span class="badge bg-success">{{ $class->so_buoi_diem_danh ?? 0 }}</span>
                                        <span class="badge bg-warning text-dark">{{ $class->so_buoi_hoc ?? 0 }}</span>
                                    </td> --}}
                                    {{-- <td>{{ $class->trang_thai_lop_hoc ? 'Kết thúc' : 'Chưa kết thúc' }}</td> --}}
                                    <td class="text-center">
                                        @if ($class->trang_thai_lop_hoc === 1)
                                            <span class="btn btn-sm btn-warning text-white">Kết thúc</span>
                                        @elseif ($class->trang_thai_lop_hoc === 0)
                                            <span class="btn btn-sm btn-success">Đang học</span>
                                        @else
                                            <span class="btn btn-sm btn-secondary">Không rõ</span>
                                        @endif
                                        <br>
                                        <span class="btn btn-sm btn-info mt-1">{{ $class->active_days }} ngày active</span>
                                    </td>
                                    {{-- <td>{{ $class->ngay_tao_lop_hoc->format('d/m/Y') }}</td> --}}
                                    <td class="text-center">
                                        {{ $class->ngay_tao_lop_hoc ? date('d/m/Y', strtotime($class->ngay_tao_lop_hoc)) : '' }}
                                    </td>
                                </tr>
                            @endforeach
                            {{-- <tr>
                                <td><input type="checkbox" class="form-check-input"></td>
                                <td>324</td>
                                <td>WK</td>
                                <td>Workshop Robotics</td>
                                <td><span class="badge bg-primary">Sunday</span></td>
                                <td><span class="badge bg-info">Trương</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary">Lương Khánh Huyền</button>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger">Offline</button>
                                </td>
                                <td></td>
                                <td class="text-center">1</td>
                                <td class="text-center"><span class="badge bg-success">0/15</span></td>
                                <td class="text-center">
                                    <span class="badge bg-warning">Completed</span>
                                    <span class="badge bg-info">1 day active</span>
                                </td>
                                <td>25/02/2025 <span class="text-muted">15:29</span></td>
                            </tr> --}}

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
