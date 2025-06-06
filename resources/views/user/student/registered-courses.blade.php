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
            padding: 12px 15px;
            border: 1px solid #ddd;
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
                        <h2>Khóa học đã đăng ký của <span class="text-primary"> {{ Auth::user()->full_name }}</span></h2>
                        <div class="table-responsive">
                            <table class="">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã khóa học</th>
                                        <th>Tên khóa học</th>
                                        <th>Trạng thái</th>
                                        {{-- <th>Hành động</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($registeredCourses as $index => $course)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $course->course_code }}</td>
                                            <td>{{ $course->course_name }}</td>
                                            <td>
                                                @if ($course->status == 'active')
                                                    <span class="badge">Đang hoạt động</span>
                                                @else
                                                    <span class="badge">Không hoạt động</span>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                <a href="" class="btn">Xem chi tiết</a>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center alert-warning">Bạn chưa đăng ký khóa học
                                                nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </section>
    </main>
@endsection
