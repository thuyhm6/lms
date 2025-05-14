@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-20">
            <h3>Chi tiết học sinh</h3>
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
                    <a href="{{ route('admin.students') }}">
                        <div class="text-tiny">All students</div>
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
                <h4>Thông tin học sinh: {{ $student->user->full_name ?? 'N/A' }}</h4>
                <a class="tf-button style-1 w208" href="{{ route('admin.students') }}">
                    <i class="icon-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Thông tin cá nhân -->
            <div class="info-section mb-15">
                <h5>Thông tin cá nhân</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Họ tên:</span> {{ $student->user->full_name ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Email:</span> {{ $student->user->email ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Điện thoại:</span> {{ $student->user->mobile ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Địa chỉ:</span> {{ $student->user->address ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Sinh nhật:</span> 
                        @if($student->user->birthday)
                            {{ \Carbon\Carbon::parse($student->user->birthday)->format('d/m/Y') }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="info-item">
                        <span class="label">Giới tính:</span> {{ $student->user->gender  }}
                    </div>
                    <div class="info-item">
                        <span class="label">Trường:</span> {{ $student->school  }}
                    </div>
                    <div class="info-item">
                        <span class="label">Lớp:</span> {{ $student->grade  }}
                    </div>
                </div>
            </div>

            <!-- Thông tin học tập -->
            <div class="info-section mb-15">
                <h5>Thông tin khác</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Hoạt động:</span> {{ $student->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Ghi chú:</span> {{ $student->notes ?? 'Không có ghi chú.' }}
                    </div>
                </div>
            </div>

            <!-- Thông tin phụ hunh -->
            <div class="info-section">
                <h5>Thông tin phụ hunh</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Phụ huynh:</span> {{ $parent->parent_name }} - {{ $parent->parent_mobile}}
                    </div>
                    <div class="info-item">
                        <span class="label">Địa chỉ</span> {{ $parent->parent_address }}
                    </div>
                </div>
            </div>
            <!-- Thông tin hệ thống -->
            <div class="info-section">
                <h5>Thông tin hệ thống</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Ngày đăng ký:</span> {{ $student->created_at ? $student->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Ngày cập nhật:</span> {{ $student->updated_at ? $student->updated_at->format('d/m/Y H:i') : 'N/A' }}
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