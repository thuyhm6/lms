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




        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/fonts/DejaVuSans.ttf') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #000;
            border-radius: 5px;
        }


        .center {
            text-align: center;
            font-size: 12px;
        }

        .info {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #000;
            border-radius: 5px;
        }

        .info p {
            margin: 3px 0;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        .totals {
            margin-top: 15px;
        }

        .totals td {
            border: none;
            padding: 5px;
        }

        .thank-you {
            margin-top: 20px;
            font-style: italic;
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .right {
            text-align: right;
        }




        /* Modal container */
        .custom-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            /* Tăng độ tối của nền */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Modal content */
        .custom-modal-content {
            background-color: #fff;
            width: 90%;
            /* Tăng kích thước modal */
            max-width: 900px;
            /* Giới hạn chiều rộng tối đa */
            border-radius: 12px;
            /* Bo góc mềm mại hơn */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            /* Tăng bóng đổ */
            overflow: hidden;
            animation: slideDown 0.3s ease-out;
            max-height: 90%;
            /* Đảm bảo modal không vượt quá chiều cao màn hình */
            display: flex;
            flex-direction: column;
        }

        /* Modal header */
        .custom-modal-header {
            padding: 20px;
            background-color: #f3f3f3;
            /* Màu xanh đậm */
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
            font-weight: bold;
        }

        /* Modal close button */
        .custom-modal-close {
            font-size: 24px;
            cursor: pointer;
            color: #ff0000;
            transition: color 0.2s;
        }

        .custom-modal-close:hover {
            color: #ddd;
        }

        /* Modal body */
        .custom-modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            /* Để body chiếm không gian còn lại */
        }

        /* Modal footer */
        .custom-modal-footer {
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Buttons */
        .custom-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .custom-btn-cancel {
            background-color: #dc3545;
            color: #fff;
            transition: background-color 0.2s;
        }

        .custom-btn-cancel:hover {
            background-color: #c82333;
        }

        .custom-btn-submit {
            background-color: #28a745;
            color: #fff;
            transition: background-color 0.2s;
        }

        .custom-btn-submit:hover {
            background-color: #218838;
        }

        /* Invoice box */
        .invoice-box {
            max-width: 850px;
            /* Tăng chiều rộng hóa đơn */
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            /* Tăng kích thước chữ */
            line-height: 1.6;
            color: #333;
        }

        /* Invoice header */
        .invoice-box h1 {
            text-align: center;
            font-size: 24px;
            /* Giảm kích thước tiêu đề */
            margin: 40px 0 !important;
            /* Giảm khoảng cách trên và dưới */
            color: #000000;
            line-height: 0;

        }

        .invoice-box p {
            font-size: 16px;
            color: black
        }

        /* Invoice info */
        .invoice-box .info {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .invoice-box .info p {
            margin: 5px 0;
            font-size: 16px;
        }

        /* Section title */
        .invoice-box .section-title {
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #555555;
            padding-bottom: 5px;
            color: #000000;
        }

        /* Table */
        .invoice-box table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .invoice-box th,
        .invoice-box td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        .invoice-box th {
            background-color: #ffffff;
            color: #000000;
            font-weight: bold;
        }

        .invoice-box .totals td {
            border: none;
            padding: 8px;
            font-size: 16px;
        }

        .invoice-box .thank-you {
            margin-top: 30px;
            font-style: italic;
            text-align: center;
            font-size: 18px;
            color: #555;
        }

        /* Animation */
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
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20">
                <h3>Giao dịch</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Giao dịch</div>
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

                    {{-- ID hoặc mã giao dịch --}}
                    {{-- <div style="flex: 1 1 0; min-width: 150px;">
                        <label for="transaction_code" class="form-label">ID hoặc Mã giao dịch</label>
                        <input type="text" name="id" class="form-control"
                            placeholder="Nhập ID hoặc mã giao dịch">
                    </div> --}}

                    {{-- Công nợ --}}
                    <div style="flex: 1 1 0; min-width: 130px;">
                        <label for="debt" class="form-label">Công nợ</label>
                        <select name="debt" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1">Còn nợ</option>
                            <option value="0">Đã thanh toán đủ</option>
                        </select>
                    </div>

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
                    <h4 class="text fs-4"><i class="icon-menu"></i> Danh sách giao dịch</h4>
                    <a class="tf-button style-1 w208" href="{{ route('admin.transactions.create') }}"><i
                            class="icon-plus"></i>Thêm mới</a>
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
                                                @if ($transaction->transactionDetails < 2)
                                                    <li class="py-1">
                                                        <button class="dropdown-item d-flex align-items-center gap-2 btn" data-transaction-id="{{ $transaction->id }}"
                                                            onclick="openCustomModal({{ $transaction->id }})">
                                                            <i class="icon-printer text-primary"></i> Xem hóa đơn
                                                        </button>
                                                    </li>
                                                @endif

                                                <li class="py-1">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="{{ route('admin.transactions.edit', ['id' => $transaction->id]) }}">
                                                        <i class="icon-edit text-primary"></i> Sửa
                                                    </a>
                                                </li>
                                                <li class="py-1">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="{{ route('admin.transactions.detail', ['id' => $transaction->id]) }}">
                                                        <i class="icon-eye text-primary"></i> Chi tiết
                                                    </a>
                                                </li>
                                                <li class="py-1">
                                                    <form
                                                        action="{{ route('admin.transactions.delete', ['id' => $transaction->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn delete dropdown-item d-flex align-items-center gap-2 text-danger">
                                                            <i class="icon-trash-2"></i> Xóa
                                                        </button>
                                                    </form>
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
                                <option value="100">100
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- Modal hiển thị hóa đơn -->
    <div id="customModal" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Hóa đơn thanh toán</h5>
                <span class="custom-modal-close" onclick="closeCustomModal()">×</span>
            </div>
            <div class="custom-modal-body">
                <div id="modalInvoiceContent">
                    <!-- Nội dung hóa đơn sẽ được load ở đây -->
                </div>
            </div>
            <div class="custom-modal-footer">
                <button class="custom-btn custom-btn-cancel" onclick="closeCustomModal()">Đóng</button>
                <button id="downloadInvoice" class="custom-btn custom-btn-submit" data-transaction-id="">Xuất hóa đơn</button>
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
                            ${item.transactionDetails && item.transactionDetails < 2 ? `
                                    <li class="py-1">
                                        <button class="dropdown-item d-flex align-items-center gap-2 btn btn-view-invoice"
                                            data-id="${item.id}">
                                            <i class="icon-printer text-primary"></i> Xem hóa đơn
                                        </button>
                                    </li>
                                ` : ''}

                            <li class="py-1">
                                <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                    href="/admin/transactions/${item.id}/detail">
                                    <i class="icon-eye text-primary"></i> Chi tiết
                                </a>
                            </li>

                            <li class="py-1">
                                <a class="dropdown-item d-flex align-items-center gap-2 btn" href="/admin/transactions/${item.id}/edit">
                                    <i class="icon-edit text-primary"></i> Sửa
                                </a>
                            </li>
                            <li class="py-1">
                                <form action="/admin/transactions/${item.id}/delete" method="POST">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn delete dropdown-item d-flex align-items-center gap-2 text-danger">
                                        <i class="icon-trash-2"></i> Xóa
                                    </button>
                                </form>
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



        // Mở modal
        function openCustomModal(transactionId) {
            $('#downloadInvoice').data('transaction-id', transactionId);

            // Gọi API để lấy nội dung hóa đơn
            fetch(`/admin/invoice/${transactionId}/show`)
                .then(response => response.json())
                .then(data => {
                    const transaction = data.transaction;
                    const studentParent = data.studentParent;

                    // Render nội dung hóa đơn
                    const invoiceHtml = `
                        <div class="invoice-box">
                            <h1 style="margin: 10px 0; padding: 0;">HÓA ĐƠN THANH TOÁN</h1>
                            <p class="center">Trung tâm 8-BIT</p>

                            <div class="info">
                                <p><strong>Mã hóa đơn:</strong> HD${transaction.id}</p>
                                <p><strong>Ngày lập:</strong> ${new Date(transaction.created_at).toLocaleDateString('vi-VN')}</p>
                                <p><strong>Người lập:</strong> Quản trị viên</p>
                                <p><strong>Chi nhánh:</strong> CS1 - Thanh Hóa</p>
                            </div>

                            <div class="section-title">Thông tin học sinh</div>
                            <p><strong>Họ tên:</strong> ${studentParent?.student_name || 'N/A'}</p>
                            <p><strong>Email:</strong> ${studentParent?.student_email || 'N/A'}</p>
                            <p><strong>Số điện thoại:</strong> ${studentParent?.student_phone || 'N/A'}</p>
                            <p><strong>Trường:</strong> ${studentParent?.student_school || 'N/A'}</p>
                            <p><strong>Lớp:</strong> ${studentParent?.student_grade || 'N/A'}</p>

                            <div class="section-title">Thông tin phụ huynh</div>
                            <p><strong>Họ tên:</strong> ${studentParent?.parent_name || 'N/A'}</p>
                            <p><strong>Email:</strong> ${studentParent?.parent_email || 'N/A'}</p>
                            <p><strong>Số điện thoại:</strong> ${studentParent?.parent_phone || 'N/A'}</p>

                            <div class="section-title">Chi tiết thanh toán</div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tên khóa học</th>
                                        <th>Số buổi</th>
                                        <th>Học phí</th>
                                        <th>Đã thanh toán</th>
                                        <th>Công nợ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${transaction.package_name || 'N/A'}</td>
                                        <td>${transaction.number_of_sessions || 0}</td>
                                        <td>${Math.round(transaction.price || 0).toLocaleString('vi-VN')} VNĐ</td>
                                        <td>${Math.round(transaction.amount_paid || 0).toLocaleString('vi-VN')} VNĐ</td>
                                        <td>${Math.round((transaction.debt || 0)).toLocaleString('vi-VN')} VNĐ</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="thank-you">
                                <p>Xin cảm ơn quý phụ huynh đã đăng ký cho học sinh tham gia khóa học tại trung tâm.</p>
                            </div>
                        </div>
                    `;

                    document.getElementById('modalInvoiceContent').innerHTML = invoiceHtml;

                    // Hiển thị modal
                    document.getElementById('customModal').style.display = 'flex';
                })
                .catch(error => console.error('Lỗi khi tải hóa đơn:', error));
        }

        //Hàm in hóa đơn
        function printInvoice(transactionId) {
            if (transactionId) {
                // Chuyển hướng đến đường dẫn in hóa đơn
                window.location.href = `/admin/invoice/${transactionId}/print`;
            } else {
                console.error('Không tìm thấy transactionId để in hóa đơn.');
            }
        }

        // Đóng modal
        function closeCustomModal() {
            document.getElementById('customModal').style.display = 'none';
        }

        //In hóa đơn
        $('#downloadInvoice').click(function() {
            const transactionId = $(this).data('transaction-id'); // Lấy transactionId từ thuộc tính data
            printInvoice(transactionId); // Gọi hàm in hóa đơn
        });

        //Hiển thị hóa đơn
        $(document).on('click', '.btn-view-invoice', function() {
            const transactionId = $(this).data('id'); // Lấy ID giao dịch từ thuộc tính data-id
            openCustomModal(transactionId); // Gọi hàm mở modal
        });
    </script>
@endpush
