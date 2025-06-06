@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Hoạt động')
@section('content')
    <header class="page-header wow fadeInUp" data-wow-delay="0.5s" data-background="img/gallery.jpg">
        <div class="container">
          <h2>Gallery</h2>
          <div class="bosluk3"></div>
          <p><a href="index-2.html">Home</a> <i class="flaticon-right-chevron"></i> Gallery </p>
        </div>
        <!-- end container --> 
    </header>
    <section class="gallery-alani">
            <div class="container">
                <div class="row">
                <div class="col-12">
                    <div class="h-yazi-ortalama h-yazi-margin-orta-3">
                        <h2 class="h2-baslik-hizmetler-2 wow fade">Gallery</h2>
                    </div>
                    <div class="bosluk3"></div>
                </div>
                </div>
            </div>
            <div class="grid-container">
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
                        <img src="img/gallery-big-8.jpg" alt="Galeri" style="width:100%">
                      </div>
                    <div class="mySlides">
                        <img src="img/gallery-big-2.jpg" alt="Galeri" style="width:100%">
                    </div>
                    <div class="mySlides">
                        <img src="img/gallery-big-3.jpg" alt="Galeri" style="width:100%">
                      </div>
                    <div class="mySlides">
                        <img src="img/gallery-big-7.jpg" alt="Galeri" style="width:100%">
                    </div> 
                    <div class="mySlides">
                        <img src="img/gallery-big-5.jpg" alt="Galeri" style="width:100%">
                      </div>
                    <div class="mySlides">
                        <img src="img/gallery-big-3.jpg" alt="Galeri" style="width:100%">
                    </div> 
                    <div class="mySlides">
                        <img src="img/gallery-big-7.jpg" alt="Galeri" style="width:100%">
                      </div>
                    <div class="mySlides">
                        <img src="img/gallery-big-8.jpg" alt="Galeri" style="width:100%">
                    </div> 
                    <div class="mySlides">
                        <img src="img/gallery-big-9.jpg" alt="Galeri" style="width:100%">
                      </div>
                    <div class="mySlides">
                        <img src="img/gallery-big-10.jpg" alt="Galeri" style="width:100%">
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