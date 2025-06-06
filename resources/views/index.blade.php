@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Trang chủ')
@section('content')
  <header class="slider">
    <div class="main-slider">
    <div class="swiper-wrapper">
      @foreach ($slides as $slide)
        <div class="swiper-slide">
            <div class="slide-image" data-background="{{ asset('uploads/slides')}}/{{ $slide->image }}"></div>
            <div class="container">
                <h1><span style="font-size:40px;">{{ $slide->title }}</h1>
                <p>{{ $slide->subtitle }}</p>
                <div class="ortabuton"> <a href="#">{{ $slide->link }}</a></div> 
            </div>
        </div>
      @endforeach
        
        <!-- end container --> 
        </div>
    </div>
    <div class="button-prev">❮</div>
    <div class="button-next">❯</div>
    <div class="swiper-pagination"></div>
    </header>
    <!--Quality Alanı-->
    <!--Quality 1-->
    {{-- <div class="boslukq3"></div> --}}
    
    <!--Rooms-->
    <section class="room">
        <div class="h-yazi-ortalama h-yazi-margin-orta-3">
            <h2 class="h2-baslik-hizmetler-2 wow fadeInUp" data-wow-delay="0.3s">KHÓA HỌC</h2>
            <div class="bosluk333"></div>
            <img class="divider" width="363" height="38" title="divider" alt="divider" src="img/room-divider.png">
            <div class="bosluk333"></div>
            <p class="h2-baslik-hizmetler-2__paragraf wow fadeInUp" data-wow-delay="0.4s">
                Những khóa học đang triển khai tại trung tâm.
            </p>
        </div>
        <div class="bosluk3"></div>
        <div class="container">  
            <div class="carousel-classes">
                <div class="swiper-wrapper">
                  @foreach ($courses->chunk(2) as $chunk)
                    <div class="swiper-slide">
                      @foreach ($chunk as $index => $course)
                        <div class="pro-tabs">
                            <div class="pro-content current wow fade">
                                <div class="pros">
                                    <div class="prow wow fadeInLeft" data-wow-delay="0.5s" onclick="window.location.href='room-detail.html'">
                                        <img src="{{ asset($course->image ?? 'images/default.png') }}" alt="Room">
                                        <div class="pro-card-content">
                                            <h2>{{ $course->course_name }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      @endforeach
                    </div>
                  @endforeach
                <!-- end swiper-slide -->
            </div>
            <!-- end swiper-wrapper -->
            <div class="swiper-pagination"></div>
            <!-- end swiper-pagination -->
            </div>
        </div> 
    </section>
  <!--About Top-->
    <section class="services-top">
        <div class="bosluk3"></div>
        <div class="tablo">
            <div class="tablo--1-ve-2 wow slideInUp" data-wow-delay="0.3s">
                <div class="galeri">
                    <img src="{{ asset('images/about-us/about-us.jpg') }}" alt="life hotel about" class="galeri__gorsel galeri__gorsel--3 zimage">
                </div>
            </div>           
            <!--Galeri Görsel Alanı-->
            <div class="tablo--1-ve-3 wow fadeInRight" data-wow-delay="0.4s">
                <h2 class="h2-baslik-anasayfa-ozel1 wow fadeInRight" data-wow-delay="0.5s"><strong>Đôi nét về chúng tôi</strong></h2>
                <div class="bosluk333"></div>
                <p class="paragraf wow fadeInRight" data-wow-delay="0.6s">
                Trung tâm Sáng tạo Công nghệ 8-bit là đơn vị đầu tiên tại Thanh Hóa đào tạo công nghệ, lập trình và AI cho trẻ em. Mỗi học viên tại trung tâm đều được lộ trình hóa cá nhân, giúp các bé khai mở tiềm năng, phát triển đúng năng lực cá nhân. Với đội ngũ giảng viên giàu kinh nghiệm, chuyên môn sâu, và chương trình học bài bản, giúp các con phát huy các điểm mạnh cá nhân và định hướng nghề nghiệp cho trẻ trong tương lai.
                </p>
                  <div class="bosluk333"></div>
                <img class="divider" width="120" height="15" title="divider" alt="divider" src="img/divider.jpg">
                <div class="bosluk333"></div>
                <div class="row">
                    <div class="col-sm-2 wow fadeInRight" data-wow-delay="0.7s">
                        <div class="iconleft"><i class="flaticon-hotel-room"></i></div>
                    </div>
                    <div class="col-sm-10 wow fadeInRight" data-wow-delay="0.8s">
                        <h3 class="baslik-3s h-yazi-margin-kucuk1">Cở sở vật chất hiện đại</h3><br>
                        <p class="paragraf-info">Cơ sở vật chất hiện đại.</p><br>
                    </div>
                </div>
                <div class="bosluk1"></div>
                <div class="row">
                    <div class="col-sm-2 wow fadeInRight" data-wow-delay="1.1s">
                        <div class="iconleft"><i class="flaticon-check-in"></i></div>
                    </div>
                    <div class="col-sm-10 wow fadeInRight" data-wow-delay="1.2s">
                        <h3 class="baslik-3s h-yazi-margin-kucuk1">Đội ngũ giáo viên giàu kinh  nghiệm</h3><br>
                        <p class="paragraf-info">Đội ngữ giáo viên giàu kinh nghiệm.</p><br>
                    </div>
                </div>
                <a href="{{ route('home.about') }}" class="custom-button wow fadeInRight" data-wow-delay="1.3s">Tìm hiểu thêm →</a>
                  <div class="bosluk3rh"></div>
            </div>
        </div>  
    </section>

                <!-- Tabs -->
<section class="tabs">
<div class="container">
<div class="row">
    <div class="col-lg-12">
        <div class="season_tabs">
                <div class="season_tab">
                    <input type="radio" id="tab-1" name="tab-group-1" checked>
                    <label for="tab-1">Về chúng tôi</label>
                    <div class="season_content">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <img src="img/about-us1.jpg" alt="">
                                </div>
                                <div class="col-lg-7">
                                    <p class="listtext">
                                        Trung tâm Sáng tạo Công nghệ 8-bit là đơn vị đầu tiên tại Thanh Hóa đào tạo công nghệ, lập trình và AI cho trẻ em. Mỗi học viên tại trung tâm đều được lộ trình hóa cá nhân, giúp các bé khai mở tiềm năng, phát triển đúng năng lực cá nhân.
                                    </p>
                                    <div class="bosluk3"></div>
                                    <p class="listtext"></p>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="season_tab">
                    <input type="radio" id="tab-2" name="tab-group-1" checked>
                    <label for="tab-2">Tầm nhìn</label>
                    <div class="season_content">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <img src="img/about-us2.jpg" alt="">
                                </div>
                                <div class="col-lg-7">
                                    <p class="listtext">
                                        Trở thành trung tâm hàng đầu, giúp trẻ em sáng tạo, rèn luyện tư duy và làm chủ công nghệ, sẵn sàng cho tương lai số.
                                    </p>
                                    <div class="bosluk3"></div>
                                    <p class="listtext"></p>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="season_tab">
                    <input type="radio" id="tab-3" name="tab-group-1" checked>
                    <label for="tab-3">Sứ mệnh</label>
                    <div class="season_content">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <img src="img/about-us3.jpg" alt="">
                                </div>
                                <div class="col-lg-7">
                                    <p class="listtext">
                                        Truyền cảm hứng, cung cấp kiến thức và trang bị kỹ năng công nghệ, giúp trẻ tự tin sáng tạo và phát triển trong kỷ nguyên số.
                                    </p>
                                    <div class="bosluk3"></div>
                                    <p class="listtext"></p>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="season_tab">
                    <input type="radio" id="tab-4" name="tab-group-1" checked>
                    <label for="tab-4">Giá trị cốt lõi</label>
                    <div class="season_content">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-5">
                                    <img src="img/about-us4.jpg" alt="">
                                </div>
                                <div class="col-lg-7">
                                    <p class="listtext">
                                        Sáng tạo, thực hành, tư duy công nghệ, hợp tác và trách nhiệm, giúp trẻ học hỏi, phát triển và ứng dụng công nghệ tích cực.
                                    </p>
                                    <div class="bosluk3"></div>
                                    <p class="listtext"></p>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>
<!--Yorumlar-->
<section class="yorumlar-alani-sayfa">
<div class="container">
    <div class="row">
    <div class="col-12">
        <div class="h-yazi-ortalama h-yazi-margin-orta-3">
            <h2 class="h2-baslik-hizmetler-2 wow fadeInUp" data-wow-delay="0.4s">Phụ huynh và học sinh<strong> Nói Gì Về Chúng Tôi</strong> </h2>
        </div>
        {{-- <div class="bosluk3ps"></div> --}}
    </div>
        <div class="col-12">
        <div class="carousel-classes">
                <div class="swiper-wrapper">
            <div class="swiper-slide wow fadeInLeft" data-wow-delay="0.5s">
                <div class="class-box">
                <div class="testimonial-card">
                    <div class="testimon-text">
                        Học ở 8 Bit rất tốt.
                        <i class="fas fa-quote-right quote"></i>
                    </div>
                    <div class="testimonialimg">
                        <div class="testimonimg"><img src="img/testimonial1.png" alt="">
                        </div>
                        <h3 class='person'>Thành Anh</h3>
                    </div>
                    </div>
                    </div>
                    </div>
                    <!-- end swiper-slide -->
            <div class="swiper-slide wow fadeInLeft" data-wow-delay="0.6s">
                <div class="class-box">
                <div class="testimonial-card">
                    <div class="testimon-text">
                        Học ở 8 Bit rất tốt.
                        <i class="fas fa-quote-right quote"></i>
                    </div>
                    <div class="testimonialimg">
                        <div class="testimonimg"><img src="img/testimonial2.png" alt="">
                        </div>
                        <h3 class='person'>Mỹ Linh</h3>
                    </div>
                    </div>
                </div>
                    </div>
                    <!-- end swiper-slide -->
            <div class="swiper-slide wow fadeInRight" data-wow-delay="0.7s">
                <div class="class-box">
                <div class="testimonial-card">
                    <div class="testimon-text">
                        Học ở 8 Bit rất tốt.
                        <i class="fas fa-quote-right quote"></i>
                    </div>
                    <div class="testimonialimg">
                        <div class="testimonimg"><img src="img/testimonial3.png" alt="">
                        </div>
                        <h3 class='person'>Duyên</h3>
                    </div>
                    </div>
                </div>
                    </div>
                    <!-- end swiper-slide -->
            <div class="swiper-slide wow fadeInRight" data-wow-delay="0.8s">
                <div class="class-box">
                <div class="testimonial-card">
                    <div class="testimon-text">
                        Học ở 8 Bit rất tốt.
                        <i class="fas fa-quote-right quote"></i>
                    </div>
                    <div class="testimonialimg">
                        <div class="testimonimg"><img src="img/testimonial4.png" alt="">
                        </div>
                        <h3 class='person'>Huyền</h3>
                    </div>
                    </div>
                    </div>
                    </div>
                    <!-- end swiper-slide -->
            </div>
            <!-- end swiper-wrapper -->
            <div class="swiper-pagination"></div>
            <!-- end swiper-pagination -->
            </div>
        </div>
        <!-- end col-12 -->
    </div>
</div>
</section>
<!-- Accordion FAQ-->
<section>
<div class="container">
    <div class="row">
        <div class="col-xl-6">
            <img src="{{ asset('img/loi-ich.jpg') }}" class="" alt="">
        </div>
        <div class="col-xl-6">
            <div class="h-yazi-ortalama h-yazi-margin-orta-3">
                <h2 class="h2-baslik-hizmetler-2"> Lợi ích khi tới với <strong>8-bit</strong> </h2> 
            </div>
                <p class="h2-baslik-hizmetler-yorum__yorum">
                    8-bit luôn đặt trải nghiệm của học viên lên hàng đầu
                </p>
            <div class="bosluk3a"></div>
            <div class="container asa">
                <div class="question">
                    Học và tương tác 1-1
                </div>
                <div class="answercont">
                    <div class="answer">
                        Giáo viên sẽ hướng dẫn và sửa lỗi trực tiếp 1-1 cho các bạn nhỏ.
                    </div>
                </div>
                <div class="question">
                    Cá nhân hóa lộ trình học
                </div>
                <div class="answercont">
                    <div class="answer">
                        Con được thiết kế lộ trình học riêng biệt theo các mức độ tiếp thu khác nhau
                    </div>
                </div>
                <div class="question">
                    Chủ động lịch học
                </div>
                <div class="answercont">
                    <div class="answer">
                        Thời gian học linh hoạt, phù hợp với lịch học cá nhân của con
                    </div>
                </div>
                <div class="question">
                    Định hướng tương lai
                </div>
                <div class="answercont">
                    <div class="answer">
                        Các con sẽ được tiếp xúc với công nghệ từ sớm góp phần định hướng ngành nghề trong tương lai                            
                    </div>
                </div>
                <div class="question">
                    Theo dõi dễ dàng
                </div>
                <div class="answercont">
                    <div class="answer">
                        Phụ huynh có thể nắm bắt tình hình của con một cách dễ dàng và có thể trải nghiệm thực tế cùng các con
                    </div>
                </div>
                <div class="question">
                    Thực hành liên tục
                </div>
                <div class="answercont">
                    <div class="answer">
                        Đan xen với việc học lý thuyết, các con sẽ được thực hành liên tục trong các buổi lên lớp để nắm vũng kiến thức
                    </div>
                </div>
        </div>
    </div>
</div>
</section>                                      
    <!--Posts-->
    <section class="blog-alani-sayfa">
    <div class="container">
        <div class="row">
        <div class="col-12">
            <div class="h-yazi-ortalama h-yazi-margin-orta-3">
                <h2 class="h2-baslik-hizmetler-2 wow fadeInUp" data-wow-delay="0.3s">Resent <strong>Blog</strong> Post </h2>
            </div>
        </div>
            <div class="col-12">
            <div class="carousel-classes">
              <div class="swiper-wrapper">
                @foreach ($news as $newsItem)
                  <div class="swiper-slide wow fadeInLeft" data-wow-delay="0.4s" data-tilt>
                    <div class="post-kutu" onclick="location.href='what-is-a-suite-room.html';" style="cursor:pointer;">
                        <img src="{{ asset($newsItem->image ?? 'images/default.png') }}" alt="Haber 1" class="haber-gorsel">
                        <div class="datesection"><span class="date">{{ $newsItem->updated_at->format('H:i d/m/Y') }}</span>&nbsp;<span class="tt">-</span>&nbsp;<span class="category">Tin tức</span></div>
                        <h3 class="baslik-3 h-yazi-margin-kucuk"> {{ $newsItem->title }}</h3>
                        <p class="post-kutu--yazi">
                            {{ $newsItem->short_intro }}
                        </p>
                        <div class="h-yazi-ortalama h-yazi-margin-4">
                            <a href="{{ route('home.news.detail', ['news_slug' => $newsItem->slug]) }}" class="custom-button">Chi tiết</a>
                        </div>
                    </div>
                  </div>
                @endforeach
                <!-- end swiper-slide -->
              
              </div>
                <!-- end swiper-wrapper -->
                <div class="swiper-pagination"></div>
                <!-- end swiper-pagination -->
                </div>
            </div>
            <!-- end col-12 -->
        </div>
    </div>
    </section>
    <section class="gallery-alani">
        <div class="container">
            <div class="row">
            <div class="col-12">
                <div class="h-yazi-ortalama h-yazi-margin-orta-3">
                    <h2 class="h2-baslik-hizmetler-2 wow fadeInUp" data-wow-delay="0.5s">Gallery</h2>
                </div>
                <div class="bosluk3"></div>
            </div>
            </div>
        </div>
        <div class="grid-container wow fadeInUp" data-wow-delay="0.6s">
            <div>
              <img class='grid-item grid-item-1' src='img/gallery-8.jpg' alt="Galeri" onclick="openModal();currentSlide(1)" >
            </div>
            <div>
              <img class='grid-item grid-item-2' src='img/gallery-2.jpg' alt="Galeri" onclick="openModal();currentSlide(2)" >
            </div>
            <div>
              <img class='grid-item grid-item-3' src='img/gallery-3.jpg' alt="Galeri" onclick="openModal();currentSlide(3)" >
            </div>
            <div>
              <img class='grid-item grid-item-4' src='img/gallery-7.jpg' alt="Galeri" onclick="openModal();currentSlide(4)" >
            </div>
            <div>
              <img class='grid-item grid-item-5' src='img/gallery-5.jpg' alt="Galeri" onclick="openModal();currentSlide(5)" >
            </div>
            <div>
              <img class='grid-item grid-item-6' src='img/gallery-3.jpg' alt="Galeri" onclick="openModal();currentSlide(6)" >
            </div>
            <div>
              <img class='grid-item grid-item-7' src='img/gallery-7.jpg' alt="Galeri" onclick="openModal();currentSlide(7)" >
            </div>
            <div>
              <img class='grid-item grid-item-8' src='img/gallery-8.jpg' alt="Galeri" onclick="openModal();currentSlide(8)" >
            </div>
            <div>
              <img class='grid-item grid-item-9' src='img/gallery-9.jpg' alt="Galeri" onclick="openModal();currentSlide(9)" >
            </div>
            <div>
              <img class='grid-item grid-item-10' src='img/gallery-10.jpg' alt="Galeri" onclick="openModal();currentSlide(10)" >
            </div>
          </div>
          <!-- The Modal/Lightbox -->
        <div id="myModal" class="modal">
            <span class="close cursor" onclick="closeModal()">&times;</span>
            <div class="modal-content">
                <div class="mySlides">
                    <img src="img/gallery-8.jpg" alt="Galeri" style="width:100%">
                  </div>
                <div class="mySlides">
                    <img src="img/gallery-2.jpg" alt="Galeri" style="width:100%">
                </div>
                <div class="mySlides">
                    <img src="img/gallery-3.jpg" alt="Galeri" style="width:100%">
                  </div>
                <div class="mySlides">
                    <img src="img/gallery-7.jpg" alt="Galeri" style="width:100%">
                </div> 
                <div class="mySlides">
                    <img src="img/gallery-5.jpg" alt="Galeri" style="width:100%">
                  </div>
                <div class="mySlides">
                    <img src="img/gallery-3.jpg" alt="Galeri" style="width:100%">
                </div> 
                <div class="mySlides">
                    <img src="img/gallery-7.jpg" alt="Galeri" style="width:100%">
                  </div>
                <div class="mySlides">
                    <img src="img/gallery-8.jpg" alt="Galeri" style="width:100%">
                </div> 
                <div class="mySlides">
                    <img src="img/gallery-9.jpg" alt="Galeri" style="width:100%">
                  </div>
                <div class="mySlides">
                    <img src="img/gallery-10.jpg" alt="Galeri" style="width:100%">
                </div>
            </div>
            <!-- Next/previous controls -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
  
            <!-- Caption text -->
            <div class="caption-container">
              <p id="caption"></p>
            </div>
            <!-- Thumbnail image controls -->
            <div class="grid-container">
              <img class="demo" src="img/gallery-8.jpg" onclick="currentSlide(1)" alt="Image Name 1" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-2.jpg" onclick="currentSlide(2)" alt="Image Name 2" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-3.jpg" onclick="currentSlide(3)" alt="Image Name 3" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-7.jpg" onclick="currentSlide(4)" alt="Image Name 4" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-5.jpg" onclick="currentSlide(4)" alt="Image Name 5" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-3.jpg" onclick="currentSlide(4)" alt="Image Name 6" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-7.jpg" onclick="currentSlide(4)" alt="Image Name 7" style="display: none; width:1%;">
              <img class="demo" src="img/gallery-8.jpg" onclick="currentSlide(4)" alt="Image Name 8" style="display: none; width:1%;">
            </div>
        </div>   
    </section>
@endsection