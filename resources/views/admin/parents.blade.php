@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All parents</h3>
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
                        <div class="text-tiny">All parents</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box mb-10">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="{{ route('admin.parents.filter') }}">
                            <div class="filter-container">
                                <!-- Keyword Search -->
                                <div class="filter-item">
                                    <label for="keyword">Tìm kiếm</label>
                                    <input type="text" id="keyword" placeholder="Tên, email, số điện thoại..."
                                        name="keyword" value="{{ request('keyword') }}" aria-required="true">
                                </div>
                                <!-- Marketing Source Filter -->
                                <div class="filter-item">
                                    <label for="marketing_source">Nguồn marketing</label>
                                    <select id="marketing_source" name="marketing_source" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach ([
                                            'ads_content' => 'Ads & Content',
                                            'consultant' => 'Tư vấn viên',
                                            'class_management' => 'CSKH - Quản lý lớp học',
                                            'workshop' => 'Hội thảo',
                                            'sales_marketing' => 'Sale & Maketing',
                                            'teacher' => 'Giáo viên',
                                        ] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('marketing_source') == $value ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Learning Format Filter -->
                                <div class="filter-item">
                                    <label for="learning_format">Hình thức học</label>
                                    <select id="learning_format" name="learning_format" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach (['online' => 'Online', 'offline' => 'Offline', 'hybrid' => 'Hybrid'] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('learning_format') == $value ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Contact Status Filter -->
                                <div class="filter-item">
                                    <label for="status">Trạng thái</label>
                                    <select id="status" name="status" class="form-control">
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
                                        ] as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- From Date Filter -->
                                <div class="filter-item">
                                    <label for="from_date">Từ ngày</label>
                                    <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}"
                                        aria-required="false">
                                </div>
                                <!-- To Date Filter -->
                                <div class="filter-item">
                                    <label for="to_date">Đến ngày</label>
                                    <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}"
                                        aria-required="false">
                                </div>
                                <!-- Rows Per Page -->
                                <div class="filter-item">
                                    <label for="per_page">Số dòng</label>
                                    <select id="per_page" name="per_page" class="form-control">
                                        @foreach ([10, 25, 50, 100] as $option)
                                            <option value="{{ $option }}"
                                                {{ request('per_page', 10) == $option ? 'selected' : '' }}>{{ $option }}
                                                dòng</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Buttons -->
                                <div class="filter-buttons">
                                    <button type="submit" class="tf-button style-1 btn-search" title="Tìm kiếm"><i
                                            class="icon-search"></i></button>
                                    <a href="{{ route('admin.parents.filter') }}" class="tf-button style-1 btn-reset"
                                        title="Xóa bộ lọc"><i class="fa-solid fa-eraser"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.parent.add') }}"><i
                            class="icon-plus"></i>Add new</a>
                </div>
            </div>

            <div class="wg-box">
                <div class="table-responsive">
                    @if (Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif
                    @if (Session::has('success'))
                        <p class="alert alert-success">{{ Session::get('success') }}</p>
                    @endif
                    @if (Session::has('error'))
                        <p class="alert alert-danger">{{ Session::get('error') }}</p>
                    @endif
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 30px;text-align: center">STT</th>
                                <th style="width: 40px;text-align: center">Action</th>
                                <th style="text-align: center">Họ tên</th>
                                <th style="text-align: center">Địa chỉ</th>
                                <th style="width: 70px;text-align: center">Điện thoại</th>
                                <th style="width: 150px;text-align: center">Email</th>
                                <th style="width: 200px;text-align: center">Môn học</th>
                                <th style="width: 70px;text-align: center">Trạng thái liên hệ</th>
                                <th style="width: 45px;text-align: center">Hình thức học</th>
                                <th style="width: 70px;text-align: center">Nguồn marketing</th>
                                <th style="width: 40px;text-align: center">Ghi chú</th>
                                <th style="width: 70px;text-align: center">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($parents->isEmpty())
                                <tr>
                                    <td colspan="12">No parents found.</td>
                                </tr>
                            @else
                                @foreach ($parents as $parent)
                                    <tr>
                                        <td>{{ ($parents->currentPage() - 1) * $parents->perPage() + $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="action-wrapper">
                                                <div class="action-icon" data-parent-id="{{ $parent->id }}"
                                                    data-user-id="{{ $parent->user_id }}"
                                                    aria-label="Action menu for parent {{ $parent->id }}">
                                                    <i class="icon-settings"></i>
                                                </div>
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
                                            @if (empty($subjects))
                                                <span style="font-size: 14px" class="bg-secondary">N/A</span>
                                            @else
                                                @foreach ($subjects as $subjectId)
                                                    <span style="font-size: 14px" class="badge bg-primary me-1 mb-1">
                                                        {{ $allSubjects[$subjectId] ?? 'N/A' }}
                                                    </span>
                                                @endforeach
                                            @endif
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
                                                ];
                                            @endphp
                                            <select name="status" style="font-size: 15px" class="form-control"
                                                data-parent-id="{{ $parent->id }}">
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}"
                                                        {{ $parent->status == $status ? 'selected' : '' }}>
                                                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $parent->learning_format ?? 'N/A' }}</td>
                                        <td>
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
                                            {{ $marketingSourceLabels[$parent->marketing_source] ?? ($parent->marketing_source ?? 'N/A') }}
                                        </td>
                                        <td>{{ $parent->notes ?? 'N/A' }}</td>
                                        <td>{{ $parent->created_at->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="pagination-container">
                    @if ($parents->hasPages())
                        {{ $parents->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-muted">Không có đủ dữ liệu để phân trang.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .filter-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            align-items: end;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .filter-item {
            display: flex;
            flex-direction: column;
        }

        .filter-item label {
            font-size: 13px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .filter-item input,
        .filter-item select {
            padding: 8px 12px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
            width: 100%;
            box-sizing: border-box;
        }

        .filter-item input:focus,
        .filter-item select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .filter-item input[type="date"] {
            padding: 7px 12px;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .tf-button.style-1 {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .tf-button.style-1:hover {
            background-color: #0056b3;
        }

        .tf-button.btn-reset {
            background-color: #dc3545;
        }

        .tf-button.btn-reset:hover {
            background-color: #b02a37;
        }

        .tf-button i {
            margin-right: 5px;
        }

        .fa-eraser {
            color: #fff;
        }

        .pagination-container {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-top: 10px;
            text-align: center;
        }

        .pagination-container .pagination {
            margin: 0;
            justify-content: center;
            display: inline-flex;
            flex-wrap: wrap;
        }

        .pagination-container .page-item .page-link {
            border-radius: 6px;
            margin: 0 3px;
            padding: 8px 12px;
            font-size: 14px;
            color: #333;
            border: 1px solid #dcdcdc;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination-container .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .pagination-container .page-item .page-link:hover {
            background-color: #e9ecef;
            border-color: #dcdcdc;
        }

        .pagination-container .page-item.disabled .page-link {
            color: #6c757d;
            cursor: not-allowed;
        }

        .pagination-container .text-muted {
            font-size: 14px;
            margin: 0;
        }

        @media (max-width: 768px) {
            .filter-container {
                grid-template-columns: 1fr;
            }

            .filter-buttons {
                justify-content: flex-start;
            }

            .pagination-container .pagination {
                flex-wrap: wrap;
            }
        }
    </style>
@endsection

@push('scripts')
 <script>
        $(document).ready(function() {
            // Create a single popup dynamically
            $('body').append(`
                <div class="action-popup" id="single-action-popup" style="display: none; position: absolute; z-index: 1000;">
                    <a id="view-action" class="action-item"><i class="icon-eye"></i> Xem</a>
                    <a id="edit-action" class="action-item"><i class="icon-edit-3"></i> Sửa</a>
                    <form id="delete-action" method="POST" class="action-item delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-button delete">
                            <i class="icon-trash-2 text-danger"></i> Xóa
                        </button>
                    </form>
                </div>
            `);

            // Position and show popup
            $('.action-icon').on('click', function(e) {
                e.stopPropagation();
                const parentId = $(this).data('parent-id');
                const userId = $(this).data('user-id') || parentId; // Fallback to parentId if user-id not set
                console.log('Clicked action icon for parent ID:', parentId, 'user ID:', userId);

                // Update popup content
                $('#view-action').attr('href', '{{ route("admin.parent.view", "__USER_ID__") }}'.replace('__USER_ID__', userId));
                $('#edit-action').attr('href', '{{ route("admin.parent.edit", "__USER_ID__") }}'.replace('__USER_ID__', userId));
                $('#delete-action').attr('action', '{{ route("admin.parent.delete", "__USER_ID__") }}'.replace('__USER_ID__', userId));

                // Position popup near the clicked icon
                const popup = $('#single-action-popup');
                const icon = $(this);
                const offset = icon.offset();
                popup.css({
                    top: offset.top + icon.outerHeight(),
                    left: offset.left
                });

                // Show popup
                $('.action-popup').hide(); // Hide any other popups
                popup.show();
            });

            // Close popup when clicking outside
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.action-wrapper, #single-action-popup').length) {
                    console.log('Clicked outside, closing popup');
                    $('#single-action-popup').hide();
                }
            });

            // Prevent popup from closing when clicking inside
            $('#single-action-popup').on('click', function(e) {
                e.stopPropagation();
            });

            // Delete confirmation
            $('#delete-action').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                console.log('Delete form submitted for URL:', form.attr('action'));

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
            });

            // Status update
            $('select[name="status"]').on('change', function() {
                const parentId = $(this).data('parent-id');
                const newStatus = $(this).val();
                console.log('Status change for parent ID:', parentId, 'New status:', newStatus);

                $.ajax({
                    url: '/admin/parent/update-status',
                    method: 'POST',
                    data: {
                        parent_id: parentId,
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('Cập nhật trạng thái thành công');
                    },
                    error: function(xhr) {
                        toastr.error('Lỗi khi cập nhật trạng thái');
                    }
                });
            });

            // Auto-submit form when filter changes
            $('select[name="marketing_source"], select[name="learning_format"], select[name="status"], select[name="per_page"]').on('change', function() {
                $(this).closest('form').submit();
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

@endpush