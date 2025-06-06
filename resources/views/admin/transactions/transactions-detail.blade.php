@extends('layouts.admin')
@section('content')
    <style>
        .total-amount,
        .total-debt {
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .total-amount span,
        .total-debt span {
            color: #d9534f;
            /* màu đỏ nhẹ cho nổi bật */
            font-style: italic;
        }






        /* MODAL */

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
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <div class="d-flex items-center gap20">
                <h3>Chi Tiết giao dịch</h3>
                <div class="mt-4">
                    <a class="tf-button style-1 w208" href="{{ route('admin.transactions') }}">
                        <i class="icon-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Thêm giao dịch</div>
                </li>
            </ul>
        </div>
        <div class="main-content-wrap">

            {{-- Thông tin học sinh --}}
            <div class="bg-white rounded p-3 mb-4">
                <h5>Thông tin học sinh</h5>
                <table class="table table-bordered table-custom">
                    <tbody>
                        <tr>
                            <th>Họ tên học sinh</th>
                            <td>{{ $StudentParent[0]->student_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $StudentParent[0]->student_email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại</th>
                            <td>{{ $StudentParent[0]->student_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Trường</th>
                            <td>{{ $StudentParent[0]->student_school ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Lớp</th>
                            <td>{{ $StudentParent[0]->student_grade ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Thông tin phụ huynh --}}
            <div class="bg-white rounded p-3 mb-4">
                <h5>Thông tin phụ huynh</h5>
                <table class="table table-bordered table-custom">
                    <tbody>
                        <tr>
                            <th>Họ tên phụ huynh</th>
                            <td>{{ $StudentParent[0]->parent_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Số điện thoại</th>
                            <td>{{ $StudentParent[0]->parent_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $StudentParent[0]->parent_email ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Thông tin giao dịch chính --}}
            <div class="bg-white rounded p-3 mb-4">
                <h5>Thông tin giao dịch</h5>
                <table class="table table-bordered table-custom">
                    <thead>
                        <tr>
                            <th>Khóa học</th>
                            <th>Số buổi</th>
                            <th>Giá</th>
                            <th>Buổi Khuyến mãi</th>
                            <th>Học bổng</th>
                            <th>Tổng số buổi</th>
                            <th>Tổng tiền</th>
                            <th>Đã thanh toán</th>
                            <th>Công nợ</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $transaction->coursePackage->package_name ?? 'N/A' }}</td>
                            <td>{{ $transaction->coursePackage->number_of_sessions }}</td>
                            <td>{{ number_format($transaction->coursePackage->price, 0, ',', '.') }} đ</td>
                            <td>{{ $transaction->promo_sessions }}</td>
                            <td>{{ number_format($transaction->scholarship_amount, 0, ',', '.') }}
                                đ</td>
                            <td>{{ $transaction->coursePackage->number_of_sessions + $transaction->promo_sessions }}</td>
                            <td>{{ number_format(($transaction->coursePackage->price ?? 0) - ($transaction->scholarship_amount ?? 0), 0, ',', '.') }}
                                đ
                            </td>
                            <td>{{ number_format($transaction->amount_paid ?? 0, 0, ',', '.') }} đ</td>


                            <td>{{ number_format($transaction->debt ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $transaction->note }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Chi tiết giao dịch --}}
            <div class="bg-white rounded p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Chi tiết giao dịch</h5>

                    @if ($transaction->debt > 0)
                        <button type="button" class="btn btn-success mt-2 p-3" data-bs-toggle="collapse"
                            data-bs-target="#form-add-detail">+ Thêm chi tiết giao dịch</button>
                    @endif
                </div>
                <table class="table table-bordered table-custom">
                    @if (Session::has('success'))
                        <p class="alert alert-success">{{ Session::get('success') }}</p>
                    @endif
                    <thead>
                        <tr>
                            <th>Ngày thanh toán</th>
                            <th>Số tiền</th>
                            <th>Ghi chú</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->transactionDetails as $detail)
                            <tr>
                                <td>{{ $detail->created_at }}</td>
                                <td>{{ number_format($detail->amount_paid, 0, ',', '.') }} đ</td>
                                <td>{{ $detail->note }}</td>
                                <td class="text-center">
                                    <button
                                        class="btn btn-primary btn-download-invoice d-flex align-items-center gap-2 btn-view-invoice"
                                        data-transaction-id="{{ $transaction->id }}"
                                        data-transaction-detail-id="{{ $detail->id }}">
                                        <i class="icon-printer"></i> Xuất hóa đơn
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


                <div class="collapse mt-3" id="form-add-detail">
                    <form method="POST" action="{{ route('admin.transactions.detail.store', $transaction->id) }}">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                        <table class="table align-middle table-custom">
                            <thead>
                                <tr>
                                    <th>Số tiền</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="amount_paid" class="form-control amount" required></td>

                                    <td><input type="text" name="note" class="form-control"></td>


                                </tr>
                            </tbody>
                        </table>

                        <!-- Total Amount -->
                        <div class="total-amount">
                            Tổng số tiền: <span id="total-amount">0</span> Đồng
                            (<span id="total-amount-text">Không đồng</span>)
                        </div>

                        <!-- Total Debt -->
                        <div class="total-debt">
                            Công nợ: <span id="total-debt">0</span> Đồng
                            (<span id="total-debt-text">Không đồng</span>)
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary p-3">Lưu giao dịch</button>
                        </div>
                    </form>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Đã có lỗi xảy ra:</strong>
                        <ul class="mb-2">
                            @foreach ($errors->all() as $error)
                                <li class="my-2">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                    <button id="downloadInvoice" class="custom-btn custom-btn-submit" data-transaction-id="" data-transaction-detail-id="">Xuất hóa
                        đơn</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Chuyển đổi số thành văn bản tiếng Việt
        function numberToVietnameseText(number) {
            const ChuSo = ["không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín"];

            function docBaSo(num) {
                let tram = Math.floor(num / 100);
                let chuc = Math.floor((num % 100) / 10);
                let donvi = num % 10;
                let result = "";

                if (tram > 0) {
                    result += ChuSo[tram] + " trăm";
                    if (chuc === 0 && donvi > 0) result += " linh";
                }

                if (chuc > 0) {
                    if (chuc === 1) {
                        result += " mười";
                    } else {
                        result += " " + ChuSo[chuc] + " mươi";
                    }
                }

                if (donvi > 0) {
                    if (chuc > 1 && donvi === 1) {
                        result += " mốt";
                    } else if (donvi === 5 && chuc !== 0) {
                        result += " lăm";
                    } else {
                        result += " " + ChuSo[donvi];
                    }
                }

                return result.trim();
            }

            if (number === 0) return "Không đồng";

            let result = "";
            const unit = ["", " nghìn", " triệu", " tỷ"];
            let i = 0;

            while (number > 0) {
                let temp = number % 1000;
                if (temp !== 0) {
                    let prefix = docBaSo(temp);
                    result = prefix + unit[i] + (result ? " " + result : "");
                }
                number = Math.floor(number / 1000);
                i++;
            }

            return result.charAt(0).toUpperCase() + result.slice(1) + " đồng";
        }

        // Tính lại tổng tiền sau khi thêm giao dịch mới
        $(document).ready(function() {
            $('.amount').on('input', function() {
                let newAmount = 0;
                $('.amount').each(function() {
                    const val = parseInt($(this).val().replace(/[^\d]/g, '')) || 0;
                    newAmount += val;
                });

                const total =
                    {{ ($transaction->coursePackage->price ?? 0) - ($transaction->scholarship_amount ?? 0) - ($transaction->amount_paid ?? 0) }};
                const debt = Math.max(total - newAmount, 0);

                // Cập nhật tổng số tiền đã nhập
                if ($('#total-amount').length) {
                    $('#total-amount').text(newAmount.toLocaleString('vi-VN'));
                }
                if ($('#total-amount-text').length) {
                    $('#total-amount-text').text(numberToVietnameseText(newAmount));
                }

                // Cập nhật công nợ còn lại
                if ($('#total-debt').length) {
                    $('#total-debt').text(debt.toLocaleString('vi-VN'));
                }
                if ($('#total-debt-text').length) {
                    $('#total-debt-text').text(numberToVietnameseText(debt));
                }
            });

            // Gọi 1 lần khi load để hiển thị ban đầu
            $('.amount').trigger('input');
        });









        // Mở modal
        function openCustomModal(transactionId, transactionIdDetail) {
            $('#downloadInvoice').data('transaction-id', transactionId);
            $('#downloadInvoice').data('transaction-detail-id', transactionIdDetail);

            // Gọi API để lấy nội dung hóa đơn
            fetch(`/admin/invoice/${transactionId}/showDetail/${transactionIdDetail}`)
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
                                        <th>Tổng đã thanh toán</th>
                                        <th>Công nợ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>${transaction.package_name || 'N/A'}</td>
                                        <td>${transaction.number_of_sessions || 0}</td>
                                        <td>${Math.round(transaction.price || 0).toLocaleString('vi-VN')} VNĐ</td>
                                        <td>${Math.round(transaction.amount_paid_detail || 0).toLocaleString('vi-VN')} VNĐ</td>
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
        function printInvoice(transactionId, transactionIdDetail) {
            if (transactionId && transactionIdDetail) {
                // Chuyển hướng đến đường dẫn in hóa đơn
                window.location.href = `/admin/invoice/${transactionId}/printDetail/${transactionIdDetail }`;
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
            const transactionIdDetail = $(this).data('transaction-detail-id');

            printInvoice(transactionId, transactionIdDetail); // Gọi hàm in hóa đơn
        });

        //Hiển thị hóa đơn
        $(document).on('click', '.btn-view-invoice', function() {
            const transactionId = $(this).data('transaction-id');
            const transactionIdDetail = $(this).data('transaction-detail-id');

            openCustomModal(transactionId, transactionIdDetail); // Gọi hàm mở modal
        });
    </script>
@endpush
