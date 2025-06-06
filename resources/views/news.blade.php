@extends('layouts.app') {{-- //Cần check chỗ này --}}
@section('title', 'Tin tức')
@section('content')
    <header class="page-header wow fadeInUp" data-wow-delay="0.5s" data-background="img/blog.jpg">
          <div class="container">
            <h2>Blog</h2>
            <div class="bosluk3"></div>
            <p><a href="index-2.html">Home</a> <i class="flaticon-right-chevron"></i> Blog </p>
          </div>
          <!-- end container --> 
    </header>
    <section class="news-alani-sayfa">
        <div class="h-yazi-ortalama h-yazi-margin-orta-3">
            <h2 class="h2-baslik-hizmetler-2"> Tin tức </h2>
            </div>
            <p class="h2-baslik-hizmetler-2__paragraf">
                Tin mới nhất
            </p><br>
            <div class="bosluk9"></div>
        <div class="tablohizmetler">
            @foreach ($news as $newsItem)
                <div class="tablo--1-ve-4" data-tilt>
                    <div class="post-kutu" onclick="location.href='{{ route('home.news.detail', ['news_slug' => $newsItem->slug]) }}';" style="cursor:pointer;">
                        <img src="{{ asset($newsItem->image ?? 'images/default.png') }}" alt="Haber 1" class="haber-gorsel">
                        <div class="datesection"><span class="date">{{ $newsItem->updated_at->format('H:i d/m/Y') }}</span>&nbsp;<span class="tt">-</span>&nbsp;<span class="category">Tin tức</span></div>
                        <h3 class="baslik-3 h-yazi-margin-kucuk">{{ $newsItem->title }}</h3>
                        <p class="post-kutu--yazi"> {{ $newsItem->short_intro }}</p>
                        <div class="h-yazi-ortalama h-yazi-margin-50">
                        <a href="{{ route('home.news.detail', ['news_slug' => $newsItem->slug]) }}" class="custom-button">Chi tiết</a>
                        </div>
                    </div>
                </div>
            <!--post 2-->
            @endforeach
        </div>
        <div class="alanb"></div>
        
        <div class="blogcount">
            <p class="countb"> 1</p>
            <p class="countb"><a href="blog-page-2.html">2</a></p>
            <p class="countb"><a href="blog-page-2.html">»</a></p>
        </div>
    </section>
@endsection