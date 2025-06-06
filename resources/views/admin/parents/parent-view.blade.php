@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <!-- Breadcrumbs -->
        <div class="flex items-center flex-wrap justify-between gap20 mb-20">
            <h3>Chi tiết phụ huynh</h3>
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
                    <a href="{{ route('admin.parents') }}">
                        <div class="text-tiny">All parents</div>
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
                <h4>Thông tin phụ huynh: {{ $parent->full_name ?? 'N/A' }}</h4>
                <a class="tf-button style-1 w208" href="{{ route('admin.parents') }}">
                    <i class="icon-arrow-left"></i> Quay lại
                </a>
            </div>
            <div class="item" id="imgpreview" style="{{ $parent->image && $parent->image != 'default.png' ? '' : 'display: none;' }}">
                <img width="100px" src="{{ $parent->image && $parent->image != 'default.png' ? asset('uploads/avatars/' . $parent->image) : '' }}" class="effect8" alt="parent avatar">
            </div>
            <!-- Thông tin cá nhân -->
            <div class="info-section mb-15">
                <h5>Thông tin cá nhân</h5>
                
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Họ tên:</span> {{ $parent->full_name ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Email:</span> {{ $parent->email ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Điện thoại:</span> {{ $parent->mobile ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Địa chỉ:</span> {{ $parent->address ?? 'N/A' }}
                    </div>
                    
                </div>
                
            </div>
            
            <!-- Thông tin học tập -->
            <div class="info-section mb-15">
                <h5>Thông tin học tập</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        @php
                            $statusLabels = [
                                'pending' => 'Đang chờ',
                                'contacted' => 'Đã liên hệ',
                                'doubtf' => 'Nghi ngờ',
                                'completed' => 'Hoàn thành',
                                'interested' => 'Quan tâm',
                                'exploring' => 'Tìm hiểu',
                                'inactive'=> 'Ngừng khai thác',
                                'reserved' => 'Bảo lưu',
                                'rejected' => 'Từ chối',
                                'contact_again' => 'Liên hệ lại',
                            ];
                        @endphp
                        <span class="label">Trạng thái liên hệ:</span> {{ $statusLabels[$parent->status] ?? ($parent->status ?? 'N/A') }}
                    </div>
                    <div class="info-item">
                        <span class="label">Hình thức học:</span> {{ $parent->learning_format ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Trường:</span> {{ $parent->school ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Lớp:</span> {{ $parent->grade ?? 'N/A' }}
                    </div>
                   <div class="info-item">
                    <span class="label">Môn học:</span>
                    @if (!empty($subjects) && is_array($subjects))
                        @foreach ($subjects as $subject)
                            <span style="font-size: 15px" class="badge bg-primary me-1 mb-1">{{ $subject }}</span>
                        @endforeach
                    @else
                        <span style="font-size: 16px" class="badge bg-secondary">N/A</span>
                    @endif
                </div>
                    <div class="info-item">
                        @php
                            $marketingSourceLabels = [
                                'ads_content' => 'Ads & Content',
                                'consultant' => 'Tư vấn viên',
                                'class_management' => 'CSKH - Quản lý lớp học',
                                'workshop' => 'Hội thảo',
                                'sales_marketing' => 'Sale & Maketing',
                                'teacher' => 'Giáo viên',
                            ];
                        @endphp
                        <span class="label">Nguồn marketing:</span> {{ $marketingSourceLabels[$parent->marketing_source] ?? ($parent->marketing_source ?? 'N/A') }}
                    </div>
                </div>
            </div>

            <!-- Ghi chú -->
            <div class="info-section mb-15">
                <h5>Ghi chú</h5>
                <div class="info-group">
                    <p style="margin: 0;">{{ $parent->notes ?? 'Không có ghi chú.' }}</p>
                </div>
            </div>

            <!-- Thông tin hệ thống -->
            <div class="info-section">
                <h5>Thông tin hệ thống</h5>
                <div class="info-group flex flex-wrap gap15">
                    <div class="info-item">
                        <span class="label">Ngày tạo:</span> {{ $parent->created_at ? $parent->created_at : 'N/A' }}
                    </div>
                    <div class="info-item">
                        <span class="label">Ngày cập nhật:</span> {{ $parent->updated_at ? $parent->updated_at : 'N/A' }}
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