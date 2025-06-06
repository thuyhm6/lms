@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Về chúng tôi')
@section('content')
  <header class="page-header wow fadeInUp" data-wow-delay="0.5s" data-background="img/about-us.jpg">
    <div class="container">
      <h2>About Us</h2>
      <div class="bosluk3"></div>
      <p><a href="index-2.html">Home</a> <i class="flaticon-right-chevron"></i> About Us</p>
    </div>
    <!-- end container --> 
  </header>
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
                  <div class="bosluk3rh"></div>
            </div>
    </div>  
</section>
<!--Count-->
<section class="count-alani">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-3 col-md-6 wow fadeInLeft" data-wow-delay="0.5s">
          <div class="iconw"><i class="flaticon-family-room"></i></div>
        <div class="counter-box wow fade">
          <span class="odometer" data-count="851" data-status="yes">0</span>
          <h6>Học sinh</h6>
        </div>
      </div>
      <!-- end col-4 -->
      <div class="col-lg-3 col-md-6 wow fadeInLeft" data-wow-delay="0.6s">
          <div class="iconw"><i class="flaticon-hotel-room"></i></div>
        <div class="counter-box wow fade">
          <span class="odometer" data-count="389" data-status="yes">0</span>
          <h6>Phòng học</h6>
        </div>
      </div>
      <!-- end col-4 -->
      <div class="col-lg-3 col-md-6 wow fadeInRight" data-wow-delay="0.7s">
          <div class="iconw"><i class="flaticon-hotel-staff"></i></div>
        <div class="counter-box wow fade">
          <span class="odometer" data-count="2462" data-status="yes">0</span>
          <h6>Giáo viên</h6>
        </div>
      </div>
      <!-- end col-4 -->
      <div class="col-lg-3 col-md-6 wow fadeInRight" data-wow-delay="0.8s">
          <div class="iconw"><i class="flaticon-room-service"></i></div>
          <div class="counter-box wow fade">
            <span class="odometer" data-count="20" data-status="yes">0</span>
            <h6>Khóa học</h6>
          </div>
        </div>
        <!-- end col-4 -->  
    </div>
  </div>
</section>
    <div class="bosluk3sh"></div>
    <!--TITLE-->
    <section class="ozellika wow fadeInUp" data-wow-delay="0.3s" data-background="rgb(26 26 26 / 29%)">
      <div class="container">
          <div class="row align-items-center no-gutters">
              <div class="col-lg-12">
                  <div class="wow fade">
                      <div class="boslukalt"></div>
                      <h2 class="h2-baslik-hizmetler-212 wow fade"><strong>Đội ngũ </strong> </h2>  
                  </div>
              </div>
          </div>
      </div>
  </section>    
    <!--Team Alanı-->
    <section class="team-section">
      <div class="container">
        <div class="row">
            <div class="col-12">
            <div class="carousel-classes">
              <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="class-box">
                      <div class="services-kutu2 wow fadeInLeft" data-wow-delay="0.4s" style="cursor:pointer;">
                          <div class="member-box wow reveal-effect">
                              <figure> <img src="img/team1.png" alt="Image">
                                <figcaption>
                                  <h6>Lê Minh Đức</h6>
                                  <p class="paragraf-sol-beyaz-orta">CEO</p>
                                </figcaption>
                              </figure>
                            </div>
                          </div>
                        </div>
                        </div>
                  <!-- end swiper-slide -->
                <div class="swiper-slide">
                    <div class="class-box">
                      <div class="services-kutu2 wow fadeInLeft" data-wow-delay="0.5s" style="cursor:pointer;">
                          <div class="member-box wow reveal-effect">
                              <figure> <img src="img/team2.png" alt="Image">
                                <figcaption>
                                  <h6>Khánh Huyền</h6>
                                  <p class="paragraf-sol-beyaz-orta">Thành phần chính</p>
                                </figcaption>
                              </figure>
                          </div>
                      </div>
                    </div>
                </div>
                  <!-- end swiper-slide -->
                <div class="swiper-slide">
                    <div class="class-box">
                      <div class="services-kutu2 wow fadeInRight" data-wow-delay="0.6s" style="cursor:pointer;">
                          <div class="member-box wow reveal-effect">
                              <figure> <img src="img/team3.png" alt="Image">
                                <figcaption>
                                  <h6>Thành Anh</h6>
                                  <p class="paragraf-sol-beyaz-orta">Thành phần cộm cán</p>
                                </figcaption>
                              </figure>
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
@endsection