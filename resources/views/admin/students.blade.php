@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>All Students</h3>
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
                    <div class="text-tiny">All students</div>
                </li>
            </ul>
        </div>

        <div class="wg-box mb-10">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search" method="GET" action="{{ route('admin.students.filter') }}">
                        <div class="filter-container">
                            <!-- Keyword Search -->
                            <div class="filter-item">
                                <label for="keyword">Tìm kiếm</label>
                                <input type="text" id="keyword" placeholder="Tên, email, số điện thoại..."
                                    name="keyword" value="{{ request('keyword') }}" aria-required="true">
                            </div>
                            <!-- Birthday From Filter -->
                            <div class="filter-item">
                                <label for="birthday_from">Sinh nhật từ</label>
                                <input type="date" id="birthday_from" name="birthday_from" value="{{ request('birthday_from') }}"
                                    aria-required="false">
                            </div>
                            <!-- Birthday To Filter -->
                            <div class="filter-item">
                                <label for="birthday_to">Sinh nhật đến</label>
                                <input type="date" id="birthday_to" name="birthday_to" value="{{ request('birthday_to') }}"
                                    aria-required="false">
                            </div>
                            <!-- Gender Filter -->
                            <div class="filter-item">
                                <label for="gender">Giới tính</label>
                                <select id="gender" name="gender" class="form-control">
                                    <option value="">Tất cả</option>
                                    <option value="Nam" {{ request('gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ request('gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                            <!-- Rows Per Page -->
                            <div class="filter-item">
                                <label for="per_page">Số dòng</label>
                                <select id="per_page" name="per_page" class="form-control">
                                    @foreach ([4, 10, 25, 50, 100] as $option)
                                        <option value="{{ $option }}"
                                            {{ request('per_page', 4) == $option ? 'selected' : '' }}>{{ $option }}
                                            dòng</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Buttons -->
                            <div class="filter-buttons">
                                <button type="submit" class="tf-button style-1 btn-search" title="Tìm kiếm"><i
                                        class="icon-search"></i></button>
                                <a href="{{ route('admin.students.filter') }}" class="tf-button style-1 btn-reset"
                                    title="Xóa bộ lọc"><i class="fa-solid fa-eraser"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.student.add') }}"><i
                        class="icon-plus"></i>Add new</a>
            </div>
        </div>

        <div class="wg-box">
            <div class="table-responsive">
                @if(Session::has('status'))
                    <p class="alert alert-success">{{ Session::get('status') }}</p>
                @endif
                @if(Session::has('success'))
                    <p class="alert alert-success">{{ Session::get('success') }}</p>
                @endif
                @if(Session::has('error'))
                    <p class="alert alert-danger">{{ Session::get('error') }}</p>
                @endif
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 30px; text-align: center">STT</th>
                            <th style="width: 40px; text-align: center">Action</th>
                            <th style="text-align: center">Họ tên</th>
                            <th style="text-align: center">Địa chỉ</th>
                            <th style="width: 70px; text-align: center">Điện thoại</th>
                            <th style="width: 150px; text-align: center">Email</th>
                            <th style="width: 70px; text-align: center">Sinh nhật</th>
                            <th style="width: 45px; text-align: center">Giới tính</th>
                            <th style="width: 70px; text-align: center">Hoạt động</th>
                            <th style="width: 40px; text-align: center">Ghi chú</th>
                            <th style="width: 70px; text-align: center">Ngày đăng ký</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($students->isEmpty())
                            <tr>
                                <td colspan="11">No students found.</td>
                            </tr>
                        @else
                            @foreach($students as $student)
                                <tr>
                                    <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="action-wrapper">
                                            <div class="action-icon" data-student-id="{{ $student->id }}"
                                                data-user-id="{{ $student->user_id }}"
                                                aria-label="Action menu for student {{ $student->id }}">
                                                <i class="icon-settings"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->user->full_name ?? 'N/A' }}</td>
                                    <td>{{ $student->user->address ?? 'N/A' }}</td>
                                    <td>{{ $student->user->mobile ?? 'N/A' }}</td>
                                    <td>{{ $student->user->email ?? 'N/A' }}</td>
                                    <td>
                                        @if($student->user->birthday)
                                            {{ \Carbon\Carbon::parse($student->user->birthday)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $student->user->gender }}</td>
                                    <td>
                                        <input type="checkbox" class="display-homepage-toggle"
                                            data-student-id="{{ $student->id }}"
                                            {{ $student->status == 1 ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $student->notes ?? 'N/A' }}</td>
                                    <td>{{ $student->created_at->format('d/m/Y H:i') ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="pagination-container">
                @if ($students->hasPages())
                    {{ $students->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        const studentId = $(this).data('student-id');
        const userId = $(this).data('user-id') || studentId;
        console.log('Clicked action icon - Student ID:', studentId, 'User ID:', userId);

        // Update popup content
        const viewUrl = '{{ route("admin.student.view", "__USER_ID__") }}'.replace('__USER_ID__', userId);
        const editUrl = '{{ route("admin.student.edit", "__USER_ID__") }}'.replace('__USER_ID__', userId);
        const deleteUrl = '{{ route("admin.student.delete", "__USER_ID__") }}'.replace('__USER_ID__', userId);
        $('#view-action').attr('href', viewUrl);
        $('#edit-action').attr('href', editUrl);
        $('#delete-action').attr('action', deleteUrl);
        console.log('Popup URLs - View:', viewUrl, 'Edit:', editUrl, 'Delete:', deleteUrl);

        // Position popup
        const popup = $('#single-action-popup');
        const icon = $(this);
        const offset = icon.offset();
        popup.css({
            top: offset.top + icon.outerHeight(),
            left: offset.left
        });

        // Show popup
        $('.action-popup').hide();
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

    // Delete confirmation with AJAX
    $('#delete-action').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const deleteUrl = form.attr('action');
        console.log('Delete form submitted - URL:', deleteUrl);

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
                console.log('User confirmed deletion, sending AJAX to:', deleteUrl);
                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        console.log('Delete successful:', response);
                        toastr.success('Xóa học sinh thành công!');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        console.error('Delete failed:', xhr.responseText);
                        toastr.error('Lỗi khi xóa học sinh: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            } else {
                console.log('User cancelled deletion');
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
        });
    });

    // Toggle status (Hoạt động)
    $('.display-homepage-toggle').on('change', function() {
        const studentId = $(this).data('student-id');
        const isActive = $(this).is(':checked') ? 1 : 0;
        console.log('Toggle status for student ID:', studentId, 'New status:', isActive);

        $.ajax({
            url: '/admin/student/toggle-status',
            method: 'POST',
            data: {
                student_id: studentId,
                status: isActive,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('Cập nhật trạng thái thành công');
            },
            error: function(xhr) {
                toastr.error('Lỗi khi cập nhật trạng thái');
                // Rollback checkbox if failed
                $(this).prop('checked', !isActive);
            }
        });
    });

    // Auto-submit form when filter changes
    $('select[name="gender"], select[name="per_page"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

@endpush