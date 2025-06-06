@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Liên hệ')
@section('content')
  <header class="page-header wow fadeInUp" data-wow-delay="0.5s" data-background="img/contact.jpg">
    <div class="container">
      <h2>Contact Us</h2>
      <div class="bosluk3"></div>
      <p><a href="index-2.html">Home</a> <i class="flaticon-right-chevron"></i> Contact Us </p>
    </div>
    <!-- end container --> 
  </header>
  <main>
      <!--İletişim İcon Alanı-->
      <section class="iletisim-icon-alani">
          <div class="tablo">
              <!--telefon 1-->
              <div class="tablo--1-ve-3 wow fadeInLeft" data-wow-delay="0.5s">
                  <div class="ozellik-kutu-iletisim" onclick="location.href='tel:0559868636';" style="cursor:pointer;" data-tilt>
                      <div class="icon"><i class="flaticon-call"></i></div>
                      <h3 class="baslik-4 h-yazi-margin-kucuk-2">Tổng đài</h3>
                      <p class="ozellik-kutu-iletisim--yazi">0559868636</p>
                  </div>
              </div>
              <!--mail 2-->
              <div class="tablo--1-ve-3 wow fadeInLeft" data-wow-delay="0.6s">
                  <div class="ozellik-kutu-iletisim" onclick="location.href='mailto:8-bit@gmail.com';" style="cursor:pointer;" data-tilt>
                      <div class="icon"><i class="flaticon-email"></i></div>
                      <h3 class="baslik-4 h-yazi-margin-kucuk-2">Thư điện tử</h3>
                      <p class="ozellik-kutu-iletisim--yazi">
                          8-bit@gmail.com
                      </p>
                  </div>
              </div>
              <!--adres 3-->
              <div class="tablo--1-ve-3 wow fadeInRight" data-wow-delay="0.7s">
                  <div class="ozellik-kutu-iletisim" onclick="window.open('https://maps.app.goo.gl/Bier2hwUoiRf9ZHW6', '_blank');" style="cursor:pointer;" data-tilt>
                      <div class="icon"><i class="flaticon-location"></i></div>
                      <h3 class="baslik-4 h-yazi-margin-kucuk-2">Trung tâm</h3>
                      <p class="ozellik-kutu-iletisim--yazi">
                          Lạc Long Quân, Đông Vệ TP Thanh Hóa
                      </p>
                  </div>
              </div>
          </div>
      </section>
      <!--İletişim Form Alanı-->
      <section class="iletisim-form-alani">
        <div class="container">
            <div class="row">
              <div class="col-sm">
                <form action="https://garantiwebtasarim.com/life-hotel-hotel-booking/contactform.php" class="form" method="post">
                    <div class="form__grup wow fadeInLeft" data-wow-delay="0.5s">
                        <input type="text" class="form__input" placeholder="Full Name" id="txt_isim" name="name" required>
                        <label for="name" class="form__label">Full Name</label>
                    </div>
              </div>
              <div class="col-sm">
                <div class="form__grup wow fadeInLeft" data-wow-delay="0.6s">
                    <input type="email" class="form__input" placeholder="Email Address" id="txt_eposta" name="email" required>
                    <label for="email" class="form__label">Email Address</label>
                </div>
              </div>
              <div class="col-sm">
                <div class="form__grup wow fadeInRight" data-wow-delay="0.7s">
                    <input type="text" class="form__input" placeholder="Phone Number" id="txt_telefon" name="telefon" required>
                    <label for="telefon" class="form__label">Phone Number</label>
                </div>
              </div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-sm">
                <div class="form__grup wow fadeInUp" data-wow-delay="0.8s">
                    <textarea name="message" placeholder="Your Message" id="txt_mesaj" class="form__input"></textarea>
                    <label for="message" class="form__label"> Your Message ...</label>
                </div>
                </div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-sm">
                <div class="form__grup wow fadeInUp" data-wow-delay="0.9s">
                    <button class="buton-contact buton-contact--kirmizi"  id="btn_Gonder">SEND</button>
                </div>
                </div>
            </div>
          </div>
      
          
                  </form>
              </div>
              
          </div>
      </section>
  </main>
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3754.075716547218!2d105.7807484926249!3d19.7943867916236!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313657162e36bca7%3A0x47fec310abe7ced5!2zVHJ1bmcgdMOibSBTw6FuZyB04bqhbyBDw7RuZyBuZ2jhu4cgOC1iaXQ!5e0!3m2!1svi!2s!4v1748775376805!5m2!1svi!2s" width="100%" height="450px" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
@endsection