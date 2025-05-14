{{-- schedules/partials/class_table.blade.php --}}
@if ($classes->isEmpty())
    <tr>
        <td colspan="6">Không tìm thấy lớp học.</td>
    </tr>
@else
    @foreach ($classes as $index => $class)
        <tr>
            <td>{{ ($classes->currentPage() - 1) * $classes->perPage() + $loop->iteration }}</td>
            <td class="text-start">
                <strong>{{ $class->ma_lop }}</strong><br>
                {{ $class->ten_lop }}
            </td>
            <td>
                <span class="btn btn-info btn-sm text-white">
                    {{ $class->giao_vien_phu_trach_chinh }}
                </span>
            </td>
            <td>
                <span class="btn btn-danger btn-sm">
                    {{ $class->hinh_thuc == 'online' ? 'Online' : 'Offline' }}
                </span>
            </td>
            <td>
                <a href="{{ route('class.students', $class->id) }}">Xem danh sách({{ $class->so_hoc_sinh }})</a>
            </td>
            <td class="text-center">
                @if ($class->trang_thai_lop_hoc == 1)
                    <span class="btn btn-sm btn-warning text-white">Kết thúc</span>
                @elseif ($class->trang_thai_lop_hoc == 0)
                    <span class="btn btn-sm btn-success">Đang học</span>
                @else
                    <span class="btn btn-sm btn-secondary">Không rõ</span>
                @endif
            </td>
            <td class="position-relative">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                Chức năng
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('class.schedule', $class->id) }}">
                                                        Danh sách lịch học
                                                    </a>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" data-id="{{ $class->id }}"
                                                        onclick="openModal(this)">Thêm
                                                        lịch học</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
        </tr>
    @endforeach
@endif