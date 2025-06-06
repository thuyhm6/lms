<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="ThuyHM6" />
    <title>@yield('title')</title>
<!-- Trong <head> -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/css/icon-font.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/odometer.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/accordion.css') }}">
    @stack("styles")
</head>
<body class="gradient-bg">
    
    <div class="preloader">
          <figure> <img src="{{ asset('images/logo/logo.png') }}" alt="Image"> </figure>
        </div>
      <div class="page-transition"></div>
      <aside class="side-widget">
          <div class="inner">
          <!-- Logo Menu Mobile -->
            <div class="logo"> <a href="{{ route('home.index') }}"><img src="{{ asset('images/logo/logo.png') }}" alt="Image"></a> </div>
            <div class="hide-mobile">
              <div class="or">
                  <h2 class="h2-baslik-anasayfa-ozel1"> Contact Information </h2>
              </div>
              <div class="bosluk2dt"></div>
              <div class="iconsv"><i class="flaticon-call"></i></div>
              <address class="address">
              <p><a href="#">0559.868.636</a>
                  <div class="bosluk2dt"></div>
                  <div class="iconsv"><i class="flaticon-email"></i></div>
                  <a href="#">8-bit@gmail.com</a>
                  <div class="bosluk2dt"></div>
                  <div class="iconsv"><i class="flaticon-location"></i></div>
                  <a href="#">136 Lạc Long Quân, Đông Vệ, TP Thanh Hóa</a>
                  <div class="bosluk2dt"></div>
                  <div class="or">
                      <a href="#"><img src="img/facebook1.png" alt=""></a>
                      <a href="#"><img src="img/instagram1.png" alt=""></a>
                      <a href="#"><img src="img/twitter1.png" alt=""></a>
                      <a href="#"><img src="img/google1.png" alt=""></a>
                  </div>
              </p>
              </address>
            </div>
            <div class="show-mobile">
              <div class="site-menu">
                  <ul class="menu">
                      {{-- <li><a href="{{ route('home.index') }}">Trang chủ</a></li> --}}
                      <li><a href="{{ route('home.about') }}">Về chúng tôi</a></li>
                      <li><a href="{{ route('home.coures') }}">Khóa học </a><span class="sb" style="font-size: 18px">+</span>
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="{{ route('home.coures') }}">Lập trình Scratch</a></li>
                            </ul>
                      </li>
                      <li><a href="{{ route('shop.index') }}">Sản phẩm</a></li>
                      <li><a href="{{ route('home.activities') }}">Hoạt động</a></li>
                      <li><a href="{{ route('home.news') }}">Tin tức</a></li>
                      <li><a href="{{ route('home.contact') }}">Liên hệ</a></li>
                      @guest
                        <li>
                          <a href="{{ route('login') }}">
                            Cá nhân
                          </a>
                        </li>
                        @else
                            <li>
                                <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}">                                    
                                  <span class="pr-6px">{{ Auth::user()->username }}</span>
                                </a>
                            </li>
                        @endguest
                  </ul>
                </div>
            </div>
            <small>© 2025 - 8-bit</small> </div>
        </aside>
        <nav class="navbar">
          <div class="container">
          <!-- Logo Menu Desktop -->
            <div class="logo"> <a href="{{ route('home.index') }}"><img src="{{ asset('images/logo/logo.png') }}" alt="Image"></a> </div>
            <div class="site-menu">
              <ul class="menueffect">
                  {{-- <li><a href="{{ route('home.index') }}">Trang chủ</a></li> --}}
                  <li><a href="{{ route('home.about') }}">Chúng tôi</a></li>
                  <li><a href="{{ route('home.coures') }}">Khóa học</a><span class="sb" style="font-size: 18px">+</span>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="{{ route('home.coures') }}">Lập trình Scratch</a></li>
                        </ul>
                  </li>
                  <li><a href="{{ route('shop.index') }}">Sản phẩm</a></li>
                  <li><a href="{{ route('home.activities') }}">Hoạt động</a></li>
                  <li><a href="{{ route('home.news') }}">Tin tức</a></li>
                  <li><a href="{{ route('home.contact') }}">Liên hệ</a></li>
                  @guest
                    <li>
                      <a href="{{ route('login') }}">Cá nhân</a>
                    </li>
                    @else
                        <li>
                            <a href="{{ Auth::user()->utype === 'ADM' ? route('admin.index') : route('user.index') }}">
                              <span class="pr-6px">{{ Auth::user()->username }}</span>
                            </a>
                        </li>
                    @endguest
              </ul>
            </div>
            <div class="hamburger-menu"> <span></span> <span></span> <span></span> </div>
            <div class="navbar-button"> <a onclick="location.href='tel:0559868636';" style="cursor:pointer;"><i class="flaticon-call iconp" ></i>&nbsp;&nbsp;&nbsp;0559868636</a> </div>
          </div>
        </nav>
  
    @yield("content")
  
    <footer class="footer">
      <div class="container">
          <div class="row">
            <div class="col-xl-3 col-lg-4">
              <div class="logo wow fadeInUp" data-wow-delay="0.3s"> <img src="{{ asset('images/logo/logo_8bit.png') }}" alt="Image"> </div>
              <!-- end logo -->
              
            </div>
            <!-- end col-3 -->
            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.6s">
              <div class="footer-info wow fadeInUp" data-wow-delay="0.4s">
                  <p><i class="flaticon-call iconpfooter"></i>&nbsp;&nbsp;&nbsp;0559.868.636</p><br>
                  <div class="bosluk04"></div>
                  <p><i class="flaticon-email iconpfooter"></i>&nbsp;&nbsp;&nbsp;8-bit@gmail.com</p><br>
                  <p><i class="flaticon-location iconpfooter"></i>Lạc Long Quân, Đông Vệ, TP Thanh Hóa</p>
              </div>
              <!-- end footer-info -->
              <ul class="footer-social wow fadeInUp" data-wow-delay="0.5s">
                  <li><a href="#"><img width="25" height="25" src="img/facebook.png" alt="Facebook"></a></li>
                  <li><a href="#"><img width="25" height="25" src="img/instagram2.png" alt="Instagram"></a></li>
                  <li><a href="#"><img width="25" height="25" src="img/twitter.png" alt="Twitter"></a></li>
                </ul>   
            </div>
            <!-- end col-4 -->
            <div class="col-lg-2 offset-xl-1 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
              <h6 class="widget-title">Services</h6>
              <ul class="footer-menu">
                  <li><a href="{{ route('home.index') }}">Trang chủ</a></li>
                  <li><a href="{{ route('home.about') }}">Chúng tôi</a></li>
                  <li><a href="{{ route('home.coures') }}">Khóa học</a></li>
              </ul>
            </div>
            <!-- end col-2 -->
            <div class="col-lg-2 col-sm-6 wow fadeInUp" data-wow-delay="0.8s">
              <h6 class="widget-title">Quick Links</h6>
              <ul class="footer-menu">
                  <li><a href="{{ route('shop.index') }}">Sản phẩm</a></li>
                  <li><a href="{{ route('home.activities') }}">Hoạt động</a></li>
                  <li><a href="{{ route('home.news') }}">Tin tức</a></li>
                  <li><a href="{{ route('home.contact') }}">Liên hệ</a></li>
              </ul>
            </div>
            <!-- end col-2 -->
            
            <!-- end col-12 --> 
          </div>
          <!-- end row --> 
        </div>
        <div class="col-12 wow fadeInUp" data-wow-delay="0.9s">
          <p class="copyright">© 2025 8-bit.</p>
        </div>
      <div id="top" style="cursor: pointer;">
          <img width="50" height="50" src="img/go-top.png" alt=""/>
      </div>
      
  </footer>
  
    <script src="{{ asset('client/js/team.js') }}"></script>
      <script src="{{ asset('client/js/jquery.min.js') }}"></script> 
      <script src="{{ asset('client/js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('client/js/bootstrap.bundle.min.js') }}"></script> 
      <script src="{{ asset('client/js/fancybox.min.js') }}"></script> 
      <script src="{{ asset('client/js/swiper.min.js') }}"></script> 
      <script src="{{ asset('client/js/odometer.min.js') }}"></script> 
      <script src="{{ asset('client/js/wow.min.js') }}"></script> 
      <script src="{{ asset('client/js/scripts.js') }}"></script>
      <script src="{{ asset('client/js/3d.jquery.js') }}"></script>
      <script src="{{ asset('client/js/jquery-1.11.3.min.js') }}"></script>
      <script src="{{ asset('client/js/pointer.js') }}"></script>
      <!--Cursor Script-->                    
      <script>
          init_pointer({
              
          })
      </script>
      <script>
        $(document).ready(function() {
            $('#btn_Gonder').click(function() {
                
            //alert("test");
            
                var isim = $('#txt_isim').val();
                var eposta = $('#txt_eposta').val();
                var telefon = $('#txt_telefon').val();
                var mesaj = $('#txt_startdate').val();
                var mesaj = $('#txt_finishdate').val();
                var mesaj = $('#txt_adult').val();
                var mesaj = $('#txt_child').val();
                    var json_data = {};
                    json_data.isim = isim;
                    json_data.eposta = eposta;
                    json_data.telefon = telefon;
                    json_data.startdate = startdate;
                    json_data.finishdate = finishdate;
                    json_data.adult = adult;
                    json_data.child = child;
                    $.ajax({
                        url: "form/Check-Form-Send.php",
                        method: "post",
                        data: json_data,
                        success: function(response) {
                            if (response == "Success") {
                                alert("Mesajınız Gönderildi.");
                            } else if (response == "Failed") {
                                alert("Mesajınız Gönderilemedi. Lütfen Tekrar Deneyiniz.");
                            } 
                            console.log(response);
                            if (response.trim() == "Success") console.log("Success");
                            else
                                console.log("Failed :  " + response);
                        }
                    });
            });
        });
      </script>
    @stack("scripts");
  </body>

</html>
