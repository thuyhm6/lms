@extends('layouts.app')
@section('content')
    <style>
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            text-align: center;
            margin-bottom: 20px;
        }

        .my-account__dashboard__welcome__title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .my-account__dashboard__welcome__text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 500;
            color: #374151;
        }

        .card-value {
            font-size: 1.75rem;
            font-weight: 600;
        }

        .text-blue {
            color: #2563eb;
        }

        .text-green {
            color: #16a34a;
        }

        .text-red {
            color: #dc2626;
        }

        .transaction-card {
            transition: background 0.2s ease;
        }

        .transaction-card:hover {
            background: #f9fafb;
        }

        .transaction-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .transaction-title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #1f2937;
        }

        .badge {
            background: #e0f2fe;
            color: #1e40af;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }

        .badge2 {
            background: #fff0f0;
            color: #1e40af;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }

        .transaction-details,
        .modal-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 8px;
        }

        .transaction-details p,
        .modal-details p {
            font-size: 0.8125rem;
            color: #4b5563;
            margin: 0;
        }

        .transaction-details strong,
        .modal-details strong {
            font-weight: 500;
            color: #1f2937;
        }

        .modal-content {
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(90deg, #2563eb, #1e40af);
            color: #fff;
            border: none;
            padding: 12px;
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 500;
            color: #ffffff;
        }

        .modal-body {
            padding: 16px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .my-account__dashboard__welcome__title {
                font-size: 1rem;
            }

            .card-value {
                font-size: 1.5rem;
            }

            .transaction-title {
                font-size: 1rem;
            }
        }


        /* CSS cải tiến cho bảng table-dashboard-myaccount */
        .table-dashboard-myaccount {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-dashboard-myaccount thead {
            background-color: #cecece;
            /* Màu xanh đậm */
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .table-dashboard-myaccount th,
        .table-dashboard-myaccount td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 0.875rem;
            color: #374151;
        }

        .table-dashboard-myaccount tbody tr:hover {
            background-color: #f9fafb;
            /* Màu nền khi hover */
        }

        .table-dashboard-myaccount tbody tr:last-child td {
            border-bottom: none;
            /* Xóa đường viền dưới cùng của hàng cuối */
        }

        .table-dashboard-myaccount th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table-dashboard-myaccount td {
            vertical-align: middle;
        }

        .table-dashboard-myaccount tbody tr:nth-child(even) {
            background-color: #f3f4f6;
            /* Màu nền xen kẽ */
        }

        .table-dashboard-myaccount td span.text-red {
            color: #dc2626;
            /* Màu đỏ cho các giá trị nợ */
            font-weight: bold;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">My Account</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <div class="my-account__dashboard__welcome">
                            <h3 class="my-account__dashboard__welcome__title">Welcome, {{ auth()->user()->full_name }}</h3>
                            <p class="my-account__dashboard__welcome__text">Tài khoản {{ auth()->user()->utype }}</p>
                        </div>
                        @if (Auth::user()->utype === 'STUDENT')
                            <main class="container">
                                <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
                                    <div class="col">
                                        <div class="card p-3">
                                            <div class="card-header">
                                                <h3 class="card-title">Số Buổi Đã Học</h3>
                                                <svg width="20" height="20" fill="none" stroke="#9ca3af"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                            </div>
                                            <p class="card-value text-blue">{{ $student->attended_sessions }}</p>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card p-3">
                                            <div class="card-header">
                                                <h3 class="card-title">Số Buổi Được Học</h3>
                                                <svg width="20" height="20" fill="none" stroke="#9ca3af"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="card-value text-green">{{ $student->registered_sessions }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                            </main>
                        @elseif(Auth::user()->utype === 'PARENT')
                            <h3 class="my-account__dashboard__welcome__title">Gói đăng ký</h3>
                                <div class="row row-cols-1 g-3">
                                    @foreach ($transactions as $transaction)
                                        <div class="col">
                                            <div class="card p-3 transaction-card">
                                                <div class="transaction-header">
                                                    <h4 class="transaction-title">
                                                        {{ $transaction->coursePackage->package_name ?? 'N/A' }}</h4>
                                                    {{-- <span class="blade">{{  $transaction->student->remaining_sessions==0 ? 'Hết Hạn' : 'Còn Hạn'  }}</span> --}}
                                                </div>
                                                <div class="transaction-details">
                                                    <p><strong>Học Sinh:</strong>
                                                        {{ $transaction->student->full_name ?? 'N/A' }}</p>
                                                    <p><strong>Số Buổi:</strong>
                                                        {{ $transaction->coursePackage->number_of_sessions ?? 0 }}</p>
                                                    <p><strong>Giá Tiền:</strong>
                                                        {{ number_format($transaction->coursePackage->price ?? 0, 0, ',', '.') }}
                                                        VND</p>
                                                    <p><strong>Buổi Khuyến Mãi:</strong>
                                                        {{ $transaction->promo_sessions ?? 0 }}
                                                    </p>
                                                    <p><strong>Học Bổng:</strong>
                                                        {{ number_format($transaction->scholarship_amount ?? 0, 0, ',', '.') }}
                                                        VND</p>
                                                    <p><strong>Tổng Số Buổi:</strong>
                                                        {{ ($transaction->coursePackage->number_of_sessions ?? 0) + ($transaction->promo_sessions ?? 0) }}
                                                    </p>
                                                    <p><strong>Tổng Tiền:</strong>
                                                        {{ number_format(($transaction->coursePackage->price ?? 0) - ($transaction->scholarship_amount ?? 0), 0, ',', '.') }}
                                                        VND
                                                    </p>
                                                    <p><strong>Đã Thanh Toán:</strong>
                                                        {{ number_format($transaction->amount_paid ?? 0, 0, ',', '.') }}
                                                        VND
                                                    </p>
                                                    <p><strong>Còn Nợ:</strong>
                                                        <span
                                                            class="text-red">{{ number_format($transaction->debt ?? 0, 0, ',', '.') }}
                                                            VND</span>
                                                    </p>
                                                </div>
                                                <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal"
                                                    data-bs-target="#transactionModal{{ $transaction->id }}">Xem Chi
                                                    Tiết</button>
                                            </div>
                                        </div>

                                        <!-- Modal Chi Tiết -->
                                        <div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1"
                                            aria-labelledby="transactionModalLabel{{ $transaction->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="transactionModalLabel{{ $transaction->id }}">Chi Tiết Giao
                                                            Dịch
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-details">
                                                            <p><strong>Học Sinh:</strong>
                                                                {{ $transaction->student->full_name ?? 'N/A' }}</p>
                                                            <p><strong>Khóa Đăng Ký:</strong>
                                                                {{ $transaction->coursePackage->package_name ?? 'N/A' }}
                                                            </p>
                                                            <p><strong>Số Buổi:</strong>
                                                                {{ $transaction->coursePackage->number_of_sessions ?? 0 }}
                                                            </p>
                                                            <p><strong>Giá Tiền:</strong>
                                                                {{ number_format($transaction->coursePackage->price ?? 0, 0, ',', '.') }}
                                                                VND</p>
                                                            <p><strong>Buổi Khuyến Mãi:</strong>
                                                                {{ $transaction->promo_sessions ?? 0 }}</p>
                                                            <p><strong>Học Bổng:</strong>
                                                                {{ number_format($transaction->scholarship_amount ?? 0, 0, ',', '.') }}
                                                                VND</p>
                                                            <p><strong>Tổng Số Buổi:</strong>
                                                                {{ ($transaction->coursePackage->number_of_sessions ?? 0) + ($transaction->promo_sessions ?? 0) }}
                                                            </p>
                                                            <p><strong>Tổng Tiền:</strong>
                                                                {{ number_format(($transaction->coursePackage->price ?? 0) - ($transaction->scholarship_amount ?? 0), 0, ',', '.') }}
                                                                VND
                                                            </p>
                                                            <p><strong>Đã Thanh Toán:</strong>
                                                                {{ number_format($transaction->amount_paid ?? 0, 0, ',', '.') }}
                                                                VND</p>
                                                            <p><strong>Còn Nợ:</strong>
                                                                <span
                                                                    class="text-red">{{ number_format($transaction->debt ?? 0, 0, ',', '.') }}
                                                                    VND</span>
                                                            </p>

                                                        </div>

                                                        <table class="table-dashboard-myaccount">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Số tiền giao dịch</th>
                                                                    <th>Ngày giao dịch</th>
                                                                    <th>Ghi chú</th>

                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($transaction->transactionDetails as $key => $detail)
                                                                    <tr>
                                                                        <td>{{ $key + 1 }}</td>
                                                                        <td>{{ number_format($detail->amount_paid, 0, ',', '.') }}
                                                                            VND</td>
                                                                        <td>{{ $detail->created_at->format('d/m/Y H:i') }}
                                                                        </td>
                                                                        <td>{{ $detail->note }}</td>

                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                        @endif

                    </div>
                </div>
        </section>
    </main>
@endsection
