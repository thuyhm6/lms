{{-- <li><a href="{{ route('user.account.orders') }}" class="menu-link menu-link_us-s">Orders</a></li>
    <li><a href="{{ route('user.account.addresses') }}" class="menu-link menu-link_us-s">Addresses</a></li> --}}

{{-- <li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s">Wishlist</a></li> --}}



<ul class="account-nav">
    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Dashboard</a></li>



    @switch(auth()->user()->utype)

        @case('TEACHER')
            <li><a href="{{ route('teacher.classes') }}" class="menu-link menu-link_us-s">Lớp học quản lý</a></li>
            <li><a href="{{ route('teacher.schedules') }}" class="menu-link menu-link_us-s">Lịch dạy</a></li>
        @break


         @case('STUDENT')
            <li><a href="{{ route('student.registered-course') }}" class="menu-link menu-link_us-s">Khóa học đăng ký</a></li>
            <li><a href="{{ route('student.registered-class') }}" class="menu-link menu-link_us-s">Lớp học</a></li>
            <li><a href="{{ route('student.schedules') }}" class="menu-link menu-link_us-s">Lịch học</a></li>
            <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Kết quả học tập</a></li>
        @break
        @case('PARENT')
            <li><a href="{{ route('parent.registered-course') }}" class="menu-link menu-link_us-s">Khóa học đăng ký của con</a></li>
            <li><a href="{{ route('parent.registered-class') }}" class="menu-link menu-link_us-s">Lớp học của con</a></li>
            <li><a href="{{ route('parent.schedules') }}" class="menu-link menu-link_us-s">Lịch học của con</a></li>
            <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">Kết quả học tập</a></li>
        @break
    @endswitch
    <li><a href="{{ route('user.account.details') }}" class="menu-link menu-link_us-s">Chi tiết tài khoản</a></li>
    <li><a href="{{ route('user.account.change.password') }}" class="menu-link menu-link_us-s">Đổi mật khẩu</a></li>
    <li>
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class="menu-link menu-link_us-s"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
        </form>
    </li>
</ul>
