{{-- Create this file at resources/views/class/partials/class_table.blade.php --}}
<tbody>
    @foreach ($classes as $class)
        <tr>
            <td>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        ⚙️
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item"
                                href="{{ route('class.students', ['id' => $class->sap_xep]) }}">📋
                                Danh sách học sinh</a></li>
                        <li><a class="dropdown-item"
                                href="{{ route('class.edit', ['id' => $class->sap_xep]) }}">✏️
                                Sửa</a></li>
                        <li><a class="dropdown-item" href="#">📊 Xem báo cáo kết quả học
                                tập</a></li>
                        <li>
                            <a href="#" class="dropdown-item text-danger"
                                onclick="event.preventDefault(); if(confirm('Bạn có chắc chắn muốn xóa lớp này?')) document.getElementById('delete-form-{{ $class->sap_xep }}').submit();">
                                🗑️ Xóa
                            </a>
                            <form id="delete-form-{{ $class->sap_xep }}"
                                action="{{ route('class.destroy', ['id' => $class->sap_xep]) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </li>
                    </ul>
                </div>
            </td>
            <td>{{ $class->sap_xep }}</td>
            <td>{{ $class->ma_lop }}</td>
            <td>{{ $class->ten_lop }}</td>
            <td>
                <span class="btn btn-sm btn-info text-white">{{ $class->lich_hoc }}</span>
            </td>
            <td class="text-center">
                <span
                    class="btn btn-sm btn-info text-white">{{ $class->giao_vien_phu_trach_chinh }}</span>
            </td>
            <td class="text-center">
                <span class="btn btn-sm btn-primary text-white">{{ $class->nhan_vien }}</span>
            </td>
            <td class="text-center">
                @if ($class->hinh_thuc === 'offline')
                    <span class="btn btn-sm btn-danger">Offline</span>
                @elseif ($class->hinh_thuc === 'online')
                    <span class="btn btn-sm btn-success">Online</span>
                @elseif ($class->hinh_thuc === 'Hybrid')
                    <span class="btn btn-sm btn-warning text-white">Hybrid</span>
                @else
                    <span
                        class="btn btn-sm btn-secondary">{{ $class->hinh_thuc ?? 'Không rõ' }}</span>
                @endif
            </td>
            <td>{{ $class->mo_ta }}</td>
            <td class="text-center">{{ $class->so_hoc_sinh ?? 'Chưa có' }}</td>
            <td class="text-center">
                @if ($class->trang_thai_lop_hoc === 0)
                    <span class="btn btn-sm btn-warning text-white">Kết thúc</span>
                @elseif ($class->trang_thai_lop_hoc === 1)
                    <span class="btn btn-sm btn-success">Đang học</span>
                @else
                    <span class="btn btn-sm btn-secondary">Không rõ</span>
                @endif
                <br>
                <span class="btn btn-sm btn-info mt-1">{{ $class->active_days }} ngày
                    active</span>
            </td>
            <td class="text-center">
                {{ $class->ngay_tao_lop_hoc ? date('d/m/Y', strtotime($class->ngay_tao_lop_hoc)) : '' }}
            </td>
        </tr>
    @endforeach
    
    @if(count($classes) == 0)
        <tr>
            <td colspan="13" class="text-center">Không tìm thấy dữ liệu</td>
        </tr>
    @endif
</tbody>