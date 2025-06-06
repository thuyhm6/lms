@extends('layouts.admin')
@section('content')
    <style>
        .form-control,
        .form-select,
        .btn {
            height: 40px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 4px;
        }

        textarea.form-control {
            height: auto;
        }

        .bg-white {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .customer-info {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .customer-info p {
            margin: 5px 0;
            font-size: 1.2rem;
        }

        .table-custom th,
        .table-custom td {
            font-size: 1.2rem;
            padding: 0.75rem;
            vertical-align: middle;
            width: auto;
            white-space: nowrap;
            text-align: left;
        }

        .table-custom th {
            background-color: #e9ecef;
        }

        .table-custom .form-control,
        .table-custom .form-select {
            height: 35px;
            font-size: 0.9rem;
        }

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

        .btn-add {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-close {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-add,
        .btn-close {
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 4px;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {

            .table-custom th,
            .table-custom td {
                font-size: 0.9rem;
                padding: 0.5rem;
            }

            .table-custom .form-control,
            .table-custom .form-select {
                height: 30px;
            }

            .btn-add,
            .btn-close {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>

    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <div class="d-flex items-center gap20">
                    <h3>Thêm giao dịch</h3>
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

            <form method="POST" action="{{ route('admin.transactions.store') }}">
                @csrf
                <div class="bg-white rounded p-3">
                    <!-- Customer Information -->
                    <div class="customer-info">
                        <h5>Khách hàng <span class="text-danger">*</span></h5>
                        <select name="student_id" class="form-select mb-3" id="student-select">
                            <option value="" selected>--Chọn học sinh--</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->user_id }}"
                                    {{ old('student_id') == $student->user_id ? 'selected' : '' }}
                                    data-parent="{{ $student->parent?->user?->full_name ?? 'N/A' }}"
                                    data-school="{{ $student->school }}"
                                    data-phone="{{ $student->parent?->user?->mobile ?? 'N/A' }}"
                                    data-email="{{ $student->parent?->user?->email ?? 'N/A' }}">
                                    {{ $student->user->full_name ?? 'Chưa có phụ huynh' }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror

                        <div id="customer-details">
                            <p><strong>Phụ huynh:</strong> <span id="parent-name"></span></p>
                            <p><strong>Trường:</strong> <span id="school"></span></p>
                            <p><strong>Số điện thoại:</strong> <span id="phone"></span></p>
                            <p><strong>Email:</strong> <span id="email"></span></p>
                        </div>
                    </div>

                    <!-- Service Selection -->
                    <h5>GIAO DỊCH</h5>
                    <div class="table-responsive">
                        <table class="table align-middle table-custom">
                            <thead class="table-light">
                                <tr>
                                    <th>Khóa đăng ký</th>
                                    <th>Số buổi</th>
                                    <th>Giá tiền</th>
                                    <th>Buổi khuyến mãi</th>
                                    <th>Học bổng</th>
                                    <th>Tổng số buổi</th>
                                    <th>Tổng tiền</th>
                                    <th>Đã thanh toán</th>
                                    <th>Công nợ</th>
                                </tr>
                            </thead>
                            <tbody id="service-rows">
                                <tr>
                                    <td>
                                        <select name="course_packages_id" class="form-select course-package">
                                            <option value="" selected>--Chọn gói khóa học--</option>
                                            @foreach ($course_packages as $package)
                                                <option value="{{ $package->id }}" data-price="{{ $package->price }}"
                                                    data-number-of-sessions="{{ $package->number_of_sessions }}"
                                                    {{ old('course_packages_id') == $package->id ? 'selected' : '' }}>
                                                    {{ $package->package_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="number_of_sessions"
                                            class="form-control number-of-sessions"
                                            value="{{ old('number_of_sessions', 0) }}" min="0" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="price" class="form-control price"
                                            value="{{ old('price', 0) }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="promo_sessions" class="form-control promo-sessions"
                                            value="{{ old('promo_sessions', 0) }}" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="scholarship_amount"
                                            class="form-control scholarship-amount"
                                            value="{{ old('scholarship_amount', 0) }}" min="0">
                                    </td>
                                    <td>
                                        <input type="number" name="total_sessions" class="form-control total-sessions"
                                            value="{{ old('total_sessions', 0) }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="total" class="form-control total"
                                            value="{{ old('total', 0) }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="amount_paid" class="form-control amount-paid"
                                            value="{{ old('amount_paid', 0) }}" min="0" step="1000">
                                    </td>
                                    <td>
                                        <input type="number" name="debt" class="form-control debt"
                                            value="{{ old('debt', 0) }}" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Amount -->
                    <div class="total-amount">
                        Tổng số tiền: <span id="total-amount">0</span> Đồng
                        (<span id="total-amount-text">Không đồng</span>)
                    </div>

                    <!-- Total Amount -->
                    <div class="total-amount">
                        Đã thanh toán: <span id="total-amount-paid">0</span> Đồng
                        (<span id="total-amount-paid-text">Không đồng</span>)
                    </div>


                    <!-- Total Debt -->
                    <div class="total-debt">
                        Công nợ: <span id="total-debt">0</span> Đồng
                        (<span id="total-debt-text">Không đồng</span>)
                    </div>

                    <!-- Notes -->
                    <div class="mt-4">
                        <label class="body-title mb-2">Ghi chú</label>
                        <textarea name="note" rows="3" class="form-control">{{ old('note') }}</textarea>
                        @error('notes')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
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
                    <!-- Buttons -->
                    <div class="mt-4 d-flex justify-content-between">

                        <button type="submit" class="btn btn-primary">Thêm Giao Dịch</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Chuyển đổi số thành văn bản tiếng Việt
            function numberToVietnameseText(num) {
                const units = ['', 'nghìn', 'triệu', 'tỷ'];
                const numbers = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];

                if (num === 0) return 'Không đồng';

                let result = '';
                let unitIndex = 0;

                while (num > 0) {
                    let chunk = num % 1000;
                    if (chunk > 0) {
                        let chunkText = '';
                        let hundreds = Math.floor(chunk / 100);
                        let tens = Math.floor((chunk % 100) / 10);
                        let ones = chunk % 10;

                        if (hundreds > 0) {
                            chunkText += numbers[hundreds] + ' trăm';
                            if (tens > 0 || ones > 0) chunkText += ' ';
                        }

                        if (tens > 0) {
                            if (tens === 1) {
                                chunkText += 'mười';
                            } else {
                                chunkText += numbers[tens] + ' mươi';
                            }
                            if (ones > 0) chunkText += ' ';
                        }

                        if (ones > 0) {
                            if (tens > 0 && ones === 1) {
                                // Chỉ dùng "mốt" khi hàng chục bắt đầu bằng 2
                                chunkText += (tens === 2) ? 'mốt' : 'một';
                            } else if (tens > 0 && ones === 5) {
                                chunkText += 'lăm';
                            } else {
                                chunkText += numbers[ones];
                            }
                        }

                        if (unitIndex > 0) {
                            chunkText += ' ' + units[unitIndex];
                            if (result !== '') chunkText += ' ';
                        }

                        result = chunkText + result;
                    }
                    num = Math.floor(num / 1000);
                    unitIndex++;
                }

                return result.charAt(0).toUpperCase() + result.slice(1) + ' đồng';
            }

            // Tính tổng tiền và các giá trị liên quan cho một hàng
            function calculateRowTotal(row) {
                const numberOfSessions = parseInt(row.find('.number-of-sessions').val()) || 0;
                const price = parseInt(row.find('.price').val()) || 0;
                const promoSessions = parseInt(row.find('.promo-sessions').val()) || 0;
                const scholarshipAmount = parseInt(row.find('.scholarship-amount').val()) || 0;
                const amountPaid = parseInt(row.find('.amount-paid').val()) || 0;

                // Tính tổng số buổi
                const totalSessions = numberOfSessions + promoSessions;
                row.find('.total-sessions').val(totalSessions);

                // Tính tổng tiền
                const total = price - scholarshipAmount;
                row.find('.total').val(total);


                // Tính công nợ
                const debt = total - amountPaid;
                row.find('.debt').val(Math.max(debt, 0));

                calculateGrandTotal();
            }

            // Tính tổng tiền và tổng công nợ toàn bộ
            function calculateGrandTotal() {
                let grandTotal = 0;
                let totalDebt = 0;
                let totalPaid = 0;
                $('#service-rows tr').each(function() {
                    const total = parseInt($(this).find('.total').val()) || 0;
                    const debt = parseInt($(this).find('.debt').val()) || 0;
                    const amountPaid = parseInt($(this).find('.amount-paid').val()) || 0;
                    grandTotal += total;
                    totalDebt += debt;
                    totalPaid += amountPaid;
                });
                $('#total-amount').text(grandTotal.toLocaleString('vi-VN'));
                $('#total-amount-text').text(numberToVietnameseText(grandTotal));


                $('#total-amount-paid').text(totalPaid.toLocaleString('vi-VN'));
                $('#total-amount-paid-text').text(numberToVietnameseText(totalPaid));

                $('#total-debt').text(totalDebt.toLocaleString('vi-VN'));
                $('#total-debt-text').text(numberToVietnameseText(totalDebt));
            }

            // Cập nhật thông tin khách hàng và học sinh khi chọn học sinh
            $('select[name="student_id"]').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                $('#parent-name').text(selectedOption.data('parent') || '');
                $('#school').text(selectedOption.data('school') || '');
                $('#phone').text(selectedOption.data('phone') || '');
                $('#email').text(selectedOption.data('email') || '');

                // Cập nhật học sinh trong bảng
                const studentName = selectedOption.text() || '';
                const studentId = selectedOption.val() || '';
                $('#service-rows tr').each(function() {
                    $(this).find('.student-name').val(studentName);
                    $(this).find('.student-id').val(studentId);
                });

                // Hiển thị form khi chọn học sinh
                if (selectedOption.val()) {
                    $('#customer-details').show();
                } else {
                    $('#customer-details').hide();
                }
            });

            // Kích hoạt sự kiện thay đổi khi trang tải để hiển thị thông tin khách hàng nếu học sinh đã được chọn trước
            $('select[name="student_id"]').trigger('change');

            // Xử lý khi chọn gói khóa học
            $(document).on('change', '.course-package', function() {
                const row = $(this).closest('tr');
                const selectedOption = $(this).find('option:selected');
                const price = parseInt(selectedOption.data('price')) || 0;
                const numberOfSessions = parseInt(selectedOption.data('number-of-sessions')) || 0;
                row.find('.price').val(price);
                row.find('.number-of-sessions').val(numberOfSessions);
                calculateRowTotal(row);
            });

            // Xử lý khi thay đổi số lượng, khuyến mãi, học bổng, đã thanh toán
            $(document).on('input', '.number-of-sessions, .promo-sessions, .scholarship-amount, .amount-paid',
                function() {
                    const row = $(this).closest('tr');
                    calculateRowTotal(row);
                });

            // Tính toán ban đầu
            calculateGrandTotal();
        });
    </script>
@endpush
