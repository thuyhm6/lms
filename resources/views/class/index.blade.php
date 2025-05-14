@extends('layouts.admin')
@push('styles')
    <style>
        .form-label.required::after {
            content: " (*)";
            color: red;
        }

        /*  */
        #filterForm {
            margin-bottom: 20px;
        }

        #filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        #filter-container .filter-item {
            display: flex;
            flex-direction: column;
            min-width: 160px;
        }

        #filter-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        #btn-search,
        #btn-reset {
            height: 38px;
            padding: 0 15px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }

        #btn-search {
            background-color: #3490dc;
            color: white;
        }

        #btn-reset {
            background-color: #e3342f;
            color: white;
        }

        #filterForm select,
        #filterForm input[type="text"] {
            height: 38px;
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
@endpush
@section('title', 'Lớp học')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Lớp học</h3>
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

            <div class="wg-box mb-10">
                <div id="schedule-filter" class="flex-grow">
                    <form id="filterForm" method="GET" action="{{ route('class.index') }}">
                        <div id="filter-container">
                            <div class="filter-item">
                                <label for="keyword">Tìm kiếm</label>
                                <input type="text" id="keyword" placeholder="Mã lớp, tên lớp..." name="keyword"
                                    value="{{ request('keyword') }}">
                            </div>
                            <div class="filter-item">
                                <label for="truong_tim_kiem">Trường tìm kiếm</label>
                                <select id="truong_tim_kiem" name="truong_tim_kiem">
                                    <option value="">Chọn trường</option>
                                    @foreach ($searchFields as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request('truong_tim_kiem') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="giao_vien">Giáo viên</label>
                                <select id="giao_vien" name="giao_vien">
                                    <option value="">Tất cả</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->user_id }}"
                                            {{ request('giao_vien') == $teacher->user_id ? 'selected' : '' }}>
                                            {{ $teacher->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai">
                                    <option value="" disabled selected>Tất cả</option>
                                    <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Đang học
                                    </option>
                                    <option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Kết thúc
                                    </option>
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="hinh_thuc">Hình thức học</label>
                                <select id="hinh_thuc" name="hinh_thuc">
                                    <option value="">Tất cả</option>
                                    @foreach ($learningFormats as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ request('hinh_thuc') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filter-item">
                                <label for="per_page">Số dòng</label>
                                <select id="per_page" name="per_page">
                                    @foreach ([4, 10, 25, 50] as $option)
                                        <option value="{{ $option }}"
                                            {{ request('per_page', 4) == $option ? 'selected' : '' }}>
                                            {{ $option }} dòng
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="filter-buttons">
                                <button type="submit" id="btn-search" title="Tìm kiếm">
                                    <i class="icon-search"></i>
                                </button>
                                <a href="{{ route('class.index') }}" id="btn-reset" title="Xóa bộ lọc">
                                    Xóa lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="wg-box">
                <div class="d-flex justify-content-between mg-3">
                    <h3>Class List</h3>
                    <a href="{{ route('class.create') }}"><button class="btn btn-success btn-lg">+ Add</button></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                {{-- <th scope="col" style="width: 5%;"><input type="checkbox" class="form-check-input"></th> --}}
                                <th scope="col" style="width: 5%;"></th>
                                <th scope="col" style="width: 5%;">Sắp xếp</th>
                                <th scope="col" style="width: 5%;">Mã lớp</th>
                                <th scope="col" style="width: 10%;">Tên lớp</th>
                                <th scope="col">Lịch học</th>
                                <th scope="col" style="width: 10%;">Giáo viên</th>
                                <th scope="col" style="width: 7%;">Nhân viên</th>
                                <th scope="col" style="width: 5%;">Hình thức học</th>
                                <th scope="col" style="width: 7%;">Mô tả</th>
                                <th scope="col" style="width: 10%;">Số học sinh</th>
                                {{-- <th scope="col">Số buổi giảng viên đã điểm danh</th> --}}
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Ngày tạo lớp học</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classes as $class)
                                <tr>
                                    {{-- {{ dd($class) }} --}}
                                    {{-- <td>{{ $class->sap_xep }}</td> --}}
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                ⚙️
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('class.students', ['id' => $class->sap_xep]) }}">📋
                                                        Danh sách học sinh</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('class.edit', ['id' => $class->sap_xep]) }}">✏️
                                                        Sửa</a></li>
                                                <li><a class="dropdown-item" href="#">📊 Xem báo cáo kết quả học
                                                        tập</a></li>
                                                <li>
                                                    <a href="#" class="dropdown-item text-danger"
                                                        onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa lớp này?')) document.getElementById('delete-form-{{ $class->sap_xep }}').submit();">
                                                        🗑️ Xóa
                                                    </a>
                                                    <form id="delete-form-{{ $class->sap_xep }}"
                                                        action="{{ route('class.destroy', ['id' => $class->sap_xep]) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>

                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $class->sap_xep }}</td>
                                    <td>{{ $class->ma_lop }}</td>
                                    <td>{{ $class->ten_lop }}</td>
                                    <td>
                                        <span class="btn btn-sm btn-info text-white">{{ $class->lich_hoc }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="btn btn-sm btn-info text-white">{{ $class->giao_vien_phu_trach_chinh }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="btn btn-sm btn-primary text-white">{{ $class->nhan_vien }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($class->hinh_thuc === 'offline')
                                            <span class="btn btn-sm btn-danger">Offline</span>
                                        @elseif ($class->hinh_thuc === 'online')
                                            <span class="btn btn-sm btn-success">Online</span>
                                        @elseif ($class->hinh_thuc === 'Hybrid')
                                            <span class="btn btn-sm btn-warning text-white">Hybrid</span>
                                        @else
                                            <span
                                                class="btn btn-sm btn-secondary">{{ $class->hinh_thuc ?? 'Không rõ' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $class->mo_ta }}</td>
                                    {{-- <td>{{ $class->so_buoi_hoc ?? 0 }}</td> --}}
                                    <td class="text-center">{{ $class->so_hoc_sinh ?? 'Chưa có' }}</td>
                                    {{-- <td>
                                        <span class="badge bg-success">{{ $class->so_buoi_diem_danh ?? 0 }}</span>
                                        <span class="badge bg-warning text-dark">{{ $class->so_buoi_hoc ?? 0 }}</span>
                                    </td> --}}
                                    {{-- <td>{{ $class->trang_thai_lop_hoc ? 'Kết thúc' : 'Chưa kết thúc' }}</td> --}}
                                    <td class="text-center">
                                        @if ($class->trang_thai_lop_hoc === 0)
                                            <span class="btn btn-sm btn-warning text-white">Kết thúc</span>
                                        @elseif ($class->trang_thai_lop_hoc === 1)
                                            <span class="btn btn-sm btn-success">Đang học</span>
                                        @else
                                            <span class="btn btn-sm btn-secondary">Không rõ</span>
                                        @endif
                                        <br>
                                        <span class="btn btn-sm btn-info mt-1">{{ $class->active_days }} ngày
                                            active</span>
                                    </td>
                                    {{-- <td>{{ $class->ngay_tao_lop_hoc->format('d/m/Y') }}</td> --}}
                                    <td class="text-center">
                                        {{ $class->ngay_tao_lop_hoc ? date('d/m/Y', strtotime($class->ngay_tao_lop_hoc)) : '' }}
                                    </td>
                                </tr>
                            @endforeach
                            

                        </tbody>
                    </table>
                </div>

                {{-- <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $coupons->links('pagination::bootstrap-5') }}
                </div> --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Filter scripts
$(document).ready(function() {
    // Submit form via AJAX
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        performAjaxSearch();
    });

    // Auto-submit when select fields change
    $('#truong_tim_kiem, #giao_vien, #trang_thai, #hinh_thuc, #per_page').on('change', function() {
        performAjaxSearch();
    });

    // Handle filter reset button
    $('#btn-reset').on('click', function(e) {
        e.preventDefault();
        // Reset all form fields
        $('#keyword').val('');
        $('#truong_tim_kiem').val('');
        $('#giao_vien').val('');
        $('#trang_thai').val('');
        $('#hinh_thuc').val('');
        $('#per_page').val('4');

        // Perform search with reset data
        performAjaxSearch();
    });

    // AJAX search function
    function performAjaxSearch() {
        const data = $('#filterForm').serialize();
        console.log('Filter Params:', data);

        $.ajax({
            url: $('#filterForm').attr('action'),
            type: 'GET',
            data: data,
            dataType: 'html', // Changed to HTML instead of JSON
            beforeSend: function() {
                // Add loading indicator to table body
                $('table.table tbody').html(
                    '<tr><td colspan="13" class="text-center">Đang tải...</td></tr>');
            },
            success: function(response) {
                // Parse the HTML response
                const $response = $(response);
                
                // Replace the table content
                const newTableBody = $response.find('table.table tbody').html();
                $('table.table tbody').html(newTableBody);
                
                // Update pagination if it exists
                const newPagination = $response.find('.wgp-pagination').html();
                if (newPagination) {
                    $('.wgp-pagination').html(newPagination);
                }

                // Update URL without reloading
                const url = new URL(window.location);
                const params = new URLSearchParams(data);

                // Remove old query parameters
                [...url.searchParams.keys()].forEach(key => {
                    url.searchParams.delete(key);
                });

                // Add new query parameters
                params.forEach((value, key) => {
                    if (value) { // Only add parameters with values
                        url.searchParams.append(key, value);
                    }
                });

                window.history.pushState({}, '', url);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status,
                    error,
                    response: xhr.responseText
                });
                $('table.table tbody').html('<tr><td colspan="13" class="text-center">Lỗi khi tải dữ liệu.</td></tr>');
            }
        });
    }

    // Handle pagination clicks with AJAX
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        $('#filterForm').append('<input type="hidden" name="page" value="' + page + '">');
        performAjaxSearch();
        $('#filterForm').find('input[name="page"]').remove();
    });
});
    </script>
@endpush
