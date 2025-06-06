@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Khóa học')
@section('content')
  <header class="page-header wow fadeInUp" data-wow-delay="0.5s" data-background="img/hotel-room.jpg">
    <div class="container">
        <h2>Rooms & Rates</h2>
        <div class="bosluk3"></div>
        <p><a href="index-2.html">Home</a> <i class="flaticon-right-chevron"></i> Rooms & Rates </p>
    </div>
    <!-- end container --> 
    </header>
    <section class="room">
        <div class="h-yazi-ortalama h-yazi-margin-orta-3">
            <h2 class="h2-baslik-hizmetler-2 wow fadeInUp" data-wow-delay="0.3s">Rooms & Rates</h2>
            <div class="bosluk333"></div>
            <img class="divider" width="363" height="38" title="divider" alt="divider" src="img/room-divider.png">
            <div class="bosluk333"></div>
            <p class="h2-baslik-hizmetler-2__paragraf wow fadeInUp" data-wow-delay="0.4s">
                Enjoy a Good Night's Sleep in Our Comfortable Rooms.
            </p>
        </div>
        
        <div class="bosluk3"></div>
        <div class="container"> 
            <div class="row">
                @foreach ($courses as $index => $course)
                    <div class="col-md-6">
                        <h2 class="h2-baslik-anasayfa-ozel1 wow fadeInUp" data-wow-delay="0.5s">{{ $course->course_name }}</h2>
                        <div class="bosluk333"></div>
                        <div class="component-systemTabs">
                            <a href="room-detail.html"><img src="{{ asset($course->image ?? 'images/default.png') }}" alt="Course"></a>
                        </div>
                        <div class="bosluk333"></div>
                        <p class="paragraf wow fadeInLeft" data-wow-delay="0.6s">
                            Khóa học thiết thực, phù hợp với học sinh từ 1-2 tuổi
                        </p>
                        <div class="row wow fadeInLeft" data-wow-delay="0.7s">
                            <div class="col">
                                <p class="paragraf-info">∷ Số lượng: 10 học viên</p><br>
                            </div>
                            <div class="col">
                                <p class="paragraf-info">∷ Số buổi: 12</p><br>
                            </div>
                            <div class="col">
                                <p class="paragraf-info">∷ Thiết bị: Laptop</p><br>
                            </div>
                        </div>
                        <div class="bosluk3ps"></div>
                        <div class="row">
                            <div class="col wow fadeInLeft" data-wow-delay="0.8s">
                                <div class="line">
                                    <h3 class="h3-baslik-anasayfa-ozel1 wow fade">1 buổi /tuần</h3>
                                        <a href="room-detail.html" class="custom-buttonb">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
        
        
    </section>
@endsection