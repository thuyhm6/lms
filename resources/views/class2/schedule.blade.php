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
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('schedules.index') }}">
                            <div class="text-tiny">Schedules</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">{{ $class->ten_lop }}</div>
                    </li>
                </ul>
            </div>

            <!-- Danh sách lịch học -->
            <div class="wg-box mb-27">
                <h4>Danh sách lịch học</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Giáo viên</th>
                                <th>Trợ giảng</th>
                                <th>Thời gian</th>
                                <th>Nội dung</th>
                                <th>Ghi chú</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schedules as $index => $schedule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="btn btn-info btn-sm text-white">
                                            {{ $schedule->giao_vien ?? $class->giao_vien_phu_trach_chinh ?? 'Chưa có' }}
                                        </span>
                                    </td>
                                    <td>{{ $schedule->tro_giang ?? 'Chưa có' }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($schedule->start_date)->format('d/m/Y') }}
                                        ({{ ucfirst(\Carbon\Carbon::parse($schedule->start_date)->locale('vi')->isoFormat('dddd')) }})
                                        <br>
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>
                                    <td class="text-start">
                                        @if ($schedule->course_id)
                                            @php
                                                $course = $schedules->firstWhere('course_id', $schedule->course_id);
                                                $subjects = $subjectsByCourse[$schedule->course_id] ?? collect([]);
                                            @endphp
                                            <strong>-</strong> {{ $course->ten_khoa_hoc ?? 'Chưa có' }}<br>
                                            @foreach ($subjects as $subject)
                                                - {{ $subject->ten_mon_hoc }}<br>
                                                @foreach ($subject->lessons as $lesson)
                                                    &nbsp;&nbsp;- {{ $lesson->ten_bai_hoc }}<br>
                                                @endforeach
                                            @endforeach
                                        @else
                                            Không có nội dung
                                        @endif
                                    </td>
                                    <td>{{ $schedule->ghi_chu ?? 'Không có' }}</td>
                                    <td>
                                        <span class="btn btn-success btn-sm">Đã duyệt</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Chưa có lịch học</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
