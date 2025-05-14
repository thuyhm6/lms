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
                        // Khởi tạo mảng để lưu các giá trị đã xuất hiện
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
                                        // Thêm fee_type vào mảng sau khi đã hiển thị
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
                                        // Thêm file_type vào mảng sau khi đã hiển thị
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
                                                {{-- <li class="py-2">
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.lessons', ['id'=>$lesson->id]) }}"
                                                    target="_blank">
                                                    <div class="item eye py-2">
                                                        <i class="icon-eye"></i> Chi tiết
                                                    </div>
                                                </a>
                                            </li> --}}
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

        // Thành (sử dụng event delegation):
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
                    console.log(response.pagination);
                    $('#body-lessons').html(renderLessons(response.lessons.data));

                    $('#paginationWrapper').html(response.pagination); // chèn HTML phân trang

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
                          <div class="border-0" type="button" data-bs-toggle="dropdown"
                                                data-bs-display="static">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                        <ul class="dropdown-menu">
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
            const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Tháng bắt đầu từ 0
            const year = date.getFullYear();

            return `${hours}:${minutes} ${day}/${month}/${year}`;
        }


        $(document).on('click', '#paginationWrapper a', function(e) {
            e.preventDefault(); // Ngăn trang tải lại

            const url = $(this).attr('href'); // Lấy URL phân trang (bao gồm các tham số lọc)

            $.ajax({
                url: url, // Gọi URL phân trang
                type: 'GET',
                success: function(response) {
                    // Cập nhật kết quả bài học và phân trang
                    $('#body-lessons').html(renderLessons(response.lessons.data));
                    $('#paginationWrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });
    </script>
@endpush
