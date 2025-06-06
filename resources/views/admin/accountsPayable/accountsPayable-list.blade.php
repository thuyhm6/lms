@extends('layouts.admin')
@section('content')
    <style>
        input[type=checkbox][disabled]:checked {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
            position: relative;
        }

        input[type=checkbox][disabled]:checked::after {
            content: '✔';
            color: white;
            position: absolute;
            top: 3px;
            left: 4px;
            font-size: 14px;
        }

        .form-select,
        .form-control,
        .btn {
            height: 40px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 12px;
        }

        .table-responsive {
            overflow: visible !important;
        }

        .dropdown-menu {
            position: absolute;
            z-index: 1050;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        .table-custom td {
            font-size: 1.2rem;
        }

        .table-custom th {
            font-size: 1.2rem;
        }

        .table-custom th,
        .table-custom td {
            width: auto;
            white-space: nowrap;
            text-align: left;
        }

        .table-custom {
            width: 100%;
            table-layout: auto;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 700px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .close {
            font-size: 1.5rem;
            font-weight: 400;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close:hover {
            color: #333;
        }

        .modal-body {
            padding: 0 10px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-table {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
        }

        .table-borderless th,
        .table-borderless td {
            padding: 8px 12px;
            font-size: 1rem;
            vertical-align: middle;
        }

        .table-borderless th {
            width: 30%;
            font-weight: 500;
            color: #555;
        }

        .table-borderless td {
            color: #333;
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-cancel {
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 0.95rem;
            background-color: #6c757d;
            color: #fff;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .modal-content {
                width: 95%;
                padding: 15px;
            }

            .table-borderless th,
            .table-borderless td {
                font-size: 0.85rem;
                padding: 6px 8px;
            }

            .section-title {
                font-size: 1rem;
            }
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20">
                <h3>Công nợ</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Công nợ</div>
                    </li>
                </ul>
            </div>
            {{-- Bộ lọc tìm kiếm --}}
            <div class="wg-box my-1 filter" style="width: 100%;">
                <form id="searchForm" class="d-flex align-items-end flex-nowrap gap-2"
                    action="{{ route('admin.transactions.filter') }}" style="width: 100%;">

                    {{-- Gói học --}}
                    <div style="flex: 1 1 0; min-width: 130px;">
                        <label for="course_package_id" class="form-label">Gói học</label>
                        <select name="course_package_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($coursePackages as $package)
                                <option value="{{ $package->id }}">{{ $package->package_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- công nợ : còn nợ --}}
                    <input type="hidden" name="debt" value="1">


                    {{-- Ngày giao dịch --}}
                    <div style="flex: 1 1 0; min-width: 150px;">
                        <label for="date_from" class="form-label">Ngày giao dịch</label>
                        <input type="date" name="date_from" class="form-control">
                    </div>
                    {{-- Từ khóa: tên hoặc SĐT --}}
                    <div style="flex: 1 1 0; min-width: 150px;">
                        <label for="keyword" class="form-label">Từ khóa</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Tên học viên hoặc SĐT">
                    </div>


                    <div class="d-flex justify-content-end align-items-center" style="flex: 0 0 180px; gap: 8px;">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <button type="reset" class="btn btn-danger" onclick="resetFilter()">Xóa</button>
                    </div>

                    <input type="hidden" name="limit" id="limit" value="10">
                </form>
            </div>



            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <h4 class="text fs-4"><i class="icon-menu"></i> Danh sách công nợ</h4>

                </div>

                <div class="table-responsive">
                    @if (Session::has('success'))
                        <p class="alert alert-success">{{ Session::get('success') }}</p>
                    @endif

                    <table class="table table-hover align-middle table-custom">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>Học sinh</th>
                                <th>Khóa đăng ký</th>
                                <th>Số buổi</th>
                                <th>Giá tiền</th>
                                <th>Buổi khuyến mãi</th>
                                <th>Học bổng</th>
                                <th>Tổng số buổi</th>
                                <th>Tổng tiền</th>
                                <th>Đã thanh toán</th>
                                <th>Còn nợ</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="body-transactions">
                            @foreach ($transactions as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaction->student->full_name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->coursePackage->package_name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->coursePackage->number_of_sessions ?? 0 }}</td>
                                    <td>{{ number_format($transaction->coursePackage->price ?? 0, 0, ',', '.') }} đ</td>
                                    <td>{{ $transaction->promo_sessions ?? 0 }}</td>
                                    <td>{{ number_format($transaction->scholarship_amount ?? 0, 0, ',', '.') }} đ</td>
                                    <td>
                                        {{ ($transaction->coursePackage->number_of_sessions ?? 0) + ($transaction->promo_sessions ?? 0) }}
                                    </td>
                                    <td>{{ number_format(($transaction->coursePackage->price ?? 0) - ($transaction->scholarship_amount ?? 0), 0, ',', '.') }}
                                        đ</td>
                                    <td>{{ number_format($transaction->amount_paid ?? 0, 0, ',', '.') }} đ</td>
                                    <td>{{ number_format($transaction->debt ?? 0, 0, ',', '.') }} đ</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <div class="border-0" type="button" data-bs-toggle="dropdown">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                                            <ul class="dropdown-menu">


                                                <li class="py-1">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="{{ route('admin.transactions.detail', ['id' => $transaction->id]) }}">
                                                        <i class="icon-edit text-primary"></i> Chi tiết
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="divider"></div>

                    <div class="row align-items-center justify-content-between my-3">
                        <div class="col-auto" id="paginationWrapper">
                            {{ $transactions->links('pagination::bootstrap-5') }}
                        </div>
                        <div class="col-auto">
                            <label for="limit2" class="me-2 mb-0">Số dòng hiển thị:</label>
                            <select name="limit" id="limit2" class="form-select form-select-sm">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var selectedForm = $(this).closest('form');
            swal({
                title: "Bạn có chắc không?",
                text: "Bạn muốn xóa giao dịch này?",
                type: "warning",
                buttons: ["Không", "Có"],
                confirmButonColor: "#dc3545"
            }).then(function(result) {
                if (result) selectedForm.submit();
            });
        });


        // Định nghĩa hàm assetPath (nếu cần cho ảnh)
        function assetPath(path) {
            return `${window.location.origin}/${path}`;
        }



        // Format currency to Vietnamese style (e.g., 1.000.000)
        function formatCurrency(number) {
            return number.toLocaleString('vi-VN');
        }

        // Format date to DD/MM/YYYY HH:MM
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }


        // Khi thay đổi limit ở formLimit
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });

        //Hàm xử lý bộ lọc
        $('#searchForm').on('submit', function(e) {
            e.preventDefault(); // Ngăn tải lại trang

            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    // console.log(response.pagination);
                    $('#body-transactions').html(renderTransactions(response.transactions.data));
                    $('#paginationWrapper').html(response.pagination);

                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });


        // Hàm render cập nhật body
        function renderTransactions(data) {
            if (data.length === 0) {
                return '<tr><td colspan="12" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }

            let html = '';
            data.forEach((item, index) => {
                const totalSessions = (item.course_package?.number_of_sessions || 0) + (item.promo_sessions || 0);
                const price = item.course_package?.price || 0;
                const scholarship = item.scholarship_amount || 0;
                const finalAmount = price - scholarship;

                html += `
            <tr>
                <td>${index + 1}</td>
                <td>${item.student?.full_name || 'N/A'}</td>
                <td>${item.course_package?.package_name || 'N/A'}</td>
                <td>${item.course_package?.number_of_sessions || 0}</td>
                <td>${numberFormat(price)} đ</td>
                <td>${item.promo_sessions || 0}</td>
                <td>${numberFormat(scholarship)} đ</td>
                <td>${totalSessions}</td>
                <td>${numberFormat(finalAmount)} đ</td>
                <td>${numberFormat(item.amount_paid || 0)} đ</td>
                <td>${numberFormat(item.debt || 0)} đ</td>
                <td class="text-center">
                    <div class="dropdown dropstart">
                        <div class="border-0" type="button" data-bs-toggle="dropdown">
                            <i class="icon-settings fs-4"></i>
                        </div>
                        <ul class="dropdown-menu">
                             <li class="py-1">
                                <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                    href="/admin/transactions/${item.id}/detail">
                                    <i class="icon-edit text-primary"></i> Chi tiết
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        `;
            });

            return html;
        }

        // Hàm định dạng số (dấu chấm hàng nghìn)
        function numberFormat(number) {
            return new Intl.NumberFormat('vi-VN').format(number || 0);
        }


        // Hàm định dạng thời gian
        function formatDateTime(dateTime) {
            const date = new Date(dateTime);

            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Tháng bắt đầu từ 0
            const year = date.getFullYear();

            return `${hours}:${minutes} ${day}/${month}/${year}`;
        }



        // Xử lý sự kiện phân trang
        $(document).on('click', '#paginationWrapper a', function(e) {
            e.preventDefault(); // Ngăn trang tải lại

            const url = $(this).attr('href'); // Lấy URL phân trang (bao gồm các tham số lọc)

            $.ajax({
                url: url, // Gọi URL phân trang
                type: 'GET',
                success: function(response) {
                    // Cập nhật kết quả bài học và phân trang
                    console.log(response.transactions.data);
                    $('#body-transactions').html(renderTransactions(response.transactions.data));
                    $('#paginationWrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });


        // Định nghĩa hàm assetPath
        function assetPath(path) {
            return `${window.location.origin}/${path}`;
        }
    </script>
@endpush
