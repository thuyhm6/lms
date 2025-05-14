@extends('layouts.admin')
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
    </style>
@endpush
@section('title', 'Danh sách lịch dạy của giáo viên')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách lịch dạy của giáo viên</h3>
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

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>STT</th>
                                <th>Giáo viên</th>
                                <th>Giáo viên trợ giảng</th>
                                <th>Ngày dạy</th>
                                <th>Nội dung</th>
                                <th>Địa điểm (lớp học)</th>
                                <th>Ghi chú</th>
                                <th>Hình thức học</th>
                                <th>Điểm danh</th>
                                {{-- <th>Trạng thái</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classes as $class)
                                <tr>
                                    <td>{{ $class->stt }}</td>
                                    <td>{{ $class->teacher_name ?? 'Chưa có' }}</td>
                                    <td>Chưa có</td>
                                    <td class="">
                                        @if ($class->start_date && $class->start_time && $class->end_time)
                                            {{ \Carbon\Carbon::parse($class->start_date)->format('d/m/Y') }}<br>
                                            <span class="btn btn-danger text-white">{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}</span> -
                                            <span class="btn btn-info text-white">{{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="btn btn-danger text-white">{{ $class->course_name ?? 'N/A' }}</span>
                                        <br>
                                        <span class="btn btn-primary text-white">{{ $class->subject_name ?? 'N/A' }}</span>
                                        <br>
                                        @if (count($class->lessons) > 0)
                                            <br>
                                            <small class="btn btn-info text-white">
                                                {{ implode(', ', $class->lessons) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="btn btn-success text-white">{{ $class->class_name ?? 'Chưa có' }}</span><br>
                                       <span class="btn btn-warnin text-white"> {{ $class->class_code ?? 'Chưa có' }}</span>
                                    </td>
                                    <td>{{ $class->note ?? 'Không có' }}</td>
                                    <td>{{ $class->learning_format ?? 'Chưa có' }}</td>
                                    <td>Chưa có</td>
                                    {{-- <td>
                                        <span
                                            class="badge {{ $class->status == 'online' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $class->status ?? 'Chưa có' }}
                                        </span>
                                    </td> --}}
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
