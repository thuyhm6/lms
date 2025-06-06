@extends('layouts.app')
@section('content')
    <style>
        .tabs {
            margin-bottom: 20px;
        }

        .tab-links {
            display: flex;
            gap: 10px;
            border-bottom: 1px solid #ddd;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .tab-link {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid transparent;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
            background: #f8f9fa;
        }

        .tab-link.active {
            background: #fff;
            border-color: #ddd;
            margin-bottom: -1px;
            padding-bottom: 11px;
        }

        .filter-section {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .filter-form {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .filter-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .filter-button {
            padding: 8px 15px;
            background: #248eff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .schedules-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .schedules-table th,
        .schedules-table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .schedules-table th {
            background: #248eff;
            color: white;
        }

        .lesson-btn {
            display: inline-block;
            margin: 4px 4px 0 0;
            font-size: 14px;
            background-color: #17a2b8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .lesson-btn:hover {
            background-color: #138496;
        }

        /* modal */
        .custom-modal {
            display: none;
            /* Ẩn mặc định */
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .custom-modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 6px;
            width: 60%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .custom-modal-close {
            position: absolute;
            right: 12px;
            top: 8px;
            font-size: 22px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
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

                    <divc class="page-content my-account__dashboard">
                        <div class="tabs">
                            <ul class="tab-links">
                                @foreach ($children as $child)
                                    <li class="tab-link {{ $child->id == $selectedChildId ? 'active' : '' }}"
                                        onclick="changeTab({{ $child->id }})">
                                        {{ $child->full_name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
    
                        <div class="filter-section">
                            <form class="filter-form" id="filterForm">
                                <input type="hidden" name="child_id" id="childIdInput" value="{{ $selectedChildId }}">
                                <input type="date" name="from_date" class="filter-input" value="{{ request('from_date') }}">
                                <input type="date" name="to_date" class="filter-input" value="{{ request('to_date') }}">
                                <button type="submit" class="filter-button">Lọc</button>
                            </form>
                        </div>
    
                        <div class="schedules-content">
                            <table class="schedules-table">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Ngày học</th>
                                        <th>Thời gian</th>
                                        <th>Khóa học</th>
                                        <th>Môn học</th>
                                        <th>Bài giảng</th>
                                        <th>Giáo viên</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $index => $schedule)
                                        <tr>
                                            <td>{{ $schedule->stt }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->ngay_hoc)->format('d/m/Y') }}</td>
                                            <td>{{ $schedule->tu_gio }} - {{ $schedule->den_gio }}</td>
                                            <td>{{ $schedule->khoa_hoc }}</td>
                                            <td>{{ $schedule->mon_hoc }}</td>
                                            <td>
                                                @php
                                                    $lines = explode("\n", $schedule->bai_giang);
                                                @endphp
                                                @foreach ($lines as $i => $line)
                                                    <button class="lesson-btn"
                                                        onclick="openModal('{{ 'modal-' . $index . '-' . $i }}')">
                                                        {{ $i + 1 }}. {{ Str::limit($line, 30) }}
                                                    </button>

                                                    <!-- Modal tùy chỉnh -->
                                                    <div id="{{ 'modal-' . $index . '-' . $i }}" class="custom-modal">
                                                        <div class="custom-modal-content">
                                                            <span class="custom-modal-close"
                                                                onclick="closeModal('{{ 'modal-' . $index . '-' . $i }}')">&times;</span>
                                                            <h5>Bài {{ $i + 1 }}</h5>
                                                            <p>{{ $line }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>{{ $schedule->giao_vien }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có lịch học nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </divc>
                </div>
            </div>
        </section>

    </main>

    <script>

        function openModal(id) {
            document.getElementById(id).style.display = 'block';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Đóng modal khi click ngoài vùng nội dung
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.custom-modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
        function changeTab(childId) {
            document.getElementById('childIdInput').value = childId;
            document.getElementById('filterForm').submit();
        }

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            window.location.href = `{{ route('parent.schedules') }}?${params.toString()}`;
        });
    </script>
@endsection
