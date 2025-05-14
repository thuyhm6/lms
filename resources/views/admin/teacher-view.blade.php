@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-20">
            <h3>Chi tiết giáo viên</h3>
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
                    <a href="{{ route('admin.teachers') }}">
                        <div class="text-tiny">All teachers</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Chi tiết</div>
                </li>
            </ul>
        </div>

        <!-- Thông báo -->
        @if(Session::has('error'))
            <p class="alert alert-danger">{{ Session::get('error') }}</p>
        @endif

        <!-- Nội dung chính -->
        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap mb-10">
                <h4>Thông tin giáo viên: {{ $teacher->user->full_name ?? 'N/A' }}</h4>
                <a class="tf-button style-1 w208" href="{{ route('admin.teachers') }}">
                    <i class="icon-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Thông tin cá nhân -->
            <div class="info-section mb-15">
                <h5>Thông tin cá nhân</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Họ tên:</span> {{ $teacher->user->full_name ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Email:</span> {{ $teacher->user->email ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Điện thoại:</span> {{ $teacher->user->mobile ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Địa chỉ:</span> {{ $teacher->user->address ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Sinh nhật:</span> 
                        @if($teacher->user->birthday)
                            {{ \Carbon\Carbon::parse($teacher->user->birthday)->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="label">Giới tính:</span> {{ $teacher->user->gender == 'nam' ? 'Nam' : ($teacher->user->gender == 'nữ' ? 'Nữ' : 'N/A') }}
                    </div>
                </div>
            </div>

            <!-- Thông tin giáo viên -->
            <div class="info-section mb-15">
                <h5>Thông tin giáo viên</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Học vị:</span> {{ $teacher->academic_degree ?? 'Không có' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Danh xưng:</span> {{ $teacher->title ?? 'Không có' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Facebook:</span> 
                        @if($teacher->facebook)
                            <a href="{{ $teacher->facebook }}" target="_blank">{{ $teacher->facebook }}</a>
                        @else
                            Không có
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="label">Giới thiệu:</span> {{ $teacher->introduction ?? 'Không có' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Thành tích:</span> {{ $teacher->achievements ?? 'Không có' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Ghi chú:</span> {{ $teacher->notes ?? 'Không có' }}
                    </div>
                </div>
            </div>

            <!-- Thông tin hệ thống -->
            <div class="info-section">
                <h5>Thông tin hệ thống</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Ngày đăng ký:</span> {{ $teacher->created_at ? $teacher->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Ngày cập nhật:</span> {{ $teacher->updated_at ? $teacher->updated_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-section {
    margin-bottom: 15px;
}
.info-section h5 {
    margin-bottom: 5px;
    font-size: 16px;
}
.info-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}
.info-item {
    flex: 1 1 45%;
    font-size: 14px;
}
.info-item .label {
    font-weight: 600;
    margin-right: 5px;
}
</style>
@endsection