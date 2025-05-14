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
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap ">
            <div class="flex items-center flex-wrap justify-between gap20">
                <h3>Môn Học</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Môn học</div>
                    </li>
                </ul>
            </div>


            {{-- BỘ LỌC tìm kiếm --}}
            <div class="wg-box my-1 filter">
                <form class="row g-3 d-flex align-items-end" action="{{ route('admin.subjects.filter') }}" id="searchForm">
                    <div class="col-md-2">
                        <label for="course_id" class="form-label">Nhóm khóa học</label>
                        <select name="course_id" class="form-select select2">
                            <option value="">Tất cả nhóm khóa học</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="publish_status" class="form-label">Trạng thái</label>
                        <select name="publish_status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="1">Duyệt</option>
                            <option value="0">Chưa duyệt</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="keyword" class="form-label">Từ khóa</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm">
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <button type="reset" class="btn btn-danger">Xóa</button>
                    </div>

                    <input type="hidden" name="limit" id="limit" value="10">
                </form>
            </div>



            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <h4 class="text fs-4"><i class="icon-menu"></i> Danh sách môn học</h4>
                    <a class="tf-button style-1 w208" href="{{ route('admin.subjects.add') }}">
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
                                <th scope="col" style="width: 40px;">STT</th>
                                <th scope="col" style="width: 100px;">Ảnh</th>
                                <th scope="col">Tên môn học</th>
                                <th scope="col">Nhóm khóa học</th>
                                <th scope="col">Người tạo</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Phân quyền giáo viên</th>
                                <th scope="col" style="width: 150px;">Trạng thái xuất bản</th>
                                <th scope="col" style="width: 70px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="body-subjects">
                            @foreach ($subjects as $index => $subject)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ asset($subject->image ?? 'images/default.png') }}" alt="Ảnh bài giảng"
                                            width="70px" height="70px" style="object-fit: cover; border-radius: 6px;">
                                    </td>
                                    <td>{{ $subject->subject_name }}</td>
                                    <td>{{ $subject->course->course_name ?? 'Không có khóa học' }}</td>
                                    <td>{{ $subject->creator->full_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($subject->created_at)->format('H:i d/m/Y') }}</td>
                                    <td>{{ $subject->teachers->full_name ?? 'null'}}</td>
                                    <td>
                                        @if ($subject->publish_status == 1)
                                            <span class="badge bg-success p-2">Đã duyệt</span>
                                        @else
                                            <span class="badge bg-secondary p-2">Chưa duyệt</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <div class="border-0" type="button" data-bs-toggle="dropdown"
                                                data-bs-display="static">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                                            <ul class="dropdown-menu">
                                                <li class="">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="{{ route('admin.lessons', ['id' => $subject->id]) }}">
                                                        <div class="item eye">
                                                            <i class="icon-eye"></i> Chi tiết
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="{{ route('admin.subjects.edit', ['id' => $subject->id]) }}">
                                                        <i class="icon-edit-3 text-primary"></i> Sửa
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <form
                                                        action="{{ route('admin.subjects.delete', ['id' => $subject->id]) }}"
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
                </div>

                <div class="divider"></div>


                <div class="row align-items-center justify-content-between my-3">
                    <div class="col-auto" id="paginationWrapper">
                        {{ $subjects->links('pagination::bootstrap-5') }}
                    </div>
                    <div class="col-auto">
                        <label for="limit" class="me-2 mb-0">Số dòng hiển thị:</label>
                        <select name="limit" id="limit2" class="form-select">
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
            e.preventDefault();
            console.log($(this).serialize());

            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {

                    $('#body-subjects').html(renderSubjects(response.subjects.data));

                    $('#paginationWrapper').html(response.pagination); // chèn HTML phân trang

                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });

        // Hàm tạo danh sách môn học
        function renderSubjects(data) {
            if (data.length === 0) {
                return '<tr><td colspan="9" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }

            let html = '';
            data.forEach((subject, index) => {
                html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <img src="${subject.image ? assetPath(subject.image) : assetPath('images/default.png')}" alt="Ảnh môn học"
                        width="70px" height="70px" style="object-fit: cover; border-radius: 6px;">
                </td>
                <td>${subject.subject_name}</td>
                <td>${subject.course?.course_name || 'Không có khóa học'}</td>
                <td>${subject.creator?.full_name || ''}</td>
                <td>${formatDateTime(subject.created_at)}</td>
                <td>${subject.teachers?.full_name || ''}</td>
                <td>
                    ${subject.publish_status == 1
                        ? '<span class="badge bg-success p-2">Đã duyệt</span>'
                        : '<span class="badge bg-secondary p-2">Chưa duyệt</span>'}
                </td>
                <td class="text-center">
                    <div class="dropdown dropstart">
                         <div class="border-0" type="button" data-bs-toggle="dropdown"
                            data-bs-display="static">
                            <i class="icon-settings fs-4"></i>
                        </div>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                    href="/admin/subjects/${subject.id}/lessons">
                                    <div class="item eye">
                                        <i class="icon-eye"></i> Chi tiết
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                    href="/admin/subjects/edit/${subject.id}">
                                    <i class="icon-edit-3 text-primary"></i> Sửa
                                </a>
                            </li>
                            <li>
                                <form action="/admin/subjects/delete/${subject.id}" method="POST" class="delete-form">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
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

                    $('#body-subjects').html(renderSubjects(response.subjects.data));
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
