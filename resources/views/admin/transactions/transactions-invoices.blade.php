<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn thanh toán</title>
    <style>
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

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 10px;
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

        th, td {
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
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>HÓA ĐƠN THANH TOÁN</h1>
        <p class="center">Trung tâm 8-BIT</p>

        <div class="info">
            <p><strong>Mã hóa đơn:</strong> HD{{ $transaction->id }}</p>
            <p><strong>Ngày lập:</strong> {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</p>
            <p><strong>Người lập:</strong> Quản trị viên</p>
            <p><strong>Chi nhánh:</strong> CS1 - Thanh Hóa</p>
        </div>

        <div class="section-title">Thông tin học sinh</div>
        @if(!empty($StudentParent[0]))
            @php $sp = $StudentParent[0]; @endphp
            <p><strong>Họ tên:</strong> {{ $sp->student_name }}</p>
            <p><strong>Email:</strong> {{ $sp->student_email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $sp->student_phone }}</p>
            <p><strong>Trường:</strong> {{ $sp->student_school }}</p>
            <p><strong>Lớp:</strong> {{ $sp->student_grade }}</p>
        @endif

        <div class="section-title">Thông tin phụ huynh</div>
        @if(!empty($sp->parent_name))
            <p><strong>Họ tên:</strong> {{ $sp->parent_name }}</p>
            <p><strong>Email:</strong> {{ $sp->parent_email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $sp->parent_phone }}</p>
        @else
            <p>Không có thông tin phụ huynh.</p>
        @endif

        <div class="section-title">Chi tiết thanh toán</div>
        <table>
            <thead>
                <tr>
                    <th>Tên khóa học</th>
                    <th>Số buổi</th>
                    <th>Học phí</th>
                    <th>Đã thanh toán</th>
                    @if ($transaction->amount_paid_detail)
                        <th>Tổng đã thanh toán</th>
                    @endif
                    <th>Công nợ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->package_name }}</td>
                    <td>{{ $transaction->number_of_sessions }}</td>
                    <td>{{ number_format($transaction->price, 0, ',', '.') }} VNĐ</td>
                    @if ($transaction->amount_paid_detail)
                        <td>{{ number_format($transaction->amount_paid_detail, 0, ',', '.') }} VNĐ</td>
                    @endif
                    <td>{{ number_format($transaction->amount_paid, 0, ',', '.') }} VNĐ</td>
                    <td>{{ number_format($transaction->price - $transaction->amount_paid -  $transaction->scholarship_amount, 0, ',', '.') }} VNĐ</td>
                </tr>
            </tbody>
        </table>

        <div class="thank-you">
            <p>Xin cảm ơn quý phụ huynh đã đăng ký cho học sinh tham gia khóa học tại trung tâm.</p>
        </div>
    </div>
</body>
</html>
