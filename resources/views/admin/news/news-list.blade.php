@extends('layouts.admin')
@section('content')
    <style>
        input[type=checkbox][disabled]:checked {
            background-color: #0d6efd !important;
            /* Nền xanh đậm */
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
            appearance: ;
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
            /* Tăng kích thước chữ */
            padding: 1rem;
            /* Tăng khoảng cách giữa các dòng */
        }

        .table-custom th {
            font-size: 1.2rem;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20">
                <h3>Tin tức</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Tin tức</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box my-1">

                <form class="row g-3 d-flex align-items-end" id="searchForm" action="{{ route('admin.news.filter') }}">

                    <div class="col-md-2">
                        <label for="is_visible" class="form-label">Hiển thị</label>
                        <select name="is_visible" class="form-select select2">
                            <option value="">Tất cả</option>
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_featured" class="form-label">Nổi bật</label>
                        <select name="is_featured" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1">Có</option>
                            <option value="0">Không</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_latest" class="form-label">Mới nhất</label>
                        <select name="is_latest" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1">Có</option>
                            <option value="0">Không</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="show_on_homepage" class="form-label">Hiện trang chủ</label>
                        <select name="show_on_homepage" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="1">Có</option>
                            <option value="0">Không</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="keyword" class="form-label">Từ khóa</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Từ khóa tìm kiếm">
                    </div>
                    <input type="hidden" name="limit" id="limit" value="10">

                    <div class="col-md-2 d-flex">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <button type="reset" class="btn btn-danger">Xóa</button>
                    </div>

                </form>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">

                    <h4 class="text fs-4"><i class="icon-menu"></i> Danh sách tin tức</h4>

                    <a class="tf-button style-1 w208" href="{{ route('admin.news.add') }}"><i class="icon-plus"></i>Thêm
                        mới</a>
                </div>

                <div class="table-responsive">
                    @if (Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif
                    <table class="table table-hover align-middle table-custom">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 40px;">STT</th>
                                <th scope="col">Tên bài viết</th>
                                <th scope="col" style="width: 80px;">Hiển thị</th>
                                <th scope="col" style="width: 150px;">Thời gian đăng</th>
                                <th scope="col" style="width: 90px;">Lượt xem</th>
                                <th scope="col" style="width: 100px;">Hiện trang chủ</th>
                                <th scope="col" style="width: 80px;">Nổi bật</th>
                                <th scope="col" style="width: 80px;">Mới nhất</th>
                                <th scope="col" style="width: 70px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="body-news">
                            @foreach ($news as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2" style="min-width: 0;">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset($item->image ?? 'images/default.png') }}"
                                                    alt="{{ $item->image_caption }}" width="50" class="rounded">
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <a href="{{ route('admin.news.view', ['id' => $item->id]) }}"
                                                    class="fw-semibold text-primary text-decoration-none text-truncate d-block"
                                                    style="max-width: 100%;">
                                                    {{ $item->title }}
                                                </a>
                                                <div class="small text-muted text-truncate">
                                                    Cập nhật: {{ $item->updated_at->format('H:i d/m/Y') }} | Người tạo:
                                                    {{ $item->creator->full_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input class="form-check-input" type="checkbox" disabled
                                            {{ $item->is_visible ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $item->created_at->format('H:i d/m/Y') }}</td>
                                    <td><span class="text-primary fw-semibold">{{ $item->views }}</span></td>
                                    <td><input class="form-check-input" type="checkbox" disabled
                                            {{ $item->show_on_homepage ? 'checked' : '' }}></td>
                                    <td><input class="form-check-input" type="checkbox" disabled
                                            {{ $item->is_featured ? 'checked' : '' }}></td>
                                    <td><input class="form-check-input" type="checkbox" disabled
                                            {{ $item->is_latest ? 'checked' : '' }}></td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <div class="border-0" type="button" data-bs-toggle="dropdown"
                                                data-bs-display="static">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                                            <ul class="dropdown-menu">
                                                <li class="">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="" target="_blank">
                                                        <div class="item eye">
                                                            <i class="icon-eye"></i> Xem
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                                        href="{{ route('admin.news.edit', ['id' => $item->id]) }}">
                                                        <i class="icon-edit-3 text-primary"></i> Sửa
                                                    </a>
                                                </li>
                                                <li class="">
                                                    <form action="{{ route('admin.news.delete', ['id' => $item->id]) }}"
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
                        {{ $news->links('pagination::bootstrap-5') }}
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

        // Gán sự kiện submit cho form lọc
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                contentType: 'application/json; charset=utf-8', // Đảm bảo UTF-8
                dataType: 'json', // Đảm bảo nhận về JSON
                success: function(response) {
                    console.log("Dữ liệu nhận được:", response.news.data);
                    $('#body-news').html(renderNews(response.news.data));
                    $('#paginationWrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });

        // Khi thay đổi limit ở formLimit
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });

        // Hàm tạo danh sách tin tức
        function renderNews(data) {
            if (data.length === 0) {
                return '<tr><td colspan="9" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }

            let html = '';
            data.forEach((item, index) => {
                html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <div class="d-flex align-items-start gap-2" style="min-width: 0;">
                        <div class="flex-shrink-0">
                            <img src="${item.image ? assetPath(item.image) : assetPath('images/default.png')}" alt="${item.image_caption || ''}"
                                width="50" class="rounded">
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <a href="/admin/news/view/${item.id}"
                                class="fw-semibold text-primary text-decoration-none text-truncate d-block"
                                style="max-width: 100%;">
                                ${item.title}
                            </a>
                            <div class="small text-muted text-truncate">
                                Cập nhật: ${formatDateTime(item.updated_at)} | Người tạo:
                                ${item.creator?.full_name || ''}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <input class="form-check-input" type="checkbox" disabled
                        ${item.is_visible ? 'checked' : ''}>
                </td>
                <td>${formatDateTime(item.created_at)}</td>
                <td><span class="text-primary fw-semibold">${item.views}</span></td>
                <td><input class="form-check-input" type="checkbox" disabled
                        ${item.show_on_homepage ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="checkbox" disabled
                        ${item.is_featured ? 'checked' : ''}></td>
                <td><input class="form-check-input" type="checkbox" disabled
                        ${item.is_latest ? 'checked' : ''}></td>
                <td class="text-center">
                    <div class="dropdown dropstart">
                         <div class="border-0" type="button" data-bs-toggle="dropdown"
                                                data-bs-display="static">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                        <ul class="dropdown-menu">
                            <li class="">
                                <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                    href="" target="_blank">
                                    <div class="item eye">
                                        <i class="icon-eye"></i> Xem
                                    </div>
                                </a>
                            </li>
                            <li class="">
                                <a class="dropdown-item d-flex align-items-center gap-2 btn"
                                    href="/admin/news/edit/${item.id}">
                                    <i class="icon-edit-3 text-primary"></i> Sửa
                                </a>
                            </li>
                            <li class="">
                                <form action="/admin/news/delete/${item.id}" method="POST">
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
            </tr>`;
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
                    console.log(response.news.data);
                    $('#body-news').html(renderNews(response.news.data));
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
