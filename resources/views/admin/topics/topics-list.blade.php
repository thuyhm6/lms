@extends('layouts.admin')

@section('content')
    <style>

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
        .table-custom td {
            font-size: 1.2rem;
            /* Tăng kích thước chữ */
            padding: 1rem;
            /* Tăng khoảng cách giữa các dòng */
        }
        .table-custom th{
            font-size: 1.2rem;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chủ đề bài viết</h3>

                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chủ đề</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="{{ route('admin.topic') }}">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm chủ đề..." name="keyword"
                                    value="{{ request('name') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.topic.add') }}"><i class="icon-plus"></i>Thêm
                        mới</a>
                </div>

                <div class="table-responsive table-custom">
                    @if (Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 40px;">STT</th>
                                <th scope="col">Tên chủ đề</th>
                                <th scope="col" style="">Chủ đề cha</th>
                                <th scope="col" style="width: 150px;">Thời gian tạo</th>
                                <th scope="col" style="width: 70px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $index => $topic)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $topic->name }}</td>
                                    <td>
                                        {{ $topic->parent_id == 0 ? 'Không có' : $topics->firstWhere('id', $topic->parent_id)?->name }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($topic->created_at)->format('H:i d/m/Y') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown dropstart">
                                            <div class="border-0" type="button" data-bs-toggle="dropdown"
                                                data-bs-display="static">
                                                <i class="icon-settings fs-4"></i>
                                            </div>
                                            <ul class="dropdown-menu">
                                                <li class="py-1">
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                        href="{{ route('admin.topic.edit', ['id' => $topic->id]) }}">
                                                        <i class="icon-edit-3 text-primary"></i> Sửa
                                                    </a>
                                                </li>
                                                <li class="py-1">
                                                    <form action="{{ route('admin.topic.delete', ['id' => $topic->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="delete dropdown-item d-flex align-items-center gap-2 text-danger">
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
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $topics->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $(".delete").on('click', function(e) {
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
        });
    </script>
@endpush
