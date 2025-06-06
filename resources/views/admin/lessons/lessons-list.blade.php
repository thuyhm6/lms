@extends('layouts.admin')
@section('content')
    <style>
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

        .custom-modal {
            display: none;
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
            border-radius: 10px;
            width: 80%;
            height: 90vh;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease-in-out;
        }

        .custom-close {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 28px;
            cursor: pointer;
        }

        .custom-close:hover {
            color: black;
        }



        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20">
                <h3>Bài giảng "<span class="text-danger fw-bold">{{ $subjectsDetail->subject_name }}</span>"</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.subjects') }}">
                            <div class="text-tiny">Môn học</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">bài giảng</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box my-1">
                <form class="row g-3 d-flex align-items-end"
                    action="{{ route('admin.lessons.filter', ['id' => $subjectsDetail]) }}" method="post" id="searchForm">
                    @csrf
                    <div class="col-md-2">
                        <label for="topic" class="form-label">Chuyên đề</label>
                        <select name="topic" class="form-select select2">
                            <option value="">Tất cả chuyên đề</option>
                            @php
                                $displayedTopics = [];
                            @endphp
                            @foreach ($lessons as $lesson)
                                @if (!in_array($lesson->topic, $displayedTopics))
                                    <option value="{{ $lesson->topic }}">{{ $lesson->topic }}</option>
                                    @php
                                        $displayedTopics[] = $lesson->topic;
                                    @endphp
                                @endif
                            @endforeach
                        </select>
                    </div>

                    @php
                        $displayedFeeTypes = [];
                        $displayedFileTypes = [];
                    @endphp

                    <div class="col-md-2">
                        <label for="fee_type" class="form-label">Hình thức học phí</label>
                        <select name="fee_type" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($lessons as $lesson)
                                @if (!in_array($lesson->fee_type, $displayedFeeTypes))
                                    <option value="{{ $lesson->fee_type }}">{{ $lesson->fee_type }}</option>
                                    @php
                                        $displayedFeeTypes[] = $lesson->fee_type;
                                    @endphp
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="file_type" class="form-label">Loại tệp</label>
                        <select name="file_type" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($lessons as $lesson)
                                @if (!in_array($lesson->file_type, $displayedFileTypes))
                                    <option value="{{ $lesson->file_type }}">{{ $lesson->file_type }}</option>
                                    @php
                                        $displayedFileTypes[] = $lesson->file_type;
                                    @endphp
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="keyword" class="form-label">Từ khóa</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm">
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <button type="reset" class="btn btn-danger">Xóa</button>
                    </div>

                    <input type="hidden" name="limit" id="limit" value="10">
                </form>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h4 class="text fs-4"><i class="icon-menu"></i> Danh sách bài giảng</h4>
                        <div class="mt-4">
                            <a class="tf-button style-1 w208" href="{{ route('admin.subjects') }}">
                                <i class="icon-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <a class="tf-button style-1 w208"
                        href="{{ route('admin.lessons.add', ['id' => $subjectsDetail->id]) }}">
                        <i class="icon-plus"></i>Thêm mới
                    </a>
                </div>
                <div class="table-responsive">
                    @if (Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif

                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%; min-width: 50px;">STT</th>
                                <th style="width: 12%; min-width: 100px;">Chuyên đề</th>
                                <th style="width: 15%; min-width: 120px;">Tiêu đề</th>
                                <th style="width: 8%; min-width: 80px;">Phân loại</th>
                                <th style="width: 8%; min-width: 80px;">Loại tính phí</th>
                                <th style="width: 8%; min-width: 80px;">Loại file</th>
                                <th style="width: 7%; min-width: 70px;">Thời lượng</th>
                                <th style="width: 10%; min-width: 90px;">Người tạo</th>
                                <th style="width: 10%; min-width: 100px;">Ngày tạo</th>
                                <th style="width: 10%; min-width: 100px;">Lần cập nhật cuối</th>
                                <th style="width: 8%; min-width: 120px;">Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="body-lessons">
                            @foreach ($lessons as $index => $lesson)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $lesson->topic }}</td>
                                    <td>
                                        <strong>{{ $lesson->lesson_name }}</strong>
                                        <div class="text-muted mt-1">Nội dung: {{ $lesson->content }}</div>
                                    </td>
                                    <td>{{ $lesson->type }}</td>
                                    <td>{{ $lesson->fee_type }}</td>
                                    <td>{{ $lesson->file_type }}</td>
                                    <td>{{ $lesson->duration }}</td>
                                    <td>{{ $lesson->creator->full_name ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lesson->created_at)->format('H:i d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lesson->updated_at)->format('H:i d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <div class="border-0" type="button" data-bs-toggle="dropdown"
                                                data-bs-display="static">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                                            <ul class="dropdown-menu">
                                                <li class="py-2">
                                                    <div class="dropdown-item d-flex align-items-center gap-2 py-2 btn"
                                                        onclick="openModal('modal-{{ $lesson->id }}')">
                                                        <i class="icon-eye text-primary"></i> Xem
                                                    </div>
                                                </li>
                                                <li class="py-2">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 btn"
                                                        href="{{ route('admin.lessons.edit', ['id' => $lesson->id]) }}">
                                                        <i class="icon-edit-3 text-primary"></i> Sửa
                                                    </a>
                                                </li>
                                                <li class="py-2">
                                                    <form
                                                        action="{{ route('admin.lessons.delete', ['id' => $lesson->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="delete dropdown-item d-flex align-items-center gap-2 text-danger btn">
                                                            <i class="icon-trash-2"></i> Xóa
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal for lesson {{ $lesson->id }} -->
                                <div id="modal-{{ $lesson->id }}" class="custom-modal">
                                    <div class="custom-modal-content">
                                        <span class="custom-close" onclick="closeModal('modal-{{ $lesson->id }}')">×</span>
                                        <div id="modalContent-{{ $lesson->id }}">
                                            <iframe src="{{ asset($lesson->file_link.'/index.html') }}" style="width: 100%; height: 600px" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="divider mb-5"></div>
                    <div class="row align-items-center justify-content-between my-3">
                        <div class="col-auto" id="paginationWrapper">
                            {{ $lessons->links('pagination::bootstrap-5') }}
                        </div>
                        <div class="col-auto">
                            <label for="limit" class="me-2 mb-0">Số dòng hiển thị:</label>
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
@endsection

@push('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        // Xử lý xóa bài giảng
        $(document).on('click', '.delete', function(e) {
            e.preventDefault();
            var selectedForm = $(this).closest('form');
            swal({
                title: "Bạn có chắc không?",
                text: "Bạn muốn xóa bản ghi này?",
                type: "warning",
                buttons: ["Không", "Có"],
                confirmButonColor: "#dc3545"
            }).then(function(result) {
                if (result) {
                    selectedForm.submit();
                }
            });
        });

        // Xử lý thay đổi số dòng hiển thị
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });

        // Xử lý bộ lọc
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#body-lessons').html(renderLessons(response.lessons.data));
                    $('#paginationWrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });

        // Hàm tạo danh sách bài học
        function renderLessons(data) {
            if (data.length === 0)
                return '<tr><td colspan="11" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';

            let html = '';
            data.forEach((lesson, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${lesson.topic}</td>
                        <td>
                            <strong>${lesson.lesson_name}</strong>
                            <div class="text-muted mt-1">Nội dung: ${lesson.content}</div>
                        </td>
                        <td>${lesson.type}</td>
                        <td>${lesson.fee_type}</td>
                        <td>${lesson.file_type}</td>
                        <td>${lesson.duration}</td>
                        <td>${lesson.creator?.full_name || ''}</td>
                        <td>${formatDateTime(lesson.created_at)}</td>
                        <td>${formatDateTime(lesson.updated_at)}</td>
                        <td class="text-center">
                            <div class="dropdown dropstart">
                                <div class="border-0" type="button" data-bs-toggle="dropdown" data-bs-display="static">
                                    <i class="icon-settings fs-4"></i>
                                </div>
                                <ul class="dropdown-menu">
                                    <li class="py-2">
                                        <div class="dropdown-item d-flex align-items-center gap-2 py-2 btn" onclick="openModal('modal-${lesson.id}')">
                                            <i class="icon-eye text-primary"></i> Xem
                                        </div>
                                    </li>
                                    <li class="py-2">
                                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 btn" href="/admin/lessons/edit/${lesson.id}">
                                            <i class="icon-edit-3 text-primary"></i> Sửa
                                        </a>
                                    </li>
                                    <li class="py-2">
                                        <form action="/admin/lessons/delete/${lesson.id}" method="POST" class="delete-form">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="delete dropdown-item d-flex align-items-center gap-2 text-danger btn">
                                                <i class="icon-trash-2"></i> Xóa
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <div id="modal-${lesson.id}" class="custom-modal">
                        <div class="custom-modal-content">
                            <span class="custom-close" onclick="closeModal('modal-${lesson.id}')">×</span>
                            <div id="modalContent-${lesson.id}">
                                ${lesson.file_type === 'video' ?
                                    `<video controls style="width: 100%; height: 400px;">
                                        <source src="${lesson.file_link}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>` :
                                    lesson.file_type === 'pdf' ?
                                    `<iframe src="${lesson.file_link}" style="width: 100%; height: 400px;" frameborder="0"></iframe>` :
                                    `<p>Loại tệp không được hỗ trợ: ${lesson.file_type}</p>`
                                }
                            </div>
                        </div>
                    </div>
                `;
            });
            return html;
        }

        // Hàm định dạng thời gian
        function formatDateTime(dateTime) {
            const date = new Date(dateTime);
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${hours}:${minutes} ${day}/${month}/${year}`;
        }

        // Xử lý phân trang
        $(document).on('click', '#paginationWrapper a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#body-lessons').html(renderLessons(response.lessons.data));
                    $('#paginationWrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });

        // Xử lý modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = "block";
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = "none";
            }
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('custom-modal');
            for (let modal of modals) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>
@endpush
