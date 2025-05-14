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

            <!-- Thông tin lớp học -->
            <div class="wg-box mb-27">
                <h4>Thông tin lớp học</h4>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã lớp:</strong> {{ $class->ma_lop }}</p>
                        <p><strong>Tên lớp:</strong> {{ $class->ten_lop }}</p>
                        <p><strong>Mô tả:</strong> {{ $class->mo_ta ?? 'Không có' }}</p>
                        <p><strong>Hình thức học:</strong> 
                            <span class="btn btn-danger btn-sm">
                                {{ $class->hinh_thuc == 'online' ? 'Online' : 'Offline' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Giáo viên chính:</strong> 
                            <span class="btn btn-info btn-sm text-white">
                                {{ $class->giao_vien_phu_trach_chinh }}
                            </span>
                        </p>
                        <p><strong>Số học sinh:</strong> {{ $class->so_hoc_sinh }}</p>
                        <p><strong>Trạng thái:</strong> 
                            <span class="btn btn-warning btn-sm text-white">
                                {{ $class->trang_thai_lop_hoc ? 'Kích hoạt' : 'Kết thúc' }}
                            </span>
                        </p>
                        <p><strong>Ngày bắt đầu:</strong> {{ \Carbon\Carbon::parse($class->ngay_tao_lop_hoc)->format('d/m/Y') }}</p>
                        <p><strong>Ngày kết thúc:</strong> {{ \Carbon\Carbon::parse($class->ngay_ket_thuc)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Danh sách lịch học -->
            <div class="wg-box mb-27">
                <h4>Danh sách lịch học</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Ngày học</th>
                                <th>Thời gian</th>
                                <th>Trợ giảng</th>
                                <th>Khóa học</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedules as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_date)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($schedule->start_date)->format('l') }})</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                    <td>{{ $schedule->tro_giang }}</td>
                                    <td>{{ $schedule->ten_khoa_hoc ?? 'Chưa có' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có lịch học</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Danh sách khóa học và môn học, bài học -->
            @forelse ($schedules->pluck('course_id')->unique()->filter() as $courseId)
                @php
                    $course = $schedules->firstWhere('course_id', $courseId);
                    $subjects = $subjectsByCourse[$courseId] ?? collect([]);
                @endphp
                <div class="wg-box mb-27">
                    <h4>Khóa học: {{ $course->ten_khoa_hoc }}</h4>
                    <p><strong>Mô tả:</strong> {{ $course->mo_ta_khoa_hoc ?? 'Không có' }}</p>
                </div>

                <div class="wg-box mb-27">
                    <h4>Danh sách môn học (Khóa học: {{ $course->ten_khoa_hoc }})</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên môn học</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subjects as $index => $subject)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subject->ten_mon_hoc }}</td>
                                        <td>{{ $subject->mo_ta_mon_hoc ?? 'Không có' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Chưa có môn học</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="wg-box mb-27">
                    <h4>Danh sách bài học (Môn học: {{ $subject->ten_mon_hoc }})</h4>
                    @forelse ($subjects as $subject)
                        <h5>Môn học: {{ $subject->ten_mon_hoc }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên bài học</th>
                                        <th>Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($subject->lessons as $index => $lesson)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $lesson->ten_bai_hoc }}</td>
                                            <td>{{ $lesson->mo_ta_bai_hoc ?? 'Không có' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Chưa có bài học</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <p>Chưa có môn học để hiển thị bài học.</p>
                    @endforelse
                </div>
            @empty
                <div class="wg-box mb-27">
                    <p>Chưa có khóa học nào được liên kết với lịch học.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection