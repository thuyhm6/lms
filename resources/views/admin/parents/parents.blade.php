@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <!-- Header Section -->
            <div class="page-header">
                <div class="header-content">
                    <h3 class="page-title">Tất cả phụ huynh</h3>
                    <ul class="breadcrumbs">
                        <li><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li><i class="icon-chevron-right"></i></li>
                        <li>All parents</li>
                    </ul>
                </div>
                <div class="header-actions">
                    <a class="btn-primary" href="{{ route('admin.parent.add') }}">
                        <i class="icon-plus"></i>Thêm mới
                    </a>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="stats-section">
                <h4 class="stats-title">Tổng quan trạng thái liên hệ</h4>
                <div class="stats-grid">
                    @php
                        // Define status statistics with initial counts
                        $statusStats = [
                            'pending' => [
                                'label' => 'Chờ đợi',
                                'count' => 0,
                                'color' => '#17a2b8',
                                'icon' => 'hourglass-half',
                            ],
                            'interested' => [
                                'label' => 'Quan tâm',
                                'count' => 0,
                                'color' => '#007bff',
                                'icon' => 'heart',
                            ],
                            'exploring' => [
                                'label' => 'Tìm hiểu',
                                'count' => 0,
                                'color' => '#ff00ff',
                                'icon' => 'search',
                            ],
                            'doubtful' => [
                                'label' => 'Nghi ngờ',
                                'count' => 0,
                                'color' => '#ff8c00',
                                'icon' => 'question-circle',
                            ],
                            'rejected' => [
                                'label' => 'Từ chối',
                                'count' => 0,
                                'color' => '#dc3545',
                                'icon' => 'times-circle',
                            ],
                            'completed' => [
                                'label' => 'Hoàn thành',
                                'count' => 0,
                                'color' => '#28a745',
                                'icon' => 'check-circle',
                            ],
                            'reserved' => [
                                'label' => 'Bảo lưu',
                                'count' => 0,
                                'color' => '#17a2b8',
                                'icon' => 'pause-circle',
                            ],
                            'contact_again' => [
                                'label' => 'Liên hệ lại',
                                'count' => 0,
                                'color' => '#ffc107',
                                'icon' => 'sync-alt',
                            ],
                            'appointment_success' => [
                                'label' => 'Hẹn thành công',
                                'count' => 0,
                                'color' => '#28a745',
                                'icon' => 'calendar-check',
                            ],
                        ];

                        // Initialize $totalStats as an empty array to avoid undefined variable error
                        $totalStats = [];

                        try {
                            // Query the database to get total counts for each status
                            $totalStats = \App\Models\ParentModel::select('status', \DB::raw('count(*) as count'))
                                ->groupBy('status')
                                ->get()
                                ->pluck('count', 'status')
                                ->toArray();
                        } catch (\Exception $e) {
                            // Log the error for debugging
                            \Log::error('Error fetching parent status stats: ' . $e->getMessage());
                            // Set $totalStats to an empty array if the query fails
                            $totalStats = [];
                        }

                        // Update the counts in $statusStats with total values from the database
                        foreach ($statusStats as $status => &$stat) {
                            if (array_key_exists($status, $totalStats)) {
                                $stat['count'] = $totalStats[$status];
                            }
                        }
                        unset($stat); // Unset the reference to avoid issues
                    @endphp

                    @foreach ($statusStats as $status => $stat)
                        <div class="stat-card" data-status="{{ $status }}" style="background: {{ $stat['color'] }}">
                            <div class="stat-icon">
                                <i class="fa-solid fa-{{ $stat['icon'] }}"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">{{ $stat['label'] }}</div>
                                <div class="stat-count">{{ $stat['count'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <form class="filters-form" method="GET" action="{{ route('admin.parents.filter') }}">
                    <div class="filters-grid">
                        <!-- Keyword Search -->
                        <div class="filter-group">
                            <label for="keyword">Tìm kiếm</label>
                            <input type="text" id="keyword" name="keyword" placeholder="Tên, email, số điện thoại..."
                                value="{{ request('keyword') }}">
                        </div>

                        <!-- Marketing Source Filter -->
                        <div class="filter-group">
                            <label for="marketing_source">Nguồn marketing</label>
                            <select id="marketing_source" name="marketing_source">
                                <option value="">Tất cả</option>
                                @foreach ([
            'ads_content' => 'Ads & Content',
            'consultant' => 'Tư vấn viên',
            'class_management' => 'CSKH - Quản lý lớp học',
            'workshop' => 'Hội thảo',
            'sales_marketing' => 'Sale & Marketing',
            'teacher' => 'Giáo viên',
        ] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('marketing_source') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Learning Format Filter -->
                        <div class="filter-group">
                            <label for="learning_format">Hình thức học</label>
                            <select id="learning_format" name="learning_format">
                                <option value="">Tất cả</option>
                                @foreach (['online' => 'Online', 'offline' => 'Offline', 'hybrid' => 'Hybrid'] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('learning_format') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="filter-group">
                            <label for="status">Trạng thái</label>
                            <select id="status" name="status">
                                <option value="">Tất cả</option>
                                @foreach ([
            'pending' => 'Đang chờ',
            'contacted' => 'Đã liên hệ',
            'doubtful' => 'Nghi ngờ',
            'completed' => 'Hoàn thành',
            'interested' => 'Quan tâm',
            'exploring' => 'Tìm hiểu',
            'inactive' => 'Ngừng khai thác',
            'reserved' => 'Bảo lưu',
            'rejected' => 'Từ chối',
            'contact_again' => 'Liên hệ lại',
            'appointment_success' => 'Hẹn thành công',
        ] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ request('status') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Filters -->
                        <div class="filter-group">
                            <label for="from_date">Từ ngày</label>
                            <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}">
                        </div>

                        <div class="filter-group">
                            <label for="to_date">Đến ngày</label>
                            <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}">
                        </div>

                        <!-- Per Page -->
                        <div class="filter-group">
                            <label for="per_page">Số dòng</label>
                            <select id="per_page" name="per_page">
                                @foreach ([10, 25, 50, 100] as $option)
                                    <option value="{{ $option }}"
                                        {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                        {{ $option }} dòng
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="filter-actions">
                            <button type="submit" class="btn-search" title="Tìm kiếm">
                                <i class="icon-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.parents.filter') }}" class="btn-reset" title="Xóa bộ lọc">
                                <i class="fa-solid fa-eraser"></i> Xóa bộ lọc
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Data Table Section -->
            <div class="data-section">
                <!-- Alert Messages -->
                @if (Session::has('status'))
                    <div class="alert alert-success">{{ Session::get('status') }}</div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                @endif

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="col-stt">STT</th>
                                <th class="col-action">Action</th>
                                <th class="col-name">Họ tên</th>
                                <th class="col-address">Địa chỉ</th>
                                <th class="col-phone">Điện thoại</th>
                                <th class="col-email">Email</th>
                                <th class="col-subjects">Môn học</th>
                                <th class="col-status">Trạng thái</th>
                                <th class="col-format">Hình thức</th>
                                <th class="col-source">Nguồn marketing</th>
                                <th class="col-date">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($parents as $key => $parent)
                                <tr>
                                    <td class="text-center">
                                        {{ $parents->firstItem() + $key }}
                                    </td>
                                    <td class="text-center">
                                        <div class="action-dropdown">
                                            <button class="action-btn" data-parent-id="{{ $parent->user_id }}"
                                                data-parent-name="{{ $parent->full_name }}"
                                                data-user-id="{{ $parent->user_id }}">
                                                <i class="icon-settings"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>{{ $parent->user->full_name ?? 'N/A' }}</td>
                                    <td>{{ $parent->user->address ?? 'N/A' }}</td>
                                    <td>{{ $parent->user->mobile ?? 'N/A' }}</td>
                                    <td>{{ $parent->user->email ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $subjects = json_decode($parent->subjects, true) ?? [];
                                        @endphp
                                        <div class="subjects-list">
                                            @forelse ($subjects as $subjectId)
                                                <span class="subject-tag">
                                                    {{ $allSubjects[$subjectId] ?? 'N/A' }}
                                                </span>
                                            @empty
                                                <span class="no-data">N/A</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusLabels = [
                                                'pending' => 'Đang chờ',
                                                'contacted' => 'Đã liên hệ',
                                                'doubtful' => 'Nghi ngờ',
                                                'completed' => 'Hoàn thành',
                                                'interested' => 'Quan tâm',
                                                'exploring' => 'Tìm hiểu',
                                                'inactive' => 'Ngừng khai thác',
                                                'reserved' => 'Bảo lưu',
                                                'rejected' => 'Từ chối',
                                                'contact_again' => 'Liên hệ lại',
                                                'appointment_success' => 'Hẹn thành công',
                                            ];
                                            $restrictedStatuses = ['contact_again', 'appointment_success'];
                                        @endphp
                                        <select class="status-select" data-parent-id="{{ $parent->user_id }}">
                                            <!-- Hiển thị trạng thái hiện tại nếu nó bị hạn chế -->
                                            @if (in_array($parent->status, $restrictedStatuses))
                                                <option value="{{ $parent->status }}" selected>
                                                    {{ $statusLabels[$parent->status] ?? ucfirst($parent->status) }}
                                                </option>
                                            @endif
                                            <!-- Hiển thị các trạng thái không bị hạn chế -->
                                            @foreach ($statuses as $status)
                                                @if (!in_array($status, $restrictedStatuses))
                                                    <option value="{{ $status }}"
                                                        {{ $parent->status == $status ? 'selected' : '' }}>
                                                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <span class="format-badge format-{{ $parent->learning_format }}">
                                            {{ ucfirst($parent->learning_format ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $marketingSourceLabels = [
                                                'ads_content' => 'Ads & Content',
                                                'consultant' => 'Tư vấn viên',
                                                'class_management' => 'CSKH',
                                                'workshop' => 'Hội thảo',
                                                'sales_marketing' => 'Sale & Marketing',
                                                'teacher' => 'Giáo viên',
                                            ];
                                        @endphp
                                        {{ $marketingSourceLabels[$parent->marketing_source] ?? ($parent->marketing_source ?? 'N/A') }}
                                    </td>
                                    <td>{{ $parent->created_at->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="no-data-row">
                                        <div class="no-data-message">
                                            <i class="icon-users"></i>
                                            <p>Không tìm thấy phụ huynh nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    @if ($parents->hasPages())
                        {{ $parents->links('pagination::bootstrap-5') }}
                    @else
                        <p class="pagination-info">Không có đủ dữ liệu để phân trang.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Date Filter Modal -->
    <div class="modal" id="contact-date-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Chọn ngày liên hệ</h5>
                <button class="modal-close" onclick="closeModal('contact-date-modal')">&times;</button>
            </div>
            <form id="contact-date-form" method="GET" action="{{ route('admin.parents.filter') }}">
                <input type="hidden" name="status" id="contact-date-status">
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                <input type="hidden" name="marketing_source" value="{{ request('marketing_source') }}">
                <input type="hidden" name="learning_format" value="{{ request('learning_format') }}">
                <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

                <div class="form-group">
                    <label for="contact_date_filter" style="margin-top: 10px">Ngày liên hệ</label>
                    <input type="date" id="contact_date_filter" name="contact_date_filter"
                        value="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('contact-date-modal')">Hủy</button>
                    <button type="submit" class="btn-primary">Áp dụng</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Appointment Modal -->
    <div class="modal" id="create-appointment-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Tạo lịch hẹn với phụ huynh: <span class="span-name"></span></h5>
                <button class="modal-close" onclick="closeModal('create-appointment-modal')">&times;</button>
            </div>
            <form id="create-appointment-form" style="margin-top: 10px" method="POST"
                action="{{ route('admin.parent.appointment.create') }}">
                @csrf
                <input type="hidden" name="parent_id" id="create-appointment-parent-id">

                <div class="form-group">
                    <label for="appointment-title">Tiêu đề</label>
                    <input type="text" id="appointment-title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="appointment-content">Nội dung</label>
                    <textarea id="appointment-content" name="content" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="appointment-status">Trạng thái liên hệ</label>
                    <select id="appointment-status" name="status">
                        <option value="contact_again">Liên hệ lại</option>
                        <option value="appointment_success">Hẹn thành công</option>
                    </select>
                </div>

                <div class="form-group" style="margin-top: 10px">
                    <label for="contact_date">Ngày liên hệ</label>
                    <input type="datetime-local" name="contact_date" id="contact_date">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary"
                        onclick="closeModal('create-appointment-modal')">Hủy</button>
                    <button type="submit" class="btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Appointments Modal -->
    <div class="modal" id="view-appointments-modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h5>Lịch sử hẹn với phụ huynh: <span class="span-name"></span></h5>
                <button class="modal-close" onclick="closeModal('view-appointments-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <div id="appointments-list">
                    <!-- Appointments will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary"
                    onclick="closeModal('view-appointments-modal')">Đóng</button>
            </div>
        </div>
    </div>

    <!-- Action Popup -->
    <div class="action-popup" id="action-popup">
        <a href="#" id="view-action" class="action-item">
            <i class="icon-eye"></i> Xem
        </a>
        <a href="#" id="edit-action" class="action-item">
            <i class="icon-edit-3"></i> Sửa
        </a>
        <form id="delete-action" method="POST" class="action-item">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">
                <i class="icon-trash-2"></i> Xóa
            </button>
        </form>
        <a href="#" id="create-appointment" class="action-item">
            <i class="icon-plus"></i> Tạo lịch hẹn
        </a>
        <a href="#" id="view-appointments" class="action-item">
            <i class="icon-inbox"></i> Xem lịch hẹn
        </a>
    </div>

    <style>
        .span-name {
            font-weight: bold;
            color: #ffffff;
            background-color: #2bff17;
            padding: 4px 8px;
            border-radius: 7px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 20px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .header-content .page-title {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }

        .breadcrumbs {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 14px;
            color: #6c757d;
        }

        .breadcrumbs a {
            color: #007bff;
            text-decoration: none;
        }

        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        /* Statistics Section */
        .stats-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-title {
            margin: 0 0 20px 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 32px;
            opacity: 0.9;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            margin-bottom: 4px;
        }

        .stat-count {
            display: block;
            font-size: 24px;
            font-weight: 700;
        }

        /* Filters Section */
        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            width: 400px;
        }

        .btn-search,
        .btn-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 40px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 17px;
        }

        .btn-search {
            background: #007bff;
            color: white;
        }

        .btn-search:hover {
            background: #0056b3;
        }

        .btn-reset {
            background: #dc3545;
            color: white;
        }

        .btn-reset:hover {
            background: #b02a37;
        }

        /* Data Section */
        .data-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .alert {
            margin: 20px 24px;
            padding: 12px 16px;
            border-radius: 6px;
            border: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .table-wrapper {
            overflow-x: auto;
            padding: 5px;
            border-radius: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .data-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #f8f9fa;
            vertical-align: middle;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .text-center {
            text-align: center;
        }

        /* Column Specific Styles */
        .col-stt {
            width: 60px;
            text-align: center;
        }

        .col-action {
            width: 80px;
            text-align: center;
        }

        .col-phone {
            width: 120px;
        }

        .col-email {
            width: 180px;
        }

        .col-subjects {
            width: 200px;
        }

        .col-status {
            width: 160px;
        }

        .col-format {
            width: 100px;
        }

        .col-source {
            width: 140px;
        }

        .col-date {
            width: 140px;
        }

        /* Action Dropdown */
        .action-dropdown {
            position: relative;
        }

        .action-btn {
            background: none;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #f8f9fa;
            border-color: #007bff;
        }

        /* Subject Tags */
        .subjects-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .subject-tag {
            display: inline-block;
            background: #1988ff;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 15px;
        }

        /* Status Select */
        .status-select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
        }

        /* Format Badge */
        .format-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .format-online {
            background: #e3f2fd;
            color: #1976d2;
        }

        .format-offline {
            background: #fff3e0;
            color: #f57c00;
        }

        .format-hybrid {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        /* No Data */
        .no-data,
        .no-data-row {
            color: #6c757d;
            font-style: italic;
        }

        .no-data-message {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .no-data-message i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .no-data-message p {
            margin: 0;
            font-size: 16px;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 24px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }

        .pagination-info {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Modals */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease-out;
            padding: 20px;
        }

        .modal-large {
            max-width: 900px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            border-bottom: 1px solid #dee2e6;
        }

        .modal-header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;

        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: #f8f9fa;
            color: #495057;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 24px;
            border-top: 1px solid #dee2e6;
            background: #ffffff;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 500;
            color: #495057;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        /* Action Popup */
        .action-popup {
            display: none;
            position: absolute;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border: 1px solid #dee2e6;
            z-index: 1000;
            min-width: 180px;
            overflow: hidden;
        }

        .action-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            color: #495057;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .action-item:hover {
            background: #f8f9fa;
            color: #007bff;
        }

        .action-item.delete-form {
            padding: 0;
        }

        .delete-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0px 16px;
            color: #dc3545;
            background: none;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-align: left;
            margin-left: -15px;
        }

        .delete-btn:hover {
            background: #f8f9fa;
            color: #b02a37;
        }

        /* Appointments Table */
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .appointments-table th,
        .appointments-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        .appointments-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .appointments-table tbody tr:hover {
            background: #f8f9fa;
        }

        .appointment-status-select {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 13px;
        }

        /* Animations */
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                justify-content: flex-start;
            }

            .modal-content {
                margin: 10px;
                width: calc(100% - 20px);
                max-width: none;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 8px 6px;
            }

            /* Hide less important columns on mobile */
            .col-address,
            .col-source {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-icon {
                font-size: 24px;
            }

            .stat-count {
                font-size: 20px;
            }
        }

        /* Print Styles */
        @media print {

            .page-header,
            .filters-section,
            .col-action,
            .action-popup,
            .modal {
                display: none !important;
            }

            .data-section {
                box-shadow: none;
                border: 1px solid #000;
            }

            .data-table th,
            .data-table td {
                border: 1px solid #000;
                padding: 8px 4px;
                font-size: 10px;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // CSRF Token Setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Statistics Card Click Handler
            $('.stat-card').on('click', function(e) {
                e.preventDefault();
                const status = $(this).data('status');

                // Update the status filter in the form
                const form = $('.filters-form');
                form.find('select[name="status"]').val(status);

                // Clear contact date filter for regular statuses
                form.find('input[name="contact_date_filter"]').val('');

                // Show contact date modal only for specific statuses
                if (status === 'contact_again' || status === 'appointment_success') {
                    $('#contact-date-status').val(status);
                    $('#contact-date-modal').show();
                } else {
                    form.submit();
                }
            });

            // Auto-submit filters on change
            $('.filters-form select[name="marketing_source"], .filters-form select[name="learning_format"], .filters-form select[name="per_page"]')
                .on('change', function() {
                    $(this).closest('form').submit();
                });

            // Action Button Click Handler
            $('.action-btn').on('click', function(e) {
                e.stopPropagation();
                const parentId = $(this).data('parent-id');
                const userId = $(this).data('user-id') || parentId;
                const parentName = $(this).data('parent-name') || 'Phụ huynh không xác định';

                // popup links
                $('#view-action').attr('href', '{{ route('admin.parent.view', '__USER_ID__') }}'.replace(
                    '__USER_ID__', userId));
                $('#edit-action').attr('href', '{{ route('admin.parent.edit', '__USER_ID__') }}'.replace(
                    '__USER_ID__', userId));
                $('#delete-action').attr('action', '{{ route('admin.parent.delete', '__USER_ID__') }}'
                    .replace('__USER_ID__', userId));
                $('#create-appointment').attr('data-parent-id', parentId);
                $('#create-appointment').attr('data-parent-name', parentName);
                $('#view-appointments').attr('data-parent-id', parentId);
                $('#view-appointments').attr('data-parent-name', parentName);

                // Position and show popup
                const popup = $('#action-popup');
                const button = $(this);
                const offset = button.offset();

                popup.css({
                    top: offset.top + button.outerHeight() + 5,
                    left: offset.left
                });

                $('.action-popup').hide();
                popup.show();
            });

            // Close popup when clicking outside
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.action-dropdown, #action-popup').length) {
                    $('#action-popup').hide();
                }
            });

            // Prevent popup from closing when clicking inside
            $('#action-popup').on('click', function(e) {
                e.stopPropagation();
            });

            // Delete Confirmation
            $('#delete-action').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "Bạn có chắc chắn?",
                        text: "Bạn muốn xóa bản ghi này?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#dc3545",
                        cancelButtonText: "Không",
                        confirmButtonText: "Có"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form[0].submit();
                        }
                    });
                } else {
                    if (confirm('Bạn có chắc chắn muốn xóa bản ghi này?')) {
                        form[0].submit();
                    }
                }
            });

            // Create Appointment Modal
            $('#create-appointment').on('click', function() {
                const parentId = $(this).attr('data-parent-id');
                const parentName = $(this).attr('data-parent-name') || 'Phụ huynh không xác định';
                $('#create-appointment-modal').find('h5 .span-name').text(`${parentName}`);
                $('#create-appointment-parent-id').val(parentId);
                $('#create-appointment-modal').show();
                $('#action-popup').hide();
            });

            // View Appointments Modal
            $('#view-appointments').on('click', function() {
                const parentId = $(this).attr('data-parent-id');
                const parentName = $(this).attr('data-parent-name') || 'Phụ huynh không xác định';
                fetchAppointments(parentId);

                $('#view-appointments-modal').find('h5 .span-name').text(`${parentName}`);
                $('#view-appointments-modal').show();
                $('#action-popup').hide();
            });

            // Create Appointment Form Submission
            $('#create-appointment-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const formData = new FormData(form[0]);

                // Convert datetime-local to proper format if needed
                const contactDate = form.find('#contact_date').val();
                if (contactDate) {
                    const date = new Date(contactDate);
                    const formattedDate = date.getFullYear() + '-' +
                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                        String(date.getDate()).padStart(2, '0') + ' ' +
                        String(date.getHours()).padStart(2, '0') + ':' +
                        String(date.getMinutes()).padStart(2, '0') + ':00';
                    formData.set('contact_date', formattedDate);
                }

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        showToast('Tạo lịch hẹn thành công', 'success');
                        closeModal('create-appointment-modal');
                        form[0].reset();
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error('Error creating appointment:', xhr.responseText);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = 'Vui lòng kiểm tra dữ liệu:\n';
                            for (let field in errors) {
                                errorMessage += `- ${errors[field].join(', ')}\n`;
                            }
                            showToast(errorMessage, 'error');
                        } else {
                            showToast('Lỗi khi tạo lịch hẹn', 'error');
                        }
                    }
                });
            });

            // Handle appointment status change
            $(document).on('change', '.appointment-status-select', function() {
                const appointmentId = $(this).data('appointment-id');
                const parentId = $(this).data('parent-id');
                const newStatus = $(this).val();

                $.ajax({
                    url: '{{ route('admin.parent.update-status') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        id: appointmentId, //Thay đổi chỗ này
                        parent_id: parentId,
                        status: newStatus
                    }),
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Cập nhật trạng thái thành công');
                            // Refresh lại danh sách cuộc hẹn
                            fetchAppointments(parentId);
                        } else {
                            toastr.error(response.message || 'Lỗi khi cập nhật trạng thái');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating status:', xhr.responseText);
                        const errorMsg = xhr.responseJSON?.message ||
                            'Lỗi khi cập nhật trạng thái';
                        toastr.error(errorMsg);
                    }
                });
            });

            // Status select change handler for table rows
            $('.status-select').on('change', function() {
                const parentUserId = $(this).data('parent-id');
                const newStatus = $(this).val();
                const appointmentId = $(this).data('appointment-id');

                $.ajax({
                    url: '{{ route('admin.parent.update-status') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data: JSON.stringify({
                        parent_id: parentUserId,
                        status: newStatus,
                        id: appointmentId //Chỗ này
                    }),
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Cập nhật trạng thái thành công');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error(response.message || 'Lỗi khi cập nhật trạng thái');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        toastr.error(xhr.responseJSON?.message ||
                        'Lỗi khi cập nhật trạng thái');
                    }
                });
            });
        });

        // Modal Functions
        function closeModal(modalId) {
            $('#' + modalId).hide();
        }

        // Close modal when clicking outside
        $(document).on('click', '.modal', function(e) {
            if (e.target === this) {
                $(this).hide();
            }
        });

        // Fetch Appointments Function
        function fetchAppointments(parentId) {
            fetch('{{ route('admin.parent.appointments') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        parent_id: parentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const appointmentsList = $('#appointments-list');
                    appointmentsList.empty();

                    if (data.appointments.length === 0) {
                        appointmentsList.append(`
                        <div class="no-data-message">
                            <i class="icon-calendar"></i>
                            <p>Không có lịch hẹn nào.</p>
                        </div>
                    `);
                    } else {
                        const statusLabels = {
                            'pending': 'Đang chờ',
                            'contacted': 'Đã liên hệ',
                            'doubtful': 'Nghi ngờ',
                            'completed': 'Hoàn thành',
                            'interested': 'Quan tâm',
                            'exploring': 'Tìm hiểu',
                            'inactive': 'Ngừng khai thác',
                            'reserved': 'Bảo lưu',
                            'rejected': 'Từ chối',
                            'contact_again': 'Liên hệ lại',
                            'appointment_success': 'Hẹn thành công'
                        };

                        const table = `
                        <table class="appointments-table">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày liên hệ</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.appointments.map(appointment => `
                                        <tr>
                                            <td>${appointment.title}</td>
                                            <td>${appointment.content}</td>
                                            <td>
                                                <select class="appointment-status-select" 
                                                        data-appointment-id="${appointment.id}"
                                                        data-parent-id="${parentId}">
                                                    ${Object.entries(statusLabels).map(([value, label]) => `
                                                    <option value="${value}" ${appointment.status === value ? 'selected' : ''}>
                                                        ${label}
                                                    </option>
                                                `).join('')}
                                                </select>
                                            </td>
                                            <td>${appointment.contact_date || 'N/A'}</td>
                                            <td>${appointment.created_at}</td>
                                        </tr>
                                    `).join('')}
                            </tbody>
                        </table>
                    `;
                        appointmentsList.append(table);
                    }
                })
                .catch(error => {
                    console.error('Error fetching appointments:', error);
                    showToast('Lỗi khi tải danh sách lịch hẹn', 'error');
                });
        }

        // Toast Notification Function
        function showToast(message, type = 'info') {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                // Fallback to alert if toastr is not available
                alert(message);
            }
        }

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // ESC key to close modals
            if (e.keyCode === 27) {
                $('.modal:visible').hide();
                $('#action-popup:visible').hide();
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    <!-- Include SweetAlert2 if not already included -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
@endpush
